<?php
$token = $_COOKIE['token'];

error_reporting(E_ALL);

if (isset($_POST['tweetid'])) {
    $tweetid = htmlspecialchars($_POST['tweetid']);


    $conn = new PDO('mysql:host=localhost;dbname=twitter;charset=utf8', 'robin', 'robin-mysql');
    $requete = $conn -> query("SELECT user.id FROM user INNER JOIN token ON user.id = token.id_user WHERE token.token LIKE '$token'");
    $result = $requete->fetch();
    $idUser = $result["id"];

    $requete2 = $conn -> query("SELECT COUNT(id_user) AS nombre, id_tweet FROM likes WHERE id_user = $idUser AND id_tweet = $tweetid");
    $result2 = $requete2->fetch();
    $verif = $result2["nombre"];

    echo $verif;
    if ($verif == 0){
        $stmt = $conn->prepare("INSERT INTO likes (id_user, id_tweet) VALUES (:idUser, :tweetid)");

        $stmt->execute(array(':idUser' => $idUser, ':tweetid' => $tweetid));
    
    }
    if ($verif > 0){

        $stmt = $conn->prepare("DELETE FROM likes where id_user like :idUser and id_tweet like :tweetid");

        $stmt->execute(array(':idUser' => $idUser, ':tweetid' => $tweetid));
    }
  


}
?>