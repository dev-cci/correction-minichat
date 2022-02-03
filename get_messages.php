<?php

require_once('database.php');

$allMessagesStatement = $bdd->query(
    'SELECT messages.*, users.nickname, users.color
    FROM messages
    INNER JOIN users
    WHERE users.id = messages.user_id
    ORDER BY messages.created_at');
$allMessages = $allMessagesStatement->fetchAll(PDO::FETCH_ASSOC);

foreach($allMessages as $message) : ?>
    <div class="card mb-0 message">
        <div class="card-body">
            <p class="my-0">
                <?=date('H:i:s', strtotime($message["created_at"]))?> &emsp;
                <strong style="color:<?= $message['color'] ?? '#444' ?>;">
                    <?=$message["nickname"]?>
                </strong>
                <span class="badge badge-secondary float-right created_at"><?=$message["message"]?></span>
            </p>
        </div>
    </div>
<?php endforeach; ?>