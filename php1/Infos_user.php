<?php
$requete = $conn->query("SELECT user.id FROM user INNER JOIN token ON user.id = token.id_user WHERE token.token = '$token'");
$result = $requete->fetch();
$idUser = $result["id"];
function getUserInfo($conn, $idUser)
{
    $sql = "SELECT username, at_user_name, profile_picture, bio FROM user WHERE id = :idUser";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['idUser' => $idUser]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
$userInfo = getUserInfo($conn, $idUser);
$token = $_COOKIE['token'];
if ($token) {
    $queryAbonnements = "SELECT user.username 
               FROM follow 
               INNER JOIN user ON follow.id_user = user.id 
               WHERE follow.id_follow = :idUser";
    $stmtAbonnements = $conn->prepare($queryAbonnements);
    $stmtAbonnements->bindParam(':idUser', $idUser);
    $stmtAbonnements->execute();
    $abonnements = $stmtAbonnements->fetchAll(PDO::FETCH_ASSOC);
    $queryAbonnes = "SELECT user.username 
               FROM follow 
               INNER JOIN user ON follow.id_follow = user.id 
               WHERE follow.id_user = :idUser";
    $stmtAbonnes = $conn->prepare($queryAbonnes);
    $stmtAbonnes->bindParam(':idUser', $idUser);
    $stmtAbonnes->execute();
    $abonnes = $stmtAbonnes->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("location: acceuil.html");
}
?>
