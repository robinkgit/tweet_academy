<?php 
try {
    $bdd = new PDO("mysql:host=localhost;dbname=twitter", "robin", "robin-mysql");
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}

$token = $_COOKIE['token'];
//var_dump($token);
if($token){

session_start(); 
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
    body{
        padding: 0;
        margin:0;
    }
    .buttonok:hover {
        background-color: rosybrown;
        color: white;
    }

    html {
        margin: 0;
        padding: 0;
    }

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

    #nouvelle_convo{
        position: relative;
        bottom: 1rem
    }
    .tab-list{
        display: flex;
        flex-direction: column;
    }

</style>

<body>
    <div class="grid grid-cols-1">
        <div class="menu hidden flex justify-between lg:block lg:flex lg:justify-between" id="navbar">
            <img class="w-10  lg:block" src="./images/logs.png" alt="Logo twitter noir">
            <br>
            <div class="flex items-center">
                <img class="w-8" src="./images/home.png" alt="Logo twitter noir">
                <a class="text-align mx-6" href="fil_actu.php">Home</a>
            </div>
            <br>
            <div class="flex items-center">
                <img class="w-8" src="./images/profile.png" alt="Logo twitter noir">
                <button class="text-align mx-6 data-target" id="message"><a href="profile.php">Profile</a></button>
            </div>
            <img src='./images/close.png' id="close_navbar" class="lg:hidden">
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
                </div> -->
            </div>
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
            </div>

            <div id="nouvelle-discussion" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="fermemodal_discussion()">&times;</span>
                    
                    <h1>Nouvelle conversation</h1>
                    <form method="post" id="nv_discussion" name="nv_discussion">
                    <input  id="name_chat" type="text" placeholder="Nom de la conversation" class="max-w-full"><br><br>
                    <input  id ="nom_personne" type="text" placeholder="Avez qui discuter ?"  class="max-w-full">
                    <button type="submit">Valider</button>
                    </form>
                </div>
            </div>
            <br>
        </div>
        <!-- <-- fin div menu --> 

        <div>
            <div class="flex">
                <div class="container max-h-screen max-w-full">
                    <div class="w-full lg:grid lg:grid-cols-3">
                        <div class="hidden lg:block bg-white overflow-auto" id="div_convo">
                            <div class="p-4 border-b">
                                <button id="nouvelle_convo" onclick ="nouvelledicussion()">Nouvelle conversation</button>
                                <img src="./images/close.png" class="float-right cursor-pointer hidden" id="croix_close">
                                <h2 class="text-lg font-semibold">Discussions</h2>
                                
                            </div>
                            <ul class="tab-list divide-y" id="divide-y">
                                <!-- <?php for ($i = 1; $i <= 10; $i++) : ?> -->
                                    <!-- <li class="discussion p-4 hover:bg-gray-50 cursor-pointer" data-discussion="<?php echo $i; ?>">Discussion <?php echo $i; ?></li> -->
                                <!-- <?php endfor; ?> -->
                            </ul>
                        </div>
                        <div class="flex-col col-span-2" id="div_message_content">
                            <div class="p-4">
                                <img src="./images/twitter_messagerie.png" class="lg:hidden cursors-pointer float-right" id="twitter_convo">
                                <img src="./images/hamburger_logo.png" class="lg:hidden cursor-pointer" id="menu_hamburger_convo">
                                <h2 class="text-lg font-semibold text-center" id="h2_nom_convo">Nom Conversation</h2>
                            </div>
                            <div id="tab_pane" class="tab-pane conversation">
                                
                            </div>
                            <div class="mt-auto">
                                
                                <form id="chatbox" method="post">
                                <input type="text" class="border p-2 md:w-3/4 w-3/4 lg:w-5/12 max-w-full bottom-0 fixed md:m-auto" placeholder="message" name="message">
                                <button type="submit" id="sendMessage" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded bottom-0 fixed left-3/4 flex-wrap flex ">
                                    Envoyer
                                </button>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- //<script src="script.js"></script> -->
        <!-- //<script src="script_feed.js"></script> -->
</body>

<script>
    // $(document).ready(function() {
    //     $('.discussion').click(function() {
    //         var idconv = $(this).data('discussion');
    //         $.ajax({
    //             url: 'ajaxmessagerie.php',
    //             type: 'GET',
    //             data: {
    //                 discussion: idconv
    //             },
    //             success: function(response) {
    //                 $('.conversation').html(response);
    //             }
    //         });
    //     });
    // });
</script>
<script src="script_parametre.js"></script>



</html>

<?php
} 
else{
    header("location: acceuil.html");
}