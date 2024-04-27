<?php
namespace App\Domain\Repository;

use App\Domain\Entity\Transaction;

/**
 * Interface TransactionRepository
 *
 * This interface represents the contract that a transaction repository must implement.
 */
interface TransactionRepository {

    /**
     * Save a transaction.
     *
     * This method should persist a Transaction entity into the repository.
     *
     * @param Transaction $transaction The transaction to be saved.
     * @return void
     */
    public function save(Transaction $transaction): void;

    /**
     * Find a transaction by its ID.
     *
     * This method should return a Transaction entity if found, or null otherwise.
     *
     * @param int $id The ID of the transaction to find.
     * @return Transaction|null The found transaction, or null if not found.
     */
    public function findById(int $id): ?Transaction;

    /**
     * Begin a transaction.
     *
     * This method should start a new transaction.
     *
     * @return void
     */
    public function beginTransaction(): void;


    /**
     * Commit a transaction.
     *
     * This method should commit the current transaction.
     *
     * @return void
     */
    public function commit(): void;

    /**
     * Rollback a transaction.
     *
     * This method should roll back the current transaction.
     *
     * @return void
     */
    public function rollback(): void;
}
