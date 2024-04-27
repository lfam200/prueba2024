<?php
namespace App\Application\Service;

use App\Domain\Repository\UserRepository;
use App\Domain\Repository\TransactionRepository;
use App\Domain\Entity\Transaction;
use App\Exception\AuthorizationFailedException;
use App\Exception\DatabaseOperationException;
use App\Infrastructure\ExternalService\NotificationService;
use App\Infrastructure\ExternalService\AuthorizationService;
use Exception;

/**
 * Class TransactionService
 *
 * This class is responsible for handling transactions between users.
 */
class TransactionService {
    private UserRepository $userRepository;
    private TransactionRepository $transactionRepository;
    private AuthorizationService $authService;
    private NotificationService $notificationService;

    /**
     * TransactionService constructor.
     *
     * @param UserRepository $userRepository
     * @param TransactionRepository $transactionRepository
     * @param AuthorizationService $authService
     * @param NotificationService $notificationService
     */
    public function __construct(UserRepository $userRepository, TransactionRepository $transactionRepository, AuthorizationService $authService, NotificationService $notificationService) {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->authService = $authService;
        $this->notificationService = $notificationService;
    }

    /**
     * Execute a transaction between two users.
     *
     * @param int $payerId The ID of the user making the payment.
     * @param int $payeeId The ID of the user receiving the payment.
     * @param float $amount The amount to be transferred.
     *
     * @throws DatabaseOperationException If there is an error with the database operation.
     * @throws AuthorizationFailedException If the authorization for the transaction fails.
     * @throws Exception If there is an error with the transaction.
     */
    public function executeTransaction(int $payerId, int $payeeId, float $amount): void {
        $payer = $this->userRepository->findById($payerId);
        $payee = $this->userRepository->findById($payeeId);

        if ($payer === null || $payee === null) {
            throw new Exception("One or both users not found.");
        }

        if (!$payer->canSendMoney()) {
            throw new Exception("Payer is not allowed to send money.");
        }

        if ($payerId === $payeeId ) {
            throw new Exception("Cannot send money to yourself.");
        }

        if ($payer->getBalance() < $amount) {
            throw new Exception("Insufficient funds.");
        }

        if (!$this->authService->authorize($payerId, $payeeId, $amount)) {
            throw new AuthorizationFailedException("Authorization failed for transaction.");

        }

        try {
            $this->transactionRepository->beginTransaction();

            $payer->setBalance($payer->getBalance() - $amount);
            $payee->setBalance($payee->getBalance() + $amount);

            $this->userRepository->updateBalance($payerId, $payer->getBalance());
            $this->userRepository->updateBalance($payeeId, $payee->getBalance());

            $transaction = new Transaction(null, $amount, $payerId, $payeeId, 'completed');
            $this->transactionRepository->save($transaction);

            $this->transactionRepository->commit();

            // Attempt to send notification
            try {
                $this->notificationService->notifyTransactionSuccess($payeeId, $amount);
            } catch (Exception $e) {
                // Log the error
            }

        } catch (Exception $e) {
            $this->transactionRepository->rollback();
            throw new DatabaseOperationException("An unexpected error occurred.", 0, $e);
        }
    }
}
