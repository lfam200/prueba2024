<?php

namespace App\Application\Service;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Exception;

/**
 * Class UserService
 *
 * This class provides services related to User entity.
 */
class UserService {
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user.
     *
     * @param string $fullName
     * @param string $documentId
     * @param string $email
     * @param string $password
     * @param string $accountType
     * @param float $balance
     * @return User
     * @throws Exception
     */
    public function registerUser(string $fullName, string $documentId, string $email, string $password, string $accountType, float $balance = 0.0): User {

        if ($this->userRepository->findByEmail($email) !== null) {
            throw new Exception("E-mail is already registered.");
        }

        if ($this->userRepository->findByDocumentId($documentId) !== null) {
            throw new Exception("Document ID is already registered.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User(null, $fullName, $documentId, $email, $hashedPassword, $accountType, $balance);
        $this->userRepository->save($user);
        return $user;
    }
}
