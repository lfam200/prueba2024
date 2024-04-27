<?php
namespace App\Domain\Repository;

use App\Domain\Entity\User;

/**
 * Interface UserRepository
 *
 * This interface defines the contract for a User repository.
 */
interface UserRepository {

    /**
     * Save a user.
     *
     * This method should persist a User entity into the repository.
     *
     * @param User $user The user to be saved.
     * @return void
     */
    public function save(User $user): void;

    /**
     * Find a user by its ID.
     *
     * This method should return a User entity if found, or null otherwise.
     *
     * @param int $id The ID of the user to find.
     * @return User|null The found user, or null if not found.
     */
    public function findById(int $id): ?User;

    /**
     * Find a user by its document ID.
     *
     * This method should return a User entity if found, or null otherwise.
     *
     * @param string $documentId The document ID of the user to find.
     * @return User|null The found user, or null if not found.
     */
    public function findByDocumentId(string $documentId): ?User;

    /**
     * Find a user by its email.
     *
     * This method should return a User entity if found, or null otherwise.
     *
     * @param string $email The email of the user to find.
     * @return User|null The found user, or null if not found.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Update the balance of a user.
     *
     * This method should update the balance of a user with the given ID.
     *
     * @param int $userId The ID of the user to update.
     * @param float $newBalance The new balance of the user.
     * @return void
     */
    public function updateBalance(int $userId, float $newBalance): void;
}
