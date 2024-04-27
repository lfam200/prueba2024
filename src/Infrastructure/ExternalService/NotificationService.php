<?php

namespace App\Infrastructure\ExternalService;

use App\Domain\Service\NotificationServiceInterface;
use Exception;

/**
 * Class NotificationService
 *
 * This class implements the NotificationServiceInterface and handles the sending of transaction success notifications.
 */
class NotificationService implements NotificationServiceInterface {
    /**
     * Notify the success of a transaction.
     *
     * This method sends a notification of a successful transaction to a user. It attempts to send the notification up to 3 times, and throws an exception if all attempts fail.
     *
     * @param int $userId The ID of the user.
     * @param float $amount The amount of the transaction.
     * @throws Exception If the notification fails to send after 3 attempts.
     */
    public function notifyTransactionSuccess(int $userId, float $amount): void {
        $url = "https://run.mocky.io/v3/6839223e-cd6c-4615-817a-60e06d2b9c82";
        $maxAttempts = 3;
        $attempts = 0;
        $success = false;

        while (!$success && $attempts < $maxAttempts) {
            $attempts++;
            $success = $this->sendHttpRequest($url);
            if (!$success) {
                sleep(1);
            }
        }

        if (!$success) {
            throw new Exception("Failed to send notification after $maxAttempts attempts.");
        }
    }

    /**
     * Send an HTTP request.
     *
     * This method sends an HTTP request to a specified URL and returns a boolean indicating whether the request was successful.
     *
     * @param string $url The URL to send the request to.
     * @return bool Returns true if the request was successful, false otherwise.
     */
    private function sendHttpRequest(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        $data = json_decode($response, true);
        return isset($data['mensaje']) && $data['mensaje'] === true;
    }
}

