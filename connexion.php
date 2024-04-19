<?php
$token = $_COOKIE['token'];
define('STDOUT', fopen('php://stdout', 'w'));
   fwrite(STDOUT, print_r($_POST, true));

if (isset($_POST['tweet'])) {
    $tweet = htmlspecialchars($_POST['tweet']);

    $bdd = new PDO('mysql:host=localhost;dbname=twitter;charset=utf8', 'robin', 'robin-mysql');
    $requete = $bdd -> query("SELECT user.id FROM user INNER JOIN token ON user.id = token.id_user WHERE token.token LIKE '$token'");
    $result = $requete->fetch();
    $idUser = $result["id"];

    $stmt = $bdd->prepare("INSERT INTO tweet (id_user, content) VALUES ($idUser, :content)");

    $stmt->execute(array(':content' => $tweet));


}
?>