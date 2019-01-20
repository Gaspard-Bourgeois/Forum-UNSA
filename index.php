<?php
session_start();


include('stock.php');
//connection base de donn�e
?>

<?php
//debut de traitement

if(isset($_POST['entrer']))
{
$login = htmlspecialchars($_POST['login']);

$mdp = htmlspecialchars($_POST['mdp']);



if(empty($login) OR empty($mdp))
{
$notification = 'Tous les champs doivent �tre remplis.';
}
elseif(!preg_match("#^[a-zA-Z0-9]{4,20}$#", $login))
{
$notification = 'Votre login doit contenir entre 5 et 20 caract�res';
}
elseif(!preg_match("#^[a-zA-Z0-9]{4,20}$#", $mdp))
{
$notification = 'Votre mot de passe doit contenir entre 5 et 20 caract�res.';
}
else
{

$passwd = base_convert($mdp, 16, 2);
//cryptage du code donn�e lors du formulaire



include('stock.php');
//connection base de donn�e


$connexion = $basedonnees -> query('SELECT login, mdp, sexe, nom, prenom, mail, permission FROM inscrit');
//conexion base de donn�e inscrit




while($identifiant = $connexion -> fetch())
{


if($login == $identifiant['login'] AND $passwd == $identifiant['mdp'])

{



//debut de difinition des sessions
$_SESSION['login'] = $identifiant['login'];
$_SESSION['sexe'] = $identifiant['sexe'];
$_SESSION['nom'] = $identifiant['nom'];
$_SESSION['prenom'] = $identifiant['prenom'];
$_SESSION['mail'] = $identifiant['mail'];
$_SESSION['permission'] = $identifiant['permission'];


//fin de definition des sessions



}
//fin de verification si c'est la bonne personne

}
$connexion ->closeCursor();
//fin de boucle


}
//fin du traitement des sessions







}
//fin du traitement des donn�e envoyer par l'utilisateur


if(empty($_SESSION['permission']))
{
include('mdp.php');
//mot de passe securit�
}



//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

<body>

<?php

include('menu.php');
?>

<p>Bienvenue sur la page d'accueil du site</p>






































</body>
</html>

