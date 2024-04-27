<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Transaction;
use App\Domain\Repository\TransactionRepository;
use PDO;

/**
 * Class MysqlTransactionRepository
 *
 * This class implements the TransactionRepository interface and handles the persistence of transactions in a MySQL database.
 */
class MysqlTransactionRepository implements TransactionRepository {

    /**
     * @var PDO The PDO connection to the database.
     */
    private PDO $connection;

    /**
     * MysqlTransactionRepository constructor.
     *
     * @param PDO $connection The PDO connection to the database.
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Begin a database transaction.
     */
    public function beginTransaction(): void {
        $this->connection->beginTransaction();
    }

    /**
     * Commit the current database transaction.
     */
    public function commit(): void {
        $this->connection->commit();
    }

    /**
     * Rollback the current database transaction.
     */
    public function rollback(): void {
        $this->connection->rollback();
    }

    /**
     * Save a transaction to the database.
     *
     * @param Transaction $transaction The transaction to save.
     */
    public function save(Transaction $transaction): void {
        try {
            $stmt = $this->connection->prepare(
                'INSERT INTO transactions (amount, payerId, payeeId, status) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([
                $transaction->getAmount(),
                $transaction->getPayerId(),
                $transaction->getPayeeId(),
                $transaction->getStatus()
            ]);
        } finally {
            if (isset($stmt)) {
                $stmt = null;
            }
        }
    }

    /**
     * Find a transaction by its ID.
     *
     * @param int $id The ID of the transaction.
     * @return Transaction|null The found transaction, or null if not found.
     */
    public function findById(int $id): ?Transaction {
        $stmt = $this->connection->prepare('SELECT * FROM transactions WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->createTransactionFromData($data) : null;
    }

    /**
     * Create a Transaction object from database data.
     *
     * @param array $data The database data.
     * @return Transaction The created Transaction object.
     */
    private function createTransactionFromData(array $data): Transaction {
        return new Transaction(
            $data['id'],
            $data['amount'],
            $data['payerId'],
            $data['payeeId'],
            $data['status']
        );
    }
}
