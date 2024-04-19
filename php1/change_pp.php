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

$token = $_COOKIE['token'];

if(array_key_exists('key',$_POST)){

$key = $_POST['key'];
fwrite(STDOUT, print_r($_POST, true));

$query = $conn -> prepare("SELECT id_user FROM token WHERE token LIKE '$token'");
$query -> execute();

$arr = $query -> fetchAll(PDO::FETCH_ASSOC);


$value = "";

foreach($arr as $rows){
    $value = $rows['id_user'];
}

$query = $conn -> prepare("UPDATE user SET profile_picture = '".$key."' WHERE id LIKE ".$value);
$query -> execute();
}
?>