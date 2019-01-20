<?php
try
{
$basedonnees = new PDO('mysql:host=localhost;dbname=forum-unsa', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>
