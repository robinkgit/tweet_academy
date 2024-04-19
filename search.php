<?php
    try {
        $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
    } catch (PDOException $e) {
        echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    }

    if(isset($_POST['hashtag'])){
        $hashtag = $_POST['hashtag'];
        $requete = $bdd->prepare("SELECT hashtag FROM hashtag_list WHERE hashtag LIKE ?");
        $requete->bindValue(1, $hashtag."%", PDO::PARAM_STR);
        $requete->execute();
        $results = $requete->fetchAll(PDO::FETCH_ASSOC);
        if($results){
            echo json_encode($results);
        } else {
            echo json_encode("error");
        }
    }
    if(isset($_POST['checkAt'])){
        $checkAt = $_POST['checkAt'];
        $requete = $bdd->prepare("SELECT at_user_name FROM user WHERE at_user_name LIKE ?");
        $requete->bindValue(1, $checkAt."%", PDO::PARAM_STR);
        $requete->execute();
        $results = $requete->fetchAll(PDO::FETCH_ASSOC);
        if($results){
            echo json_encode($results);
        } else {
            echo ("error");
        }
    }
?>