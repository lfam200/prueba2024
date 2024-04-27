<?php

namespace App\Infrastructure\ExternalService;

use Dotenv\Dotenv;
use Exception;
use PDO;
use PDOException;

/**
 * Class DatabaseConnector
 *
 * This class is responsible for connecting to the database.
 */
class DatabaseConnector {

    /**
     * @var PDO The PDO connection to the database.
     */
    private PDO $connection;

    /**
     * DatabaseConnector constructor.
     *
     * This method initializes the DatabaseConnector object. It loads environment variables from a .env file, and uses these to establish a PDO connection to the database. If the connection fails, it throws an exception.
     *
     * @throws Exception If there is an error connecting to the database.
     */
    public function __construct() {

        $host = $_ENV['DB_HOST'];
        $database = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Could not connect to database: " . $e->getMessage());
        }
    }

    /**
     * Get the PDO connection to the database.
     *
     * This method returns the PDO connection to the database.
     *
     * @return PDO The PDO connection to the database.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}