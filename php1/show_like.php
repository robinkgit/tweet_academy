<?php 
function getLikedTweetsByUserId($userId) {
    global $conn; 
    $sql = "SELECT t.id, t.id_user, t.id_response, t.time, t.content, t.id_quoted_tweet 
            FROM tweet t
            JOIN likes l ON t.id = l.id_tweet
            WHERE l.id_user = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$likedTweets = getLikedTweetsByUserId($idUser); 

