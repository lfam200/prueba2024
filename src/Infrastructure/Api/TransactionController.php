<?php

namespace App\Infrastructure\Api;

use App\Application\Service\TransactionService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransactionController
 *
 * This class handles transaction related requests.
 */
class TransactionController {
    /**
     * @var TransactionService The service to handle transactions.
     */
    private TransactionService $transactionService;

    /**
     * TransactionController constructor.
     *
     * @param TransactionService $transactionService The service to handle transactions.
     */
    public function __construct(TransactionService $transactionService) {
        $this->transactionService = $transactionService;
    }

    /**
     * Create a transaction.
     *
     * This method handles a request to create a transaction. It validates the request data and calls the transaction service to execute the transaction.
     *
     * @param Request $request The HTTP request.
     * @return Response The HTTP response.
     */
    public function createTransaction(Request $request): Response{
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['payerId', 'payeeId', 'amount'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new Response(json_encode(['error' => "The field '$field' is required and cannot be empty"]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
            }
        }

        if (!is_numeric($data['amount']) || !is_numeric($data['payerId']) || !is_numeric($data['payeeId'])) {
            return new Response(json_encode(['error' => "The fields 'amount', 'payerId', y 'payeeId' must be numeric."]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        try {
            $this->transactionService->executeTransaction($data['payerId'], $data['payeeId'], $data['amount']);
            return new Response(json_encode(['message' => 'Transaction created successfully.']), Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        } catch (Exception $e) {
            return new Response(json_encode(['error' => "Error: " . $e->getMessage()]), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }
}
