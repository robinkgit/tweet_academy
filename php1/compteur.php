<?php
function compteurtweet($conn, $idUser)
{
    $sql = "SELECT COUNT(*) AS 'tweet_count' FROM tweet WHERE id_user = :idUser";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['idUser' => $idUser]);
    $row = $stmt->fetch();
    return $row['tweet_count'];
}
$tweetCount = compteurtweet($conn, $idUser);


function compteurabonne($conn, $idUser)
{
    $sql = "SELECT COUNT(*) AS 'subscriber_count' FROM follow WHERE id_follow = :idUser";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['idUser' => $idUser]);
    $row = $stmt->fetch();
    return $row['subscriber_count'];
}
$compteurabonne = compteurabonne($conn, $idUser);

function compteurabonnement($conn, $idUser)
{
    $sql = "SELECT COUNT(*) AS 'abonnement' from follow where id_user = :idUser";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['idUser' => $idUser]);
    $row = $stmt->fetch();
    return $row['abonnement'];
}
$compteurabonnement = compteurabonnement($conn, $idUser);