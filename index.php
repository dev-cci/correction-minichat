<!doctype html>
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

                    <?php include('get_messages.php'); ?>

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
    <form method="post" action="send_message.php" class="input-group">
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
        <button class="btn btn-success col-2" id="sendBtn">Envoyer</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

</body>
</html>