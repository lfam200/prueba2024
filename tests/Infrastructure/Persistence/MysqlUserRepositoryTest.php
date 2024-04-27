<?php

namespace Infrastructure\Persistence;

use App\Domain\Entity\User;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Persistence\MysqlUserRepository;
use PDO;

class MysqlUserRepositoryTest extends TestCase
{
    private $pdo;
    private $userRepository;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->userRepository = new MysqlUserRepository($this->pdo);
    }

    public function testSaveInsertsNewUser() {
        $user = new User(null, 'John Doe', '123456789', 'john@example.com', 'password_hashed', 'common', 100.0);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([
                $user->getFullName(),
                $user->getDocumentId(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getAccountType(),
                $user->getBalance()
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->userRepository->save($user);
    }

    public function testFindByEmailUserFound() {
        $fakeUser = ['id' => 1, 'fullName' => 'John Doe', 'documentId' => '123456789', 'email' => 'john@example.com', 'password' => 'hashed_password', 'accountType' => 'common', 'balance' => 100.0];
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([$fakeUser['email']]);
        $stmt->expects($this->once())->method('fetch')->willReturn($fakeUser);
        $this->pdo->expects($this->once())->method('prepare')->willReturn($stmt);
        $user = $this->userRepository->findByEmail($fakeUser['email']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($fakeUser['email'], $user->getEmail());
    }

    public function testFindByDocumentIdUserFound() {
        $fakeUser = ['id' => 1, 'fullName' => 'John Doe', 'documentId' => '123456789', 'email' => 'john@example.com', 'password' => 'hashed_password', 'accountType' => 'common', 'balance' => 100.0];
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([$fakeUser['documentId']]);
        $stmt->expects($this->once())->method('fetch')->willReturn($fakeUser);
        $this->pdo->expects($this->once())->method('prepare')->willReturn($stmt);
        $user = $this->userRepository->findByDocumentId($fakeUser['documentId']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($fakeUser['documentId'], $user->getDocumentId());
    }

    public function testUpdateBalanceUserBalanceUpdated() {
        $userId = 1;
        $newBalance = 150.0;
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([$newBalance, $userId]);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE users SET balance = ? WHERE id = ?')
            ->willReturn($stmt);
        $this->userRepository->updateBalance($userId, $newBalance);
    }
}
