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
fwrite(STDOUT, print_r($_POST, true));

$name_chat = $_POST["name_chat"];
$name_personne = $_POST["name_personne"];
$token = $_COOKIE['token'];

$query = $conn -> prepare("INSERT INTO convo(name,picture) VALUES('".$name_chat."','image_00')");
$query -> execute();

$query2 = $conn ->prepare("SELECT id FROM convo WHERE name LIKE '$name_chat'");
$query2 -> execute();
$value_id_convo;

foreach($query2-> fetch(PDO::FETCH_ASSOC) as $rows){
    $value_id_convo = $rows;
}; 

fwrite(STDOUT, print_r($rows, true));

$query3 = $conn -> prepare("SELECT id FROM user WHERE at_user_name LIKE '$name_personne'");
$query3 ->execute();
$value_id_user = [];
foreach($query3->fetchAll(PDO::FETCH_ASSOC) as $rows2){
    $value_id_user[] = $rows2['id'];
}

foreach($value_id_user as $rows3){
    $query4 = $conn -> prepare("INSERT INTO convo_users(id_convo,id_user) VALUES('$value_id_convo','$rows3')");
    $query4 -> execute();

    
}

$query5 = $conn -> prepare("SELECT id_user FROM token WHERE token LIKE '$token'");
$query5 -> execute();

foreach($query5 -> fetchAll(PDO::FETCH_ASSOC) as $rows4){
    $value_id_token = $rows4['id_user'];
    $query6 = $conn -> prepare("INSERT INTO convo_users(id_convo,id_user) VALUES('$value_id_convo','$value_id_token')");
    $query6 -> execute();
}
?>