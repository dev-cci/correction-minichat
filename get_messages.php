<?php

require_once('database.php');

$allMessagesStatement = $bdd->query(
    'SELECT messages.*, users.nickname 
    FROM messages 
    INNER JOIN users 
    WHERE users.id = messages.user_id 
    ORDER BY messages.created_at');
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