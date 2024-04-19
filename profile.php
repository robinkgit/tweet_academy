<?php 
$token = $_COOKIE['token'];
if($token){

include('./php1/db.php');
include('./php1/Infos_user.php'); 
include('./php1/compteur.php');
include('./php1/show_tweet.php');
include('./php1/show_like.php');
include('./php1/updateBio.php');
include('./php1/change_pp.php');
include('./php1/updateProfile.php');

?>
<?php 
//var_dump($idUser);?>
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
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
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
                <img class="w-8" src="images/messages.png" alt="Logo messagerie">

                <button class="text-align mx-6 data-target" id="message"><a href="messagerie.php">Messages</a></button> 
            </div>

            <!-- <button class="button-settings" onclick="buttonreglage()">
                <i class="fas fa-cog"></i> Réglages
            </button> -->

            <!-- <button class="notification-button" onclick="notif()">
                <i class="fas fa-bell"></i>
            </button> -->

            <!-- <div id="notificationsModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="notif()">&times;</span>
                    <h2>Notifications</h2>
                    <p>0 Notifications</p>
                </div>
            </div> -->



            <!-- <div id="settingsModal" class="modal">
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
        </div>
        <div class="col-span-3">
            <div>
                <div class="flex flex-col justify-center">
                    <div class="mb-4">

                    <form id="post_tweet" name="upload_pp" method="post">
                        <label for="file-input" id="label_pp">
                                <img id="img_profil" class="rounded-full h-auto w-32 mx-auto cursor-pointer" alt="Profil">
                                <input type="file" id="file-input" class="hidden" accept="image/png, image/gif, image/jpeg">
                        </label>
                    </form>

                    </div>
                    <div class="text-center mb-4">
                        <h1 class="text-xl font-bold"><?php echo htmlspecialchars($userInfo['username']); ?></h1>
                        <p class="text-gray-600"><?php echo htmlspecialchars($userInfo['at_user_name']); ?></p>
                    </div>

                    <?php

                    ?>
                     
                     <button onclick="document.getElementById('editProfileModal').style.display='block'">
                        <i class="fas fa-cog"></i> Éditer le profil
                    </button>


                    <div id="editProfileModal" class="modal" style="display:none;">
                        <div class="modal-content">
                            <span class="close-button" onclick="document.getElementById('editProfileModal').style.display='none'">&times;</span>
                            <form action="./php1/updateProfile.php" method="post">
                                <input type="hidden" name="userId" value="<?php echo $idUser?>">
                                Username: <input type="text" name="username" required><br>
                                @Username: <input type="text" name="at_user_name" required><br>
                                Bio: <textarea name="bio" required></textarea><br>
                                <input type="submit" value="Mettre à jour le profil">
                            </form>
                        </div>
                    </div>



                    <div class="text-center mb-4">
                    <p class="text-gray-600 mt-2"><?php echo nl2br(htmlspecialchars($userInfo['bio'])); ?></p>
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
                    echo "<p>" . htmlspecialchars($abonne['username']) . "</p>";
                }
                ?>
            </div>
        </div>
    </div>
                            <div id="modalAbonnes2" class="modal">
                                <div class="modal-content">
                                    <span class="close-button" onclick="fermerModalAbonnes2()">&times;</span>
                                    <h2>Abonnements</h2>
                                    <div id="listeAbonnes2">
                                        <?php
                                        foreach ($abonnements as $abonnement) {
                                            echo "<p>" . htmlspecialchars($abonnement['username']) . "</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>


                            <div onclick="ouvrirModalAbonnes()">
                                <h2 class="text-lg font-bold"><?php echo $compteurabonnement; ?></h2>
                                <p class="text-gray-600 mr-4">Abonnés</p>
                            </div>

                            <div onclick="ouvrirModalAbonnes2()">
                                <h2 class="text-lg font-bold"><?php echo $compteurabonne; ?></h2>
                                <p class="text-gray-600">Abonnements</p>
                            </div>
                        </div>
                    </div>
                <div class="tabs mt-4 flex justify-center lg:mr-12">
                    <button class="ongletbutton mr-4" onclick="onglets(event, 'Tweets')">Tweets</button>
                    <button class="ongletbutton" onclick="onglets(event, 'Likes')">Likes</button>
                </div><br>
                <div id="Tweets" class="ongletcontent">
                    <br>
                    <?php
                    foreach ($tweets as $tweet) {
                        echo "<p>" . htmlspecialchars($tweet['tweet']) . "</p>";
                    }
                    ?>
                </div>

                <div id="Likes" class="ongletcontent">
                    <br>
                            <?php
                            foreach ($likedTweets as $tweet) {
                                echo "<div><p>" . htmlspecialchars($tweet['content']) . "</p><small>Posté le: " . htmlspecialchars($tweet['time']) . "</small></div>";
                            }
                            ?>
                </div>

                        </div>
                

                <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
                <script src="./script_profil.js"></script>
                <script type="text/javascript">
                var key_localstorage = "<?php Print($userInfo['profile_picture']); ?>";
                var balise_img = document.getElementById("img_profil");
                balise_img.setAttribute("src", localStorage.getItem(key_localstorage));
                </script>
                <script src="./style_modal_profile.js"></script>

</body>


</html>

<script src="script_compteur.js"></script>

<?php
} 
else{
    header("location: acceuil.html");
}