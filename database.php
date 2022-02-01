<?php

try
{
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=correction_minichat','root','');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}