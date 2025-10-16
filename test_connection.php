<?php

// test_connection.php

echo "Attempting to connect to the database...<br>";

// This will include your db.php file and try to connect.
// If it fails, the script will stop here because of the die() function in db.php.
require_once 'db.php';

// If the script reaches this line, it means the connection was successful.
echo "Connection successful! 🐘";

// It's good practice to close the connection when you're done.
$conn->close();

?>