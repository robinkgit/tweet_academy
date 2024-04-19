<?php

include 'db.php';

if (isset($_POST['bio'], $_POST['userId'], $_POST['username'], $_POST['at_user_name'])) {
    $bio = $_POST['bio'];
    $username = $_POST['username'];
    $userId = $_POST['userId'];
    $at_USER_name = $_POST['at_user_name'];

    $sql = "UPDATE user SET username = :username, at_user_name = :at_user_name, bio = :bio WHERE id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username, 'at_user_name' => $at_USER_name, 'bio' => $bio, 'userId' => $userId]);

    if ($stmt->execute()) {
        header('Location: ../profile.php?update=success');
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
