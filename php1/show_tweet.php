<?php
function showtweet($conn, $idUser)
{
    $sql = "SELECT content as tweet FROM tweet WHERE id_user = :idUser ORDER BY time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['idUser' => $idUser]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$tweets = showtweet($conn, $idUser);
