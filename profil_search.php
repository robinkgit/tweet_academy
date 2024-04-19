<?php
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
$token = $_COOKIE['token'];
if ($token) {
    $requete = $bdd -> query("SELECT user.id FROM user INNER JOIN token ON user.id = token.id_user WHERE token.token = '$token'");
    $result = $requete->fetch();
    $idUserConnected = $result["id"];
    if($_GET["at"]){
        $at = "@" . $_GET["at"];
        $requete = $bdd->query("SELECT * FROM user WHERE at_user_name = '$at'");
        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        $idUser = $result[0]["id"];
        function getUserInfo($bdd, $idUser){
            $sql = "SELECT username, at_user_name, profile_picture, bio FROM user WHERE id = :idUser";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['idUser' => 7]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function compteurtweet($bdd, $idUser){
            $sql = "SELECT COUNT(*) AS 'tweet_count' FROM tweet WHERE id_user = :idUser";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['idUser' => $idUser]);
            $row = $stmt->fetch();
            return $row['tweet_count'];
        }
        $tweetCount = compteurtweet($bdd, $idUser);

        function compteurabonne($bdd, $idUser){
            $sql = "SELECT COUNT(*) AS 'subscriber_count' FROM follow WHERE id_follow = :idUser";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['idUser' => $idUser]);
            $row = $stmt->fetch();
            return $row['subscriber_count'];
        }
        $compteurabonne = compteurabonne($bdd, $idUser);

        function compteurabonnement($bdd, $idUser){
            $sql = "SELECT COUNT(*) AS 'abonnement' from follow where id_user = :idUser";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['idUser' => $idUser]);
            $row = $stmt->fetch();
            return $row['abonnement'];
        }
        $compteurabonnement = compteurabonnement($bdd, $idUser);

        function showtweet($bdd, $idUser){
            $sql = "SELECT content as tweet FROM tweet WHERE id_user = :userId ORDER BY time DESC";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['userId' => $idUser]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $tweets = showtweet($bdd, $idUser);

        function showAbonnes($bdd, $idUser){
            $sql = "SELECT u.username, u.id as userId
            FROM user u
            JOIN follow f ON u.id = f.id_follow
            WHERE f.id_user = :userId
            ORDER BY f.time DESC";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['userId' => $idUser]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $abonnes = showAbonnes($bdd, $idUser);
    }
?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <link rel="stylesheet" href="src/output.css"> -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <title>Profile - tweet_academy</title>
    </head>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .button-settings {
            position: relative;
            top: 5vh;
            background-color: grey;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .button-settings:hover {
            background-color: #0d8bf2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .ongletcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
            margin-top: 20px;
        }

        .ongletbutton {
            background-color: #f1f1f1;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        .ongletbutton:hover {
            background-color: #ddd;
        }

        .ongletbutton.active {
            background-color: #ccc;
        }

        .notification-button {
            position: relative;
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 32px;
            color: #333;
            top: 10vh;
            margin-left: 3vh;
        }

        .notification-button:hover {
            color: #0d8bf2;
        }

        #follow-button {
            color: #3399FF;
            font-family: "Helvetica";
            font-size: 10pt;
            background-color: #ffffff;
            border: 1px solid;
            border-color: #3399FF;
            border-radius: 3px;
            width: 85px;
            height: 30px;
            position: absolute;
            cursor: hand;
        }
    </style>

    <body>
        <img src='./images/hamburger_logo.png' class="block lg:hidden" id="menu_profile">
        <img src='./images/close.png' class="hidden" id="close">
        <div class="flex justify-center align-center mt-2  lg:grid lg:grid-cols-3">
            <div class="menu hidden lg:block flex flex-col p-1">
                <img class="w-10" src="./images/logs.png" alt="Logo twitter noir">
                <br>
                <div class="flex items-center pb-6">
                    <img class="w-8" src="./images/home.png" alt="Logo twitter noir">

                    <a class="text-align mx-6" href="fil_actu.php">Home</a>
                </div>
                <div class="flex items-center">
                    <img class="w-8" src="./images/messages.png" alt="Logo twitter noir">

                    <button class="text-align mx-6 data-target" id="messagerie.php"><a href="#">Messages</a></button>
                </div>
                <!-- <button class="button-settings" onclick="buttonreglage()">
                    <i class="fas fa-cog"></i> Réglages
                </button>

                <button class="notification-button" onclick="notif()">
                    <i class="fas fa-bell"></i>
                </button>

                <div id="notificationsModal" class="modal">
                    <div class="modal-content">
                        <span class="close-button" onclick="notif()">&times;</span>
                        <h2>Notifications</h2>
                        <p>0 Notifications</p>
                    </div> -->
                <!-- </div>
                <div id="settingsModal" class="modal">
                    <div class="modal-content">
                        <span class="close-button" onclick="fermemodal()">&times;</span>
                        <h2>Personnaliser la page</h2>
                        <button style="border: 1px solid black;border-radius: 3vh;padding: 1vh;" onclick="changeBackgroundColor('#CCCCCC')">Gris</button>
                        <button style="border: 1px solid black;border-radius: 3vh;padding: 1vh;" onclick="changeBackgroundColor('#a4d4f4')">blue</button>
                        <h2>Changer la Font-Size</h2>
                        <button style="border: 1px solid black; border-radius: 3vh; padding: 1vh;" onclick="incremsize()">Augmenter la taille de la police</button>
                        <button style="border: 1px solid black; border-radius: 3vh; padding: 1vh;" onclick="decremsize()">Diminuer la taille de la police</button>
                    </div>
                </div> -->
                <br>
                <div class="flex items-center">
                <img class="w-8" src="images/profile.png" alt="Logo twitter noir">

                 <a class="text-align mx-6" href="profile.php">Profile</a>
    
</div>
            </div class="col-span-3">
            <div>
                <div class="flex flex-col justify-center">
                    <div class="w-full max-w-xl mx-auto pt-4">
                        <div class="mb-4 " id="div_img_profile">
                            <script>
                           var div_pp = document.getElementById('div_img_profile');
                           var img = document.createElement("img");
                           img.src = localStorage.getItem('<?php echo htmlspecialchars($result[0]['profile_picture']); ?>') 
                           img.setAttribute('class',"rounded-full h-32 w-32 mx-auto");
                           div_pp.append(img);
                            </script>
                            <!-- ici photo profil -->
                            <!-- <img class="rounded-full h-32 w-32 mx-auto" src="<script></script" alt="Profil"> -->
                        </div>
                        <div class="text-center mb-4">
                            <h1 class="text-xl font-bold"><?php echo htmlspecialchars($result[0]['username']); ?></h1>
                            <p class="text-gray-600"><?php echo htmlspecialchars($result[0]['at_user_name']); ?></p>
                            <p class="text-gray-600">Bio : <?php echo htmlspecialchars($result[0]['bio']); ?></p>

                        </div>

                        <div class="flex justify-around text-center border-t border-gray-300 pt-4 flex-wrap">
                            <div>
                                <h2 class="text-lg font-bold"><?php echo $tweetCount; ?></h2>
                                <p class="text-gray-600 mr-4">Tweets</p>
                            </div>

                            <div id="modalAbonnes" class="modal">
                                <div class="modal-content">
                                    <span class="close-button" onclick="fermerModalAbonnes()">&times;</span>
                                    <h2>Abonnés</h2>
                                    <div id="listeAbonnes">
                                        <?php
                                        foreach ($abonnes as $abonne) {
                                            echo "<p>" . htmlspecialchars($abonne['username']) . " (ID: " . htmlspecialchars($abonne['userId']) . ")</p>";
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div>
                                <h2 class="text-lg font-bold"><?php echo $compteurabonnement; ?></h2>
                                <p class="text-gray-600">Abonnés</p>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold"><?php echo $compteurabonne; ?></h2>
                                <p class="text-gray-600">Abonnements</p>
                            </div>
                        </div>

                </div>
                    <div class="flex justify-around text-center border-t border-gray-300 pt-4">
                    <button style="position: relative;left: 10vh;" id="follow-button" data-user="<?php echo $idUser ?>" data-userConnected ="<?php echo $idUserConnected ?>">+ Follow</button>
                    </div>
                </div>
            </div>
            <br>
            <!-- <div>
                <img style="position: relative;bottom: 4vh;width: 4.6%;left: 50vh;" src="./images/email_copie.png" style="width: 5%;height: 20%;position:relative;bottom: 60vh;" alt="">

            </div> -->


            <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
            <script src="script.js"></script>
            <script src="script_profil.js"></script>

    </body>

   
    </html>
  

    </html>
    <script src="./script_follow.js">

    </script>

<?php
} else {
    header("location: acceuil.html");
}