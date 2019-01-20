<?php
include('stock.php');
//connection base de donnée
?>

<?php
//debut traitement
if(!isset($_GET['code']))
{
include('mdp.php');
//mot de passe securité
}
else
{
$code = htmlspecialchars($_GET['code']);

//doit rajouter une securisation de get

//securité pour transformer en nombre

if(!isset($notification))
{
$decodeur = $basedonnees ->query('SELECT login FROM confirmation WHERE confirmer = \'0\'');

while($crypte = $decodeur ->fetch())
{
$login = $crypte['login'];
$cryptage = hexdec($login);
$cryptage = $cryptage*18;
$cryptage = $cryptage-101996;




if($cryptage == $code)
{

//on valide la confirmation de la personne qui vient clicker sur le lien
$valide = $basedonnees ->prepare('UPDATE confirmation SET confirmer = \'1\' WHERE login = ?');
$valide ->execute(array($crypte['login']));

$correct = 'ok';


}
}
$decodeur->closeCursor();
//on a finit de traiter la personne elle est a donc dorenavant valider son lien





$un = '1';
//on renvoie tout les mails des personne en attente d'inscription
$admin = $basedonnees ->prepare('SELECT login, mdp, sexe, nom, prenom, mail, DATE_FORMAT(datenaissance, \'%d / %m / %y\') AS datenaissance_fr, news, DATE_FORMAT(datecreation, \'%d / %m / %y\') AS datecreation_fr FROM confirmation WHERE confirmer = ?');
$admin ->execute(array($un));

while($info = $admin->fetch())
{
//envoie du mail

$mail = 'votremail@free.fr';
//adresse d'envoie


//debut de la creation d'un code

$code = $info['login'];
$code = hexdec($code);
$code = $code +2012;
$code = $code *2;
$code = $code -1996;
$code = $code *16;


//fin du code

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = '



Login: '.$info['login'].'

Mot de passe: '.$info['mdp'].'

Sexe: '.$info['sexe'].'

Nom: '.$info['nom'].'

Prenom: '.$info['prenom'].'

E-mail: '.$info['mail'].'

Date de naissance: '.$info['datenaissance_fr'].'

Est inscrit au news: '.$info['news'].'

Date de cration du compte: '.$info['datecreation_fr'].'




<a href="http://forum-unsa.fr/validation.php?valider=oui&code='.$code.'">validation.php?valider=oui&code='.$code.'</a>



<a href="http://forum-unsa.fr/validation.php?valider=non&code='.$code.'">validation.php?valider=non&code='.$code.'</a>






';
$message_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Information</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </head>
<body>

<table>

<tr>
<td>Login: </td><td>'.$info['login'].'</td>
</tr>
<tr>
<td>Mot de passe: </td><td>'.$info['mdp'].'</td>
</tr>
<tr>
<td>Sexe: </td><td>'.$info['sexe'].'</td>
</tr>
<tr>
<td>Nom: </td><td>'.$info['nom'].'</td>
</tr>
<tr>
<td>Prenom: </td><td>'.$info['prenom'].'</td>
</tr>
<tr>
<td>E-mail: </td><td>'.$info['mail'].'</td>
</tr>
<tr>
<td>Date de naissance: </td><td>'.$info['datenaissance_fr'].'</td>
</tr>
<tr>
<td>Est inscrit au news: </td><td>'.$info['news'].'</td>
</tr>
<tr>
<td>Date de cration du compte: </td><td>'.$info['datecreation_fr'].'</td>
</tr>

</table>

<table>
<tr>
<td style="width: 100px; height: 100px;"><a href="http://forum-unsa.fr/validation.php?valider=oui&code='.$code.'"><input type="button" value="Confirmer"/></a></td>
</tr>
<tr>
<td><a href="http://forum-unsa.fr/validation.php?valider=non&code='.$code.'"><input type="button" value="Refuser"/></a></td>
</tr>
</table>


</body>
</html>';
//==========

//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========

$sujet= ''.$info['prenom'].' '.$info['nom'].'';

//=====Création du header de l'e-mail.
$header = "From: \"Petit nouveau\"<unsa@gmail.com>".$passage_ligne;
$header.= "Reply-to: \"Eviter de répondre\" <unsa@gmail.com>".$passage_ligne;
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






if(mail($mail, $sujet, $message, $header) AND isset($correct))
{
$notification = 'Votre compte a bien été confirmer, un admin va au plus vite rendre votre compte opérationnel. Un mail vous sera envoyer pour vous prevenir.';

}
elseif(isset($correct))
{
$notification = 'Une erreur est survenu, nous ne pouvons valider votre compte.';
}
else
{
$notification= 'Lien non valide';
}


//si une donne est vieille de plus d'un moi elle est supprimer
$nonconfirmer = $basedonnees ->query('SELECT login, DATE_FORMAT(datecreation, \'%m\') AS month, DATE_FORMAT(datecreation, \'%y\') AS year FROM confirmation WHERE confirmer=\'0\' ');

$mois = date("m");
$annee = date("Y");

while($chercher = $nonconfirmer->fetch())
{

if((($annee - $chercher['year']) * 30 + $mois - $chercher['month'])>1)
{
//supression
$supr = $basedonnees ->prepare('DELETE FROM confirmation WHERE login= ?');
$supr -> execute(array($chercher['login']));



}






}
$nonconfirmer->closeCursor();



}

$admin->closeCursor();

}





//fin de traitement
$titre = 'Confirmation de compte';
include('head.php');
?>
<body>



<p>Bienvenue sur la page de confirmation de votre compte</p>

<p><i><?php echo $notification;?></i></p>

<p>Si vous n'arrivez pas à valider votre compte, verifier bien l'url du lien que vous avez saisie.</p>
<p>Sinon votre compte n'a pas été creer, je vous invite donc à le faire, <a href="inscription.php">ici</a></p>



</body>
</html>
<?php
}
?>
