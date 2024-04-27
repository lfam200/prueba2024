<?php
namespace Infrastructure\Api;


use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Api\UserController;
use App\Application\Service\UserService;

class UserControllerTest extends TestCase
{
    private $userServiceMock;
    private $userController;

    protected function setUp(): void
    {
        $this->userServiceMock = $this->createMock(UserService::class);
        $this->userController = new UserController($this->userServiceMock);
    }

    public function testRegisterUserSuccessful()
    {
        $requestData = json_encode([
            'fullName' => 'John Doe',
            'documentId' => '123456789',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'accountType' => 'common',
            'balance' => 100.0
        ]);

        $request = new Request([], [], [], [], [], [], $requestData);

        $this->userServiceMock->expects($this->once())
            ->method('registerUser')
            ->with('John Doe', '123456789', 'john.doe@example.com', 'password123', 'common', 100.0);

        $response = $this->userController->registerUser($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['message' => 'Successfully registered user.']), $response->getContent());
    }

    public function testRegisterUserFailsValidation_MissingFields()
    {
        $requestData = json_encode([
            'fullName' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ]); // Missing 'documentId' and 'accountType'

        $request = new Request([], [], [], [], [], [], $requestData);

        $response = $this->userController->registerUser($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('The field \'documentId\' is required', $response->getContent());
    }

    public function testRegisterUserInvalidEmail()
    {
        $requestData = json_encode([
            'fullName' => 'John Doe',
            'documentId' => '123456789',
            'email' => 'invalid-email',
            'password' => 'password123',
            'accountType' => 'common'
        ]);

        $request = new Request([], [], [], [], [], [], $requestData);

        $response = $this->userController->registerUser($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('The e-mail format is invalid', $response->getContent());
    }
}
