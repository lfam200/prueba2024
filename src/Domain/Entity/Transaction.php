<?php
namespace App\Domain\Entity;

/**
 * Class Transaction
 *
 * This class represents a transaction between two users.

 */
class Transaction {
    private ?int $id;
    private float $amount;
    private int $payerId;
    private int $payeeId;
    private string $status;

    /**
     * Transaction constructor.
     *
     * @param int|null $id
     * @param float $amount
     * @param int $payerId
     * @param int $payeeId
     * @param string $status
     */
    public function __construct(?int $id, float $amount, int $payerId, int $payeeId, string $status) {
        $this->id = $id;
        $this->amount = $amount;
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;
        $this->status = $status;
    }

    /**
     * Get the ID of the transaction.
     *
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Get the amount of the transaction.
     *
     * @return float
     */
    public function getAmount(): float {
        return $this->amount;
    }

    /**
     * Get the ID of the user who is making the payment.
     *
     * @return int
     */
    public function getPayerId(): int {
        return $this->payerId;
    }

    /**
     * Get the ID of the user who is receiving the payment.
     *
     * @return int
     */
    public function getPayeeId(): int {
        return $this->payeeId;
    }

    /**
     * Get the status of the transaction.
     *
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * Set the amount of the transaction.
     *
     * @param float $amount
     */
    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }

    /**
     * Set the status of the transaction.
     *
     * @param string $status
     */
    public function setStatus(string $status): void {
        $this->status = $status;
    }
}
