<?php

namespace Infrastructure\Persistence;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Persistence\MysqlTransactionRepository;
use App\Domain\Entity\Transaction;
use PDO;
use PDOStatement;

class MysqlTransactionRepositoryTest extends TestCase
{
    private $pdo;
    private $repository;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = new MysqlTransactionRepository($this->pdo);
    }

    public function testBeginTransaction()
    {
        $this->pdo->expects($this->once())->method('beginTransaction');
        $this->repository->beginTransaction();
    }

    public function testCommit()
    {
        $this->pdo->expects($this->once())->method('commit');
        $this->repository->commit();
    }

    public function testRollback()
    {
        $this->pdo->expects($this->once())->method('rollback');
        $this->repository->rollback();
    }

    public function testSaveTransaction()
    {
        $transaction = new Transaction(null, 100, 1, 2, 'pending');
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([100, 1, 2, 'pending']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO transactions (amount, payerId, payeeId, status) VALUES (?, ?, ?, ?)')
            ->willReturn($stmt);

        $this->repository->save($transaction);
    }
}
