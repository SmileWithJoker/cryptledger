<?php
include "db.php";


// Now you can use the $db_connection object to perform your queries.
// Example for PDO:
if (isset($db_connection) && $db_connection instanceof PDO) {
    $stmt = $db_connection->prepare("SELECT 1");
    $stmt->execute();
    echo "PDO query executed successfully.";
}

// Example for MySQLi:
if (isset($db_connection) && $db_connection instanceof mysqli) {
    $result = $db_connection->query("SELECT 1");
    if ($result) {
        echo "MySQLi query executed successfully.";
    }
    $db_connection->close();
}

?>

?>