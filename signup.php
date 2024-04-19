<?php
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}

if (isset($_POST['names'])) {
    $mail = $_POST['mail'];
    $requete = $bdd->prepare("SELECT * FROM user WHERE mail=?");
    $requete->execute(["$mail"]); 
    $mails = $requete->fetch();
    if ($mails) {
    echo json_encode("error email");
    } else {
        $birthdate = $_POST['birthdate'];
        $aujourdhui = date("Y-m-d");
        $diff = date_diff(date_create($birthdate), date_create($aujourdhui));
        $age = $diff->format('%y');
        if($age > 18){
            $pseudo = $_POST['pseudo'];
            $requete = $bdd->prepare("SELECT * FROM user WHERE at_user_name=?");
            $requete->execute(["$pseudo"]);
            $pseudos = $requete->fetch();
            if($pseudos){
                echo json_encode("error pseudo");
            } else {
                $names = $_POST['names'];
                $city = $_POST['city'];
                $password = hash("ripemd160", "vive le projet tweet_academy" . $_POST['password']);
                $request = $bdd->prepare("INSERT INTO user (username, at_user_name, profile_picture, bio, banner, mail, password, birthdate, private, city, campus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $request->execute([$names, $pseudo, "image_00", null, "banniere-image.jpg", $mail, $password, $birthdate, null, $city, null]);
                $request=$bdd->query("SELECT id from user WHERE mail = '$mail'");
                $temp = $request->fetch();
                $idUser = $temp['id'];
                $request=$bdd->query("INSERT INTO token (id_user, now) VALUES ($idUser, NOW())");
                echo json_encode("no errors");
            }
        } else {
            echo json_encode("error birthdate");
        }
    }
}
?>