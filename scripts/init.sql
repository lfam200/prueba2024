-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS app_db;

-- Usar la base de datos creada
USE app_db;

-- Crear tabla de usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(255) NOT NULL,
    documentId VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    accountType ENUM('common', 'merchant') NOT NULL,
    balance DECIMAL(10, 2) NOT NULL DEFAULT 0.00
);

-- Crear tabla de transacciones
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payerId INT NOT NULL,
    payeeId INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (payerId) REFERENCES users(id),
    FOREIGN KEY (payeeId) REFERENCES users(id)
);

-- Insertar usuarios de prueba
INSERT INTO users (fullName, documentId, email, password, accountType, balance) VALUES
    ('John Doe', '123456789', 'john@example.com', SHA2('password123', 256), 'common', 1000.00),
    ('Jane Doe', '987654321', 'jane@example.com', SHA2('password123', 256), 'merchant', 500.00);

-- Insertar transacciones de prueba
INSERT INTO transactions (payerId, payeeId, amount, status) VALUES
    (1, 2, 150.00, 'completed');