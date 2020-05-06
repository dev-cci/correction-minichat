<?php

require_once('database.php');

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

include('get_messages.php');