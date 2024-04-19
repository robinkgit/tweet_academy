<?php
$userId = $_POST['userId']; 
$followId = $_POST['followId']; 
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
$sql = "INSERT INTO follow (id_user, id_follow) VALUES (:userId, :followId)";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->bindParam(':followId', $followId, PDO::PARAM_INT);
if ($stmt->execute()) {
    echo "Vous vous êtes abonné ";
} 
?>
