<?php
$servername = "localhost";
$dbname = "twitter";
$username = "robin";
$password = "robin-mysql";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>