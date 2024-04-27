<?php
namespace App\Infrastructure\Api;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Application\Service\UserService;

/**
 * Class UserController
 *
 * This class handles user related requests.
 */
class UserController {

    /**
     * @var UserService The service to handle users.
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService The service to handle users.
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Register a user.
     *
     * This method handles a request to register a user. It validates the request data and calls the user service to register the user.
     *
     * @param Request $request The HTTP request.
     * @return Response The HTTP response.
     */
    public function registerUser(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['fullName', 'documentId', 'email', 'password', 'accountType'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new Response(json_encode(['error' => "The field '$field' is required and cannot be empty."]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new Response(json_encode(['error' => "The e-mail format is invalid."]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        try {
            $this->userService->registerUser($data['fullName'], $data['documentId'], $data['email'], $data['password'], $data['accountType'], $data['balance'] ?? 0.0);
            return new Response(json_encode(['message' => 'Successfully registered user.']), Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        } catch (Exception $e) {
            return new Response(json_encode(['error' => "Error: " . $e->getMessage()]), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
    }
}
