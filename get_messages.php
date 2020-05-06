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

$allMessagesStatement = $bdd->query('SELECT messages.*, users.nickname FROM messages INNER JOIN users WHERE users.id = messages.user_id');
$allMessages = $allMessagesStatement->fetchAll(PDO::FETCH_ASSOC);

foreach($allMessages as $message) : ?>
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