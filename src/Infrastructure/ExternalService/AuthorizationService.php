<?php

namespace App\Infrastructure\ExternalService;

/**
 * Class AuthorizationService
 *
 * This class handles the authorization of transactions.
 */
class AuthorizationService
{
    /**
     * Authorize a transaction.
     *
     * This method sends a request to an external service to authorize a transaction. It throws an exception if there is an error with the request.
     *
     * @param int $payerId The ID of the payer.
     * @param int $payeeId The ID of the payee.
     * @param float $amount The amount of the transaction.
     * @return bool Returns true if the transaction is authorized, false otherwise.
     * @throws \Exception If there is an error with the request.
     */
    public function authorize(int $payerId, int $payeeId, float $amount): bool {
        $url = "https://run.mocky.io/v3/1f94933c-353c-4ad1-a6a5-a1a5ce2a7abe";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            throw new \Exception("Authorization request error: " . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        return isset($data['message']) && $data['message'] === "Autorizado";
    }
}