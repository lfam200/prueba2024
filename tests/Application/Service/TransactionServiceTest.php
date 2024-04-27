<?php

namespace Application\Service;

use PHPUnit\Framework\TestCase;
use App\Application\Service\TransactionService;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\TransactionRepository;
use App\Infrastructure\ExternalService\AuthorizationService;
use App\Infrastructure\ExternalService\NotificationService;
use App\Domain\Entity\User;
use App\Exception\AuthorizationFailedException;
use App\Exception\DatabaseOperationException;

class TransactionServiceTest extends TestCase
{
    private $transactionService;
    private $userRepositoryMock;
    private $transactionRepositoryMock;
    private $authServiceMock;
    private $notificationServiceMock;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->transactionRepositoryMock = $this->createMock(TransactionRepository::class);
        $this->authServiceMock = $this->createMock(AuthorizationService::class);
        $this->notificationServiceMock = $this->createMock(NotificationService::class);

        $this->transactionService = new TransactionService(
            $this->userRepositoryMock,
            $this->transactionRepositoryMock,
            $this->authServiceMock,
            $this->notificationServiceMock
        );
    }

    public function testExecuteTransactionSuccessful()
    {
        $payer = new User(1, 'John Doe', '1234', 'john@example.com', 'hash', 'common', 500.00);
        $payee = new User(2, 'Jane Doe', '5678', 'jane@example.com', 'hash', 'common', 300.00);

        $this->userRepositoryMock->method('findById')
            ->willReturnMap([
                [1, $payer],
                [2, $payee]
            ]);

        $this->authServiceMock->expects($this->once())
            ->method('authorize')
            ->willReturn(true);

        $this->transactionRepositoryMock->expects($this->once())
            ->method('beginTransaction');

        $this->transactionRepositoryMock->expects($this->once())
            ->method('commit');

        $this->notificationServiceMock->expects($this->once())
            ->method('notifyTransactionSuccess');

        $this->transactionService->executeTransaction(1, 2, 100.00);

        $this->assertEquals(400.00, $payer->getBalance());
        $this->assertEquals(400.00, $payee->getBalance());
    }

    public function testExecuteTransactionFailsWhenAuthorizationFails()
    {
        $payer = new User(1, 'John Doe', '1234', 'john@example.com', 'hash', 'common', 500.00);
        $payee = new User(2, 'Jane Doe', '5678', 'jane@example.com', 'hash', 'common', 300.00);

        $this->userRepositoryMock->method('findById')
            ->willReturnMap([
                [1, $payer],
                [2, $payee]
            ]);

        $this->authServiceMock->expects($this->once())
            ->method('authorize')
            ->willReturn(false);

        $this->expectException(AuthorizationFailedException::class);

        $this->transactionService->executeTransaction(1, 2, 100.00);
    }

    // Additional tests can be written to cover other failure cases like insufficient funds, same user transfer, etc.
}
