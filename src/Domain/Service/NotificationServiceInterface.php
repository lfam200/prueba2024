<?php

namespace App\Domain\Service;

/**
 * Interface NotificationServiceInterface
 *
 * This interface defines the contract for a Notification service.
 */
interface NotificationServiceInterface
{
    /**
     * Notify a user of a successful transaction.
     *
     * This method should send a notification to a user indicating that a transaction was successful.
     *
     * @param int $userId The ID of the user to notify.
     * @param float $amount The amount of the transaction.
     * @return void
     */
    public function notifyTransactionSuccess(int $userId, float $amount): void;
}