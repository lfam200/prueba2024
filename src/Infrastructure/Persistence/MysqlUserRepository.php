<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use PDO;

/**
 * Class MysqlUserRepository
 *
 * This class implements the UserRepository interface and handles the persistence of users in a MySQL database.
 */
class MysqlUserRepository implements UserRepository {

    /**
     * @var PDO The PDO connection to the database.
     */
    private PDO $connection;

    /**
     * MysqlUserRepository constructor.
     *
     * @param PDO $connection The PDO connection to the database.
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Save a user to the database.
     *
     * This method updates an existing user if the user ID is not null, or inserts a new user if the user ID is null.
     *
     * @param User $user The user to save.
     */
    public function save(User $user): void {
        if ($user->getId() !== null) {
            $stmt = $this->connection->prepare('UPDATE users SET fullName = ?, documentId = ?, email = ?, password = ?, accountType = ?, balance = ? WHERE id = ?');
            $stmt->execute([
                $user->getFullName(),
                $user->getDocumentId(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getAccountType(),
                $user->getBalance(),
                $user->getId()
            ]);
        } else {
            $stmt = $this->connection->prepare('INSERT INTO users (fullName, documentId, email, password, accountType, balance) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $user->getFullName(),
                $user->getDocumentId(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getAccountType(),
                $user->getBalance()
            ]);
        }
    }

    /**
     * Find a user by its ID.
     *
     * @param int $id The ID of the user.
     * @return User|null The found user, or null if not found.
     */
    public function findById(int $id): ?User {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->createUserFromData($data) : null;
    }

    /**
     * Find a user by its email.
     *
     * @param string $email The email of the user.
     * @return User|null The found user, or null if not found.
     */
    public function findByEmail(string $email): ?User {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->createUserFromData($data) : null;
    }

    /**
     * Find a user by its document ID.
     *
     * @param string $documentId The document ID of the user.
     * @return User|null The found user, or null if not found.
     */
    public function findByDocumentId(string $documentId): ?User {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE documentId = ?');
        $stmt->execute([$documentId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->createUserFromData($data) : null;
    }

    /**
     * Update the balance of a user.
     *
     * @param int $userId The ID of the user.
     * @param float $newBalance The new balance of the user.
     */
    public function updateBalance(int $userId, float $newBalance): void {
        $stmt = $this->connection->prepare('UPDATE users SET balance = ? WHERE id = ?');
        $stmt->execute([$newBalance, $userId]);
    }

    /**
     * Create a User object from database data.
     *
     * @param array $data The database data.
     * @return User The created User object.
     */
    private function createUserFromData(array $data): User {
        return new User(
            $data['id'],
            $data['fullName'],
            $data['documentId'],
            $data['email'],
            $data['password'],
            $data['accountType'],
            $data['balance']
        );
    }
}
