<?php

define("DB_HOST", "db"); // Docker
define("DB_NAME", "kxo205"); // Set this to your username
define("DB_USER", "root"); // Set this to your username
define("DB_PASS", ""); // Set this to your DB password

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
}
catch (mysqli_sql_exception $ex) {
    // Something went wrong...
    echo "<p>Error: Unable to connect to database.</p>";
    echo "<p>Debugging errno: " . $ex->getCode() . "</p>";
    echo "<p>Debugging error: " . $ex->getMessage() . "</p>";
    exit;
}
