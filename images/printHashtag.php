<?php 
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "enzo", "root");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}

if(isset($_GET['hashtag'])){
    $hashtag = "#" . $_GET["hashtag"];
    var_dump($hashtag);
    $requete = $bdd->query("SELECT tweet.content, user.at_user_name FROM tweet INNER JOIN user ON tweet.id_user = user.id WHERE tweet.content LIKE '%$hashtag%' ORDER BY time DESC");
    $results = $requete->fetchAll();
    if($results){
        foreach ($results as $result) {
            echo $result[1] . " : " . $result[0] . '<br>';
        }
    }
}
if(isset($_GET['atUser']))echo $_GET['atUser'];
?>