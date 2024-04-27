<?php
namespace Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Transaction;

class TransactionTest extends TestCase
{
    private Transaction $transaction;

    protected function setUp(): void
    {
        $this->transaction = new Transaction(
            1,
            100.0,
            1,
            2,
            'completed'
        );
    }

    public function testGetIdReturnsCorrectId(): void
    {
        $this->assertEquals(1, $this->transaction->getId());
    }

    public function testGetAmountReturnsCorrectAmount(): void
    {
        $this->assertEquals(100.0, $this->transaction->getAmount());
    }

    public function testGetPayerIdReturnsCorrectPayerId(): void
    {
        $this->assertEquals(1, $this->transaction->getPayerId());
    }

    public function testGetPayeeIdReturnsCorrectPayeeId(): void
    {
        $this->assertEquals(2, $this->transaction->getPayeeId());
    }

    public function testGetStatusReturnsCorrectStatus(): void
    {
        $this->assertEquals('completed', $this->transaction->getStatus());
    }

    public function testSetAmountChangesAmount(): void
    {
        $this->transaction->setAmount(200.0);
        $this->assertEquals(200.0, $this->transaction->getAmount());
    }

    public function testSetStatusChangesStatus(): void
    {
        $this->transaction->setStatus('failed');
        $this->assertEquals('failed', $this->transaction->getStatus());
    }
}