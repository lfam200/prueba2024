<?php
namespace Application\Service;

use PHPUnit\Framework\TestCase;
use App\Application\Service\UserService;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Exception;

class UserServiceTest extends TestCase {
    private $userRepositoryMock;
    private $userService;

    protected function setUp(): void {
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepositoryMock);
    }

    public function testRegisterUserFailsWhenEmailExists() {
        $existingUser = new User(1, 'John Doe', '123456789', 'john@example.com', 'hashedpassword', 'common', 100.0);

        $this->userRepositoryMock->method('findByEmail')->willReturn($existingUser);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E-mail is already registered.');

        $this->userService->registerUser('Jane Doe', '987654321', 'john@example.com', 'password123', 'common');
    }

    public function testRegisterUserFailsWhenDocumentIdExists() {
        $existingUser = new User(2, 'Jane Doe', '987654321', 'jane@example.com', 'hashedpassword', 'common', 150.0);

        $this->userRepositoryMock->method('findByDocumentId')->willReturn($existingUser);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Document ID is already registered.');

        $this->userService->registerUser('Jane Doe', '987654321', 'jane@example.com', 'password123', 'common');
    }
}