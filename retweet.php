<?php
$token = $_COOKIE['token'];
define('STDOUT', fopen('php://stdout', 'w'));


//error_reporting(E_ALL);

if (isset($_POST['tweetid'])) {
    $tweetid = htmlspecialchars($_POST['tweetid']);

    fwrite(STDOUT, print_r($_POST, true));


    $conn = new PDO('mysql:host=localhost;dbname=twitter;charset=utf8', 'robin', 'robin-mysql');
    $requete = $conn -> query("SELECT user.id FROM user INNER JOIN token ON user.id = token.id_user WHERE token.token LIKE '$token'");
    $result = $requete->fetch();
    $idUser = $result["id"];
 
        $stmt = $conn->prepare("INSERT INTO retweet (id_user, id_tweet) VALUES (:idUser, :tweetid)");

        $stmt->execute(array(':idUser' => $idUser, ':tweetid' => $tweetid));
    


    

    //echo json_encode($query2->fetchAll(PDO::FETCH_ASSOC));


    }

?>