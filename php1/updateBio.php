<?php
include 'db.php'; 

if (isset($_POST['bio']) && isset($_POST['userId'])) {
    $bio = $_POST['bio'];
    $userId = $_POST['userId']; 

    $sql = "UPDATE user SET bio = :bio WHERE id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: ../profile.php?update=success');
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>
