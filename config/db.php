<?php

// Define database connection parameters in a single configuration array.
// This makes it easy to manage your credentials in one place.
$config = [
    'servername' => 'localhost', // Or your server's IP address
    'username' => 'jotahcom_test', // Your database username
    'password' => 'Ikeotuonye@00', // Your database password
    'dbname' => 'jotahcom_test' // The name of your database
];

/**
 * Connects to the database using the PDO (PHP Data Objects) method.
 * This is the recommended approach for modern PHP applications due to its
 * security features (prepared statements) and database abstraction layer.
 *
 * @param array $config An array containing database connection details.
 * @return PDO The PDO connection object.
 */
function connectWithPDO($config) {
    try {
        $conn = new PDO(
            "mysql:host={$config['servername']};dbname={$config['dbname']};charset=utf8mb4",
            $config['username'],
            $config['password']
        );
        
        // Set the PDO error mode to exception for robust error handling.
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "Successfully connected with PDO.<br>";
        return $conn;
    } catch(PDOException $e) {
        // In a production environment, you should log this error instead of
        // displaying it to the user.
        die("PDO Connection failed: " . $e->getMessage());
    }
}

/**
 * Connects to the database using the MySQLi (MySQL Improved) method.
 * This is a procedural method and is still widely used, but requires
 * more careful handling of prepared statements to prevent SQL injection.
 *
 * @param array $config An array containing database connection details.
 * @return mysqli The MySQLi connection object.
 */
function connectWithMySQLi($config) {
    // Create a new MySQLi instance.
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );

    // Check for connection errors.
    if ($conn->connect_error) {
        die("MySQLi Connection failed: " . $conn->connect_error);
    }

    echo "Successfully connected with MySQLi.<br>";
    return $conn;
}

// --- Example Usage ---

// To use PDO, uncomment the line below.
$db_connection = connectWithPDO($config);

// To use MySQLi, uncomment the line below.
// $db_connection = connectWithMySQLi($config);
