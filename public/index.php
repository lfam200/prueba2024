<?php
//Include the Composer autoload file
require_once __DIR__ . '/../vendor/autoload.php';

//Import the necessary classes
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

use App\Application\Service\TransactionService;
use App\Application\Service\UserService;

use App\Infrastructure\Api\TransactionController;
use App\Infrastructure\Api\UserController;

use App\Infrastructure\ExternalService\AuthorizationService;
use App\Infrastructure\ExternalService\DatabaseConnector;
use App\Infrastructure\ExternalService\NotificationService;

use App\Infrastructure\Persistence\MysqlTransactionRepository;
use App\Infrastructure\Persistence\MysqlUserRepository;

use App\Infrastructure\Router;


// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create a database connection
$dbConnector = new DatabaseConnector();

// Get a PDO connection
$pdo = $dbConnector->getConnection();

// Create instances of the authorization and notification services
$authService = new AuthorizationService();
$notificationService = new NotificationService();

// Create a user service and controller
$userRepository = new MysqlUserRepository($pdo);
$userService = new UserService($userRepository);
$userController = new UserController($userService);

// Create a transaction service and controller
$transactionRepository = new MysqlTransactionRepository($pdo);
$transactionService = new TransactionService($userRepository, $transactionRepository,$authService, $notificationService);
$transactionController = new TransactionController($transactionService);

// Create a router and request object
$router = new Router();
$request = Request::createFromGlobals();

// Define routes
$router->get('/', function() {
    echo "Success! The API is working!";
});

$router->post('/register', function() use ($userController, $request) {
    // This route is used to register a new user
    $response = $userController->registerUser($request);
    $response->send();
});

$router->post('/transaction', function() use ($transactionController,$request) {
    // This route is used to create a new transaction
    $response = $transactionController->createTransaction($request);
    $response->send();
});

// Run the router
$router->run();
