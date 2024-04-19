<?php
session_start();
  $username = 'robin';
  $password = 'robin-mysql';

  try{
    $connexion = new PDO('mysql:host=localhost;dbname=twitter', $username,$password);
}catch(PDOException $e){
  echo "erreur: " . $e -> getMessage();
}
define('STDOUT', fopen('php://stdout', 'w'));





if(array_key_exists("message", $_POST)){
  $token = $_COOKIE['token'];
  fwrite(STDOUT, print_r($_POST, true));

  $query1 = $connexion -> prepare("SELECT id_user FROM token WHERE token LIKE '$token'");
  $query1 -> execute();

  $value_id_user;

foreach($query1->fetch(PDO::FETCH_ASSOC) as $rows){
    $value_id_user = $rows;
}

  $name_convo = $_POST['name_convo'];
  $query2 = $connexion -> prepare("SELECT id FROM convo INNER JOIN convo_users ON convo.id = convo_users.id_convo WHERE convo.name LIKE '$name_convo' AND convo_users.id_user LIKE '$value_id_user'");
  $query2->execute();
  $value_id_convo;
foreach($query2-> fetchAll(PDO::FETCH_ASSOC) as $rows2){
  $value_id_convo = $rows2['id'];
}
//
  $message = $_POST["message"];
  $query = $connexion -> prepare("INSERT INTO messages (id_convo,id_user,content) VALUES ('$value_id_convo','$value_id_user','$message')");
  $query -> execute();
  $query4 = $connexion -> prepare("SELECT at_user_name FROM user WHERE id LIKE '$value_id_user'");
  $query4 -> execute();
  echo json_encode($query4->fetchAll(PDO::FETCH_ASSOC));
}


if(array_key_exists("id_convo", $_POST)){
  $token = $_COOKIE['token'];
fwrite(STDOUT, print_r($_POST, true));

$query1 = $connexion -> prepare("SELECT id_user FROM token WHERE token LIKE '$token'");
$query1 -> execute();

$value_id_user;

foreach($query1->fetch(PDO::FETCH_ASSOC) as $rows){
    $value_id_user = $rows;
}

  $name_convo = $_POST['name_convo'];
  $query2 = $connexion -> prepare("SELECT id FROM convo INNER JOIN convo_users ON convo.id = convo_users.id_convo WHERE convo.name LIKE '$name_convo' AND convo_users.id_user LIKE '$value_id_user'");
  $query2->execute();
  $value_id_convo;
foreach($query2-> fetchAll(PDO::FETCH_ASSOC) as $rows2){
  $value_id_convo = $rows2['id'];
}
  //fwrite(STDOUT, print_r($value_id_convo, true));

$query3 = $connexion -> prepare("SELECT user.at_user_name ,content,time FROM user INNER JOIN messages on user.id = messages.id_user WHERE id_convo LIKE '$value_id_convo'");
$query3 -> execute();
echo json_encode($query3->fetchAll(PDO::FETCH_ASSOC));
}