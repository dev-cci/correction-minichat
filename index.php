<?php

    try
    {
        $bdd = new PDO('mysql:host=127.0.0.1;dbname=minichat','root','');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }

    $currentNickname = $_COOKIE["user_cookie"];

    if(!empty($_POST["message"]) && !empty($_POST["nickname"])) {
        $message = htmlspecialchars($_POST["message"]);
        $nickname = htmlspecialchars($_POST["nickname"]);

        setcookie('user_cookie', $nickname);
        $currentNickname = $nickname;

        // 0 : Je vérifie si le user existe déjà ou pas
        $userStatement = $bdd->prepare('SELECT * FROM users WHERE nickname = ?');
        $userStatement->execute([$nickname]);

        $user = $userStatement->fetch(PDO::FETCH_ASSOC);

        //var_dump($user);exit;

        if($user) {
            $userId = $user["id"];
        }
        else {
            // 1 : J'insère le nouveau user
            $insertUserStatement = $bdd->prepare('INSERT INTO users (nickname, created_at, ip_address) VALUES (?, ?, ?)');
            $insertUserStatement->execute([$nickname, date('Y-m-d H:i:s'), getIp()]);

            // 2 : Je récupère le dernier ID généré du user
            $userId = $bdd->lastInsertId();
        }

        // 3 : J'insère le message
        $insertMessageStatement = $bdd->prepare('INSERT INTO messages (user_id, message, ip_address, created_at) VALUES (?, ?, ?, ?)');
        $insertMessageStatement->execute([$userId, $message, getIp(), date('Y-m-d H:i:s')]);
    }


    $allMessagesStatement = $bdd->query('SELECT messages.*, users.nickname FROM messages INNER JOIN users WHERE users.id = messages.user_id');
    $allMessages = $allMessagesStatement->fetchAll(PDO::FETCH_ASSOC);

    $allUsersStatement = $bdd->query('SELECT * FROM users');
    $allUsers = $allUsersStatement->fetchAll(PDO::FETCH_ASSOC);


    //echo '<pre>';
    //print_r($allMessages);exit;

?><!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/minichat.css">

    <title>🐱 Mini-chat</title>
</head>
<body>

<nav class="navbar fixed-top navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php">🐱 Bienvenue dans le mini-chat !</a>
</nav>

<main>
    <div class="container mt-5">
        <section class="row mb-5 my-5">
            <div class="col-9" id="messages">
                <div class="col-12" id="messages-container">

                    <?php foreach($allMessages as $message) : ?>
                        <div class="card mb-0 message">
                            <div class="card-body">
                                <p class="my-0">
                                    <strong>
                                        <?=$message["nickname"]?>
                                    </strong>
                                    : 
                                    <span class="badge badge-secondary float-right created_at"><?=$message["message"]?></span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
            <div class="col-3">
                <?php foreach($allUsers as $user) : ?>
                    <div><?=$user["nickname"]?></div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<div id="talkBar" class="bg-primary">
    <form action="index.php" method="post">
        <div class="input-group">
            <input type="text"
                   id="pseudo"
                   class="form-control input-group-addon col-2"
                   name="nickname"
                   placeholder="Pseudo"
                   minlength="2"
                   required
                   value="<?=$currentNickname?>">
            <input type="text"
                   id="message"
                   class="form-control col-8"
                   name="message"
                   placeholder="Tape ton message ici"
                   minlength="1"
                   maxlength="255"
                   required>
            <button type="submit" class="btn btn-success col-2">Envoyer</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

<script>
    function refreshMessages() {
        $.get('/get_messages.php', function (messagesHtml) {
            document.querySelector('#messages-container').innerHTML = messagesHtml;
            setTimeout(refreshMessages, 3000);
            console.log("Messages rafraîchis")
        })
    }

    $(function () {
        refreshMessages();
    });
</script>
</body>
</html>