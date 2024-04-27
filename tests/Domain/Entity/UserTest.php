<?php
namespace Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\User;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User(
            1,
            'John Doe',
            '123456789',
            'john.doe@example.com',
            'password',
            'common',
            100.0
        );
    }

    public function testGetId(): void
    {
        $this->assertEquals(1, $this->user->getId());
    }

    public function testGetFullName(): void
    {
        $this->assertEquals('John Doe', $this->user->getFullName());
    }

    public function testGetDocumentId(): void
    {
        $this->assertEquals('123456789', $this->user->getDocumentId());
    }

    public function testGetEmail(): void
    {
        $this->assertEquals('john.doe@example.com', $this->user->getEmail());
    }

    public function testGetPassword(): void
    {
        $this->assertEquals('password', $this->user->getPassword());
    }

    public function testGetAccountType(): void
    {
        $this->assertEquals('common', $this->user->getAccountType());
    }

    public function testGetBalance(): void
    {
        $this->assertEquals(100.0, $this->user->getBalance());
    }

    public function testSetBalance(): void
    {
        $this->user->setBalance(200.0);
        $this->assertEquals(200.0, $this->user->getBalance());
    }

    public function testCanSendMoney(): void
    {
        $this->assertTrue($this->user->canSendMoney());
    }
}