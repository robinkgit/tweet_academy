<?php
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
define('STDOUT', fopen('php://stdout', 'w'));



$token = $_COOKIE['token'];

if(isset($_GET['print'])){
    $requete = $bdd->query("SELECT tweet.content,tweet.id,user.at_user_name,user.username,profile_picture,COUNT(likes.id_user) as likes_count FROM tweet INNER JOIN user ON tweet.id_user = user.id LEFT JOIN likes ON tweet.id = likes.id_tweet WHERE id_quoted_tweet IS NULL GROUP BY tweet.id ORDER BY tweet.time DESC");
    $tweets = $requete->fetchAll(PDO::FETCH_ASSOC);
    //$tweet_response = $query->fetchAll(PDO::FETCH_ASSOC);
   // $result = array_merge($tweets,$tweet_response);
    echo json_encode($tweets);
    // echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
}
if(isset($_POST['quoted_tweet'])){
    $id_tweet_quoted = $_POST['id_tweet_quoted'];
    $query = $bdd -> prepare("SELECT tweet.content,tweet.id,user.at_user_name,user.username,profile_picture,COUNT(likes.id_user) as likes_count,id_quoted_tweet FROM tweet INNER JOIN user ON tweet.id_user = user.id LEFT JOIN likes ON tweet.id = likes.id_tweet WHERE  id_quoted_tweet LIKE '$id_tweet_quoted' GROUP BY tweet.id ORDER BY tweet.time ASC");
    $query -> execute();
    $tweet_response = $query->fetchAll(PDO::FETCH_ASSOC);
   // $result = array_merge($tweets,$tweet_response);
    echo json_encode($tweet_response);
   // echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
}

if(array_key_exists('retweet',$_POST)){
    $requete = $bdd -> query("SELECT user.id FROM user INNER JOIN token ON user.id = token.id_user WHERE token.token LIKE '$token'");
    $result = $requete->fetch();
    $idUser = $result["id"];

    $query = $bdd -> prepare("SELECT id_tweet,id_user FROM retweet");
    $query -> execute();

    $result_id_tweet = $query -> fetchAll(PDO::FETCH_ASSOC);
    
    $arr_content_tweet = [];
    $arr_id_user_retweet =[];
    $result_merge;
    foreach($result_id_tweet as $rows){
        $id_tweet_retweet = $rows["id_tweet"];
        $id_user_retweet = $rows["id_user"];

        $query2 = $bdd -> prepare("SELECT tweet.content,tweet.id,user.at_user_name,user.username,profile_picture,COUNT(likes.id_user) as likes_count FROM tweet INNER JOIN user ON tweet.id_user = user.id LEFT JOIN likes ON tweet.id = likes.id_tweet WHERE tweet.id LIKE '$id_tweet_retweet' GROUP BY tweet.id ORDER BY tweet.time DESC");
        $query2 -> execute();

        $query3 = $bdd ->prepare("SELECT at_user_name FROM user WHERE id LIKE '$id_user_retweet'");
        $query3 -> execute();

        $arr_content_tweet[] = array_merge($query2->fetchAll(PDO::FETCH_ASSOC),$query3->fetchAll(PDO::FETCH_ASSOC));
       //$result_merge = array_merge();
    }

    
   // fwrite(STDOUT, print_r($arr_content_tweet, true));

   echo json_encode($arr_content_tweet);

}
if(array_key_exists("response_tweet_com",$_POST)){
        fwrite(STDOUT, print_r($_POST, true));

    $requete = $bdd -> query("SELECT user.id FROM user INNER JOIN token ON user.id = token.id_user WHERE token.token LIKE '$token'");
    $result = $requete->fetch();
    $idUser = $result["id"];
    $content = $_POST['response_tweet_com'];

    $id_tweet_ref = $_POST['id_tweet_ref'];

    $query_id_response = $bdd ->prepare("SELECT id_response FROM tweet WHERE id_quoted_tweet LIKE '$id_tweet_ref' ORDER BY id_response DESC LIMIT 1");
    $query_id_response -> execute();
    $result_brut = $query_id_response-> fetchAll(PDO::FETCH_ASSOC);

    fwrite(STDOUT, print_r($result_brut, true));
    if(count($result_brut) == 0){
        $query_insert_response = $bdd->prepare("INSERT INTO tweet(id_user,id_response,content,id_quoted_tweet) VALUES ($idUser,1,'$content',$id_tweet_ref)");
        $query_insert_response-> execute();
    }else{
        foreach ($result_brut as $rows){

            $id_response_value = intval($rows['id_response']);
            $id_response_value++;
            $query_insert_response = $bdd->prepare("INSERT INTO tweet(id_user,id_response,content,id_quoted_tweet) VALUES ('$idUser','$id_response_value','$content','$id_tweet_ref')");
            $query_insert_response -> execute();
        }
    }
        
}
?>