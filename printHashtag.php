<?php 
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}

if(isset($_POST["saveHashtag"])){
    $hashtagToSave = $_POST["saveHashtag"];
    $requete = $bdd->query("SELECT hashtag FROM hashtag_list WHERE hashtag = '$hashtagToSave'");
    $temp = $requete->fetchAll();
    if(empty($temp)){
        $requete = $bdd->query("INSERT INTO hashtag_list (hashtag) VALUES ('$hashtagToSave')");
    } 
}

if(isset($_GET['hashtag'])){
    $hashtag = "#" . $_GET['hashtag'];
    $requete = $bdd->query("SELECT tweet.content, user.at_user_name FROM tweet INNER JOIN user ON tweet.id_user = user.id WHERE tweet.content LIKE '%$hashtag%' ORDER BY time DESC");
    $results = $requete->fetchAll();
    if($results){
        foreach ($results as $result) {
            echo $result[1] . " : " . $result[0] . '<br>';
        }
    }
}

if(isset($_POST['searchHashtag'])){
    $searchHashtag = "#" . $_POST['searchHashtag'];
    $requete = $bdd->prepare("SELECT hashtag FROM hashtag_list WHERE hashtag LIKE ?");
    $requete->bindValue(1, $searchHashtag."%", PDO::PARAM_STR);
    $requete->execute();
    $results = $requete->fetchAll(PDO::FETCH_ASSOC);
    if($results){
        echo json_encode($results);
    } else {
        echo json_encode("error");
    }
}
// if(isset($_GET['atUser']))echo $_GET['atUser'];
?>