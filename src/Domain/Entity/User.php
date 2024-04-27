<?php
namespace App\Domain\Entity;

/**
 * Class User
 *
 * This class represents a user of the system.
 */
class User
{
    private ?int $id;
    private string $fullName;
    private string $documentId;
    private string $email;
    private string $password;
    private string $accountType; // 'common' or 'merchant'
    private float $balance;

    /**
     * User constructor.
     *
     * @param int|null $id
     * @param string $fullName
     * @param string $documentId
     * @param string $email
     * @param string $password
     * @param string $accountType
     * @param float $balance
     */
    public function __construct(
        ?int $id,
        string $fullName,
        string $documentId,
        string $email,
        string $password,
        string $accountType,
        float $balance = 0.0
    ) {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->documentId = $documentId;
        $this->email = $email;
        $this->password = $password;
        $this->accountType = $accountType;
        $this->balance = $balance;
    }

    /**
     * Get the ID of the user.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * Get the document ID of the user.
     *
     * @return string
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    /**
     * Get the e-mail of the user.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the password of the user.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the account type of the user.
     *
     * @return string
     */
    public function getAccountType(): string
    {
        return $this->accountType;
    }

    /**
     * Get the balance of the user.
     *
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Set the balance of the user.
     *
     * @param float $balance
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * Check if the user can send money.
     *
     * This method checks if the user's account type is 'common'. Only users with 'common' account type can send money.
     *
     * @return bool Returns true if the user's account type is 'common', false otherwise.
     */
    public function canSendMoney(): bool {
        return $this->accountType === 'common';  // Only common users can send money
    }
}
