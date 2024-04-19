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
define('STDOUT', fopen('php://stdout', 'w'));
//fwrite(STDOUT, print_r($_POST, true));

$token = $_COOKIE['token'];
$query1 = $conn -> prepare("SELECT id_user FROM token WHERE token LIKE '$token'");
$query1 -> execute();

$value_id_user;

foreach($query1->fetch(PDO::FETCH_ASSOC) as $rows){
    $value_id_user = $rows;
}

fwrite(STDOUT, print_r($value_id_user, true));


$query7 = $conn -> prepare("SELECT name FROM convo INNER JOIN convo_users ON convo.id = convo_users.id_convo WHERE convo_users.id_user = '$value_id_user'");
$query7 -> execute();

//fwrite(STDOUT, print_r($query->fetchAll(PDO::FETCH_NUM), true));


echo json_encode($query7->fetchAll(PDO::FETCH_NUM));