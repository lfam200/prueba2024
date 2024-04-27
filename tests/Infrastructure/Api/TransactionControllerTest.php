<?php

namespace Infrastructure\Api;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Api\TransactionController;
use App\Application\Service\TransactionService;

class TransactionControllerTest extends TestCase
{
    private $transactionServiceMock;
    private $transactionController;

    protected function setUp(): void
    {
        $this->transactionServiceMock = $this->createMock(TransactionService::class);
        $this->transactionController = new TransactionController($this->transactionServiceMock);
    }

    public function testCreateTransaction_Success()
    {
        $jsonInput = json_encode(['payerId' => 1, 'payeeId' => 2, 'amount' => 100]);
        $request = Request::create('/create-transaction', 'POST', [], [], [], [], $jsonInput);

        $this->transactionServiceMock->expects($this->once())
            ->method('executeTransaction')
            ->with(1, 2, 100);

        $response = $this->transactionController->createTransaction($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['message' => 'Transaction created successfully.']), $response->getContent());
    }

    public function testCreateTransaction_Failure_ValidationErrors()
    {
        $jsonInput = json_encode(['payerId' => '', 'payeeId' => 2, 'amount' => 'not_a_number']);
        $request = Request::create('/create-transaction', 'POST', [], [], [], [], $jsonInput);

        $response = $this->transactionController->createTransaction($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('required and cannot be empty', $response->getContent());
    }

    public function testCreateTransaction_Failure_ServiceException()
    {
        $jsonInput = json_encode(['payerId' => 1, 'payeeId' => 2, 'amount' => 100]);
        $request = Request::create('/create-transaction', 'POST', [], [], [], [], $jsonInput);

        $this->transactionServiceMock->expects($this->once())
            ->method('executeTransaction')
            ->willThrowException(new Exception('Internal Error'));

        $response = $this->transactionController->createTransaction($request);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertStringContainsString('Error: Internal Error', $response->getContent());
    }
}
