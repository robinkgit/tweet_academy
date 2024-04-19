<?php
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
if(isset($_POST["mailbis"])){
    $mailbis = $_POST["mailbis"];
    $passwordbis = hash("ripemd160", "vive le projet tweet_academy" . $_POST['passwordbis']);
    $requete = $bdd->prepare("SELECT * FROM user WHERE mail = :mailbis AND password = :passwordbis");
    $requete->execute(
        array(
            "mailbis" => "$mailbis",
            "passwordbis" => "$passwordbis",
        ));

    if($requete->rowCount() > 0){
        $result = $requete->fetch();
        $infoUser = array($result['username']);
        $token = bin2hex(random_bytes(15));
        $idUser = $result["id"];
        $requete = $bdd->query("UPDATE token SET token = '$token', now = NOW() WHERE id_user = $idUser");
        setcookie( "token", $token, time() + 3600 );
        echo json_encode($token);
        header("location: fil_actu.php");
    } else {
       echo "Le compte n'existe pas";
    }
}