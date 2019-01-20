<?php
session_start();

if(isset($_GET['logout']))
{
session_destroy();
session_start();

}




include('stock.php');
//connection base de donnée
?>

<?php
//debut de traitement

if(isset($_POST['entrer']))
{
$login = htmlspecialchars($_POST['login']);

$mdp = htmlspecialchars($_POST['mdp']);

$login = strtolower($login);
$mdp = strtolower($mdp);



if(empty($login) OR empty($mdp))
{
$notification = 'Tous les champs doivent être remplis.';
}
elseif(!preg_match("#^[a-zA-Z0-9éèï]{4,20}$#", $login))
{
$notification = 'Votre login doit contenir entre 5 et 20 caractères';
}
elseif(!preg_match("#^[a-zA-Z0-9]{4,20}$#", $mdp))
{
$notification = 'Votre mot de passe doit contenir entre 5 et 20 caractères.';
}
else
{

$passwd = base_convert($mdp, 16, 2);
//cryptage du code donnée lors du formulaire



include('stock.php');
//connection base de donnée


$connexion = $basedonnees -> query('SELECT id, avatar, login, mdp, sexe, nom, prenom, mail, news, DATE_FORMAT(datenaissance, \'%d-%m-%y\') AS date, permission, chat FROM inscrit');
//conexion base de donnée inscrit




while($identifiant = $connexion -> fetch())
{


if($login == $identifiant['login'] AND $passwd == $identifiant['mdp'])

{



//debut de difinition des sessions


$_SESSION['proprietaire'] = $identifiant['id'];
$_SESSION['avatar'] = $identifiant['avatar'];
$_SESSION['login'] = $identifiant['login'];
$_SESSION['sexe'] = $identifiant['sexe'];
$_SESSION['nom'] = $identifiant['nom'];
$_SESSION['prenom'] = $identifiant['prenom'];
$_SESSION['mail'] = $identifiant['mail'];
$_SESSION['date'] = $identifiant['date'];
$_SESSION['news'] = $identifiant['news'];
$_SESSION['permission'] = $identifiant['permission'];
$_SESSION['chat'] = $identifiant['chat'];


//fin de definition des sessions



}
//fin de verification si c'est la bonne personne

}
$connexion ->closeCursor();
//fin de boucle


}
//fin du traitement des sessions







}
//fin du traitement des donnée envoyer par l'utilisateur


if(!isset($_SESSION['permission']))
{
include('mdp.php');
//mot de passe securité
}
else
{


//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

<body>

<?php

include('menu.php');
?>
<div id="contenu">
<p><center>Bienvenue sur la page d'accueil du site</center></p>


































</div>

<?php
include('chat.php');
?>

</body>
</html>
<?php

}
?>
