<?php
include('stock.php');
//connection base de donnée
?>

<?php
//debut traitement
if(isset($_GET['code']) AND isset($_GET['valider']))
{
//debut de traitement

$valider = htmlspecialchars($_GET['valider']);
$pass = htmlspecialchars($_GET['code']);

if(!preg_match("#^oui$|^non$#", $valider))
{
$notification = 'Votre lien est mort.';
}

if(!isset($notification))
{

$validation = $basedonnees ->query('SELECT login, mdp, sexe, nom, prenom, mail, datenaissance, news, datecreation FROM confirmation WHERE confirmer = \'1\'');


while($alors = $validation->fetch())
{
$code = $alors['login'];
$code = hexdec($code);
$code = $code +2012;
$code = $code *2;
$code = $code -1996;
$code = $code *16;

if($code == $pass)
{
//Si bon mot de passe
//verification si demande de suppression ou sauvegarde

$reussi = 'ok';

if($valider == 'oui')
{

//si valider egal oui alors transfert des donnée vers la base de donnée


//haching
$passwd = base_convert($alors['mdp'], 16, 2);
$permission = 'utilisateur';





$adresse = '0';


$transfert = $basedonnees ->prepare('INSERT INTO inscrit(avatar, login, mdp, sexe, nom, prenom, mail, datenaissance, news, datecreation, permission) VALUES(:avatar, :login, :mpd, :sexe, :nom, :prenom, :mail, :datenaissance, :news, :datecreation, :permission)');
$transfert->execute(array(
							'avatar' => $adresse,
							'login' => $alors['login'],
							'mpd' => $passwd,
							'sexe' => $alors['sexe'],
							'nom' => $alors['nom'],
							'prenom' => $alors['prenom'],
							'mail' => $alors['mail'],
							'datenaissance' => $alors['datenaissance'],
							'news' => $alors['news'],
							'datecreation' => $alors['datecreation'],
							'permission' => $permission
							));

$sujet= 'Compte validé';

$message_txt = '
Votre compte a bien été validé, sur le site <a href="forum-unsa.fr">forum-unsa.fr</a>.
Vous pouvez donc dorennavent vous inscrire et participer à la vie du site.

Voici, ci-joint votre login et votre mot de passe:

Login: '.$alors['login'].'
Mot de passe '.$alors['mdp'].'

<a href="forum-unsa.fr">Se connecter</a>

Dorenavant, ceux-ci seront crypter, ce mail est donc l\'ultime sauvegarde de vos identifiants.';



$message_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Information</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </head>
<body>


<p>Votre compte a bien été validé, sur le site <a href="http://forum-unsa.fr">forum-unsa.fr</a>.</p>
<p>Vous pouvez donc dorennavent vous inscrire et participer à la vie du site.</p>

<p>Voici, ci-joint votre login et votre mot de passe:</p>

<table>
<tr>
<td>Login:</td><td>'.$alors['login'].'</td>
</tr>
<tr>
<td>Mot de passe:</td><td>'.$alors['mdp'].'</td>
</tr>
<tr>
<td><a href="http://forum-unsa.fr">Se connecter</a></td>
</tr>
</table>

<p>Dorenavant, ceux-ci seront crypter, ce mail est donc l\'ultime sauvegarde de vos identifiants.</p>

</body>
</html>';



$suppresion = $basedonnees ->prepare('DELETE FROM confirmation WHERE login= ?');
$suppresion -> execute(array($alors['login']));



}














//si valider egal non alors on supprimer les donnée
elseif($valider == 'non')
{

$suppresion = $basedonnees ->prepare('DELETE FROM confirmation WHERE login= ?');
$suppresion -> execute(array($alors['login']));


$sujet = 'Comtpe refusé';

$message_txt = '
Nous somme désolé mais votre compte s\'est vu être supprimer, nous rappellons que vous devez faire partie de la communauté pour pouvoir vous inscrire.
Si c\'est le cas alors le problème vient surement du faite que vous avez renseigné de mauvaise information qui nous on empecher de vous identifier.';

$message_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Information</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </head>
<body>

<p>Nous somme désolé mais votre compte s\'est vu être supprimer, nous rappellons que vous devez faire partie de la communauté pour pouvoir vous inscrire.</p>
<p>Si c\'est le cas alors le problème vient surement du faite que vous avez renseigné de mauvaise information qui nous on empecher de vous identifier.</p>






</body>
</html>
';












}
//fin de boucle si valider egal non



//debut de l'envoie de l'e-mail

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}



//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========



//=====Création du header de l'e-mail.
$header = "From: \"Validation du compte\"<forum-unsa.fr@gmail.com>".$passage_ligne;
$header.= "Reply-to: \"Eviter de répondre\" <forum-unsa.fr@gmail.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========






if(mail($alors['mail'], $sujet, $message, $header))
{
$notification = 'Mail de notification envoyé';
}

else
{
$notification= 'Envoie du mail impossible';
}









//fin de l'envoie


}
//fin de boucle si le code est correct

$validation->closeCursor();

if(!isset($reussi))
{
$notification = 'Lien déja validé.';
}

}
//fin de boucle s'il n'y a pas l'URL






}

}














//fin de traitement
$titre = 'Validation';
include('head.php');

?>

<body>





<p>La Page que vous cherchez est introuvable</p>




































</body>
</html>

