<?php
include('stock.php');
//connection base de donn�e
?>

<?php
//debut de traitement
if(isset($_POST['envoyer']))
{
$login = htmlspecialchars($_POST['login']);
$mdp = htmlspecialchars($_POST['mdp']);
$mdp2 = htmlspecialchars($_POST['mdp2']);
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);
$sexe = htmlspecialchars($_POST['sexe']);
$mail = htmlspecialchars($_POST['mail']);
$datenaissance1 = htmlspecialchars($_POST['datenaissance1']);
$datenaissance2 = htmlspecialchars($_POST['datenaissance2']);
$datenaissance3 = htmlspecialchars($_POST['datenaissance3']);
$news = htmlspecialchars($_POST['news']);
$charte = htmlspecialchars($_POST['charte']);

$login = strtolower($login);
$mdp = strtolower($mdp);
$mdp2 = strtolower($mdp2);
$nom = strtoupper($nom);
$prenom = strtolower($prenom);
$prenom = ucfirst($prenom);
$mail = strtolower($mail);

if(!empty($news))
{
$news = 1;
}
if(empty($news))
{
$news = 0;
}

if(empty($login) OR empty($mdp) OR empty($mdp2) OR empty($nom) OR empty($prenom) OR empty($sexe) OR empty($mail) OR empty($datenaissance1) OR empty($datenaissance2) OR empty($datenaissance3))
{
$notification = 'Touts les champs doivent �tre remplis.';

}

elseif(empty($charte))
{
$notification = 'Vous devez accepter la charte de confidentialit� pour vous inscrire';
}

elseif(!preg_match("#^[a-zA-Z0-9���]{2,}$#", $login))
{
$notification = 'Votre login doit �tre compos� au minimum de 2 caractheres.';
}

elseif(!preg_match("#^[a-zA-Z0-9]{4,20}$#", $mdp))
{
$notification = 'Votre mot de passe doit �tre compos� de 5 � 20 caractheres.';
}

elseif(!preg_match("#^[a-zA-Z��]{1,}$#", $nom))
{
$notification = 'Votre nom doit �tre compos� de 3 � 30 caracth�res.';
}

elseif(!preg_match("#^[a-zA-Z���]{1,}$#", $prenom))
{
$notification = 'Votre pr�nom doit faire au minimu 3 � 30 caracth�res.';
}

elseif(!preg_match("#^M$|^F$#", $sexe))
{
$notification = 'Votre sexe ne peut �tre autre que Masculin ou Feminin.';
}

elseif(!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$#", $mail))
{
$notification = 'Vote adresse e-mail est invalide.';
}

elseif(!preg_match("#^[0-9]{2}$#", $datenaissance1) OR !preg_match("#^[0-9]{2}$#", $datenaissance2) OR !preg_match("#^[0-9]{4}$#", $datenaissance3))
{
$notification = 'Votre date de naissance est invalide.';
}

elseif($prenom == $nom)
{
$notification = 'Votre prenom et votre nom ne peuvent �tre identiques';
}
else
{
$verif = $basedonnees->query('SELECT login, nom, prenom, mail FROM confirmation');



while($oups = $verif->fetch())
{
if($oups['login'] == $login)
{
$notification = 'Ce login existe d�j�.';
}

if($oups['nom'] == $nom AND $oups['prenom'] == $prenom)
{
$notification = 'Cette identit� est d�ja utilis�. Vous avez surement d�j� cr�er un compte.';
}

if($oups['mail'] == $mail)
{
$notification = 'Cette adresse mail est d�j� utilis�e.';
}
}
//fin de boucle pour confirmation
$verif ->closeCursor();

$verif1 = $basedonnees-> query('SELECT login, nom, prenom, mail FROM inscrit');

while($oups = $verif1 ->fetch())
{
if($oups['login'] == $login)
{
$notification = 'Ce login existe d�j�.';
}

if($oups['nom'] == $nom AND $oups['prenom'] == $prenom)
{
$notification = 'Cette identit� est d�ja utilis�. Vous avez surement d�j� cr�er un compte.';
}

if($oups['mail'] == $mail)
{
$notification = 'Cette adresse mail est d�j� utilis�e.';
}
}
//fin de boucle pour les inscrits
$verif1 ->closeCursor();


if(!isset($notification))
{

$datenaissance = $datenaissance3.'/'.$datenaissance2.'/'.$datenaissance1;

$confirmation = $basedonnees->prepare('INSERT INTO confirmation(login, mdp, sexe, nom, prenom, mail, datenaissance, news, confirmer, datecreation) VALUES(:login, :mdp, :sexe, :nom, :prenom, :mail, :datenaissance, :news, 0, NOW())');
$confirmation -> execute(array(
								'login' => $login,
								'mdp' => $mdp,
								'sexe' => $sexe,
								'nom' => $nom,
								'prenom' => $prenom,
								'mail' => $mail,
								'datenaissance' => $datenaissance,
								'news' => $news
								));




$date = $basedonnees ->query('SELECT datenaissance FROM confirmation');


while($zero = $date->fetch())
{
if($zero['datenaissance'] == 0)
{
$notification = 'Votre date de naissance est invalide.';
$badnaissance = 'oui';
}
}

$date->closeCursor();
//fin de la boucle de verification de date


if(isset($badnaissance))
{
$zero = '0';
$jack = $basedonnees->prepare('DELETE FROM confirmation WHERE datenaissance= ? ');
$jack->execute(array($zero));
}
else
{
//envoie du mail

$code = hexdec($login);
$code = $code * 18;
$code = $code - 101996;





if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====D�claration des messages au format texte et au format HTML.
$message_txt = 'Merci de vous �tre inscrit sur le site Web: forum-unsa.fr.
Si vous reconnaissez �tre '.$nom.' '.$prenom.' , vous devez confirmer votre inscription en cliquant sur le lien ci-dessous:

Lien de confirmation: <a href="forum-unsa.fr/confirmation.php?code='.$code.'">forum-unsa.fr/confirmation.php?code='.$code.'</a>

Si vous pr�f�rez annuler votre inscription, il vous suffit de supprimer ce mail, sa suppression sera automatique apr�s l\'�coulement d\'un d�lai de trente jours.

Accueil du site: <a href="forum-unsa.fr/">forum-unsa.fr</a>';

$message_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Lien de de confirmation</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </head>
<body>

<p>Merci de vous �tre inscrit sur le site Web: forum-unsa.fr!</p>
<p>Si vous reconnaissez �tre '.$nom.' '.$prenom.' , vous devez confirmer votre inscription en cliquant sur le lien ci-dessous:</p>

<p>Lien de confirmation: <a href="forum-unsa.fr/confirmation.php?code='.$code.'">forum-unsa.fr/confirmation.php?code='.$code.'</a></p>

<p>Si vous pr�f�rez annuler votre inscription, il vous suffit de supprimer ce mail, sa suppression sera automatique apr�s l\'�coulement d\'un d�lai de trente jours.</p>

<p>Accueil du site: <a href="forum-unsa.fr/">forum-unsa.fr</a></p>




</body>
</html>';
//==========

//=====Cr�ation de la boundary
$boundary = "-----=".md5(rand());
//==========


 $sujet = 'Confirmation d\'inscription';

//=====Cr�ation du header de l'e-mail.
$header = "From: \"forum-unsa.fr\"<forum-unsa.fr@gmail.com>".$passage_ligne;
$header.= "Reply-to: \"Eviter de r�pondre\" <forum-unsa.fr@gmail.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Cr�ation du message.
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






if(mail($mail, $sujet, $message, $header))
{

$notification = 'Pour valider votre compte vous devez maintenant vous connecter sur votre boite mail.';
echo 'Pour valider votre compte vous devez maintenant vous connecter sur votre boite mail.';

}
else
{
$notification = 'Il est impossible de vous envoyer un mail a cette adresse';
}










}



}
}




}
//fin de traitement




//fin de traitement

?>
<!DOCTYPE html>
<html>
    <head>
       <title>Inscription</title>
	   <link rel="icon" href="Images/icone.png" />
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="css" href="login.css" />
	   <?php
	   if(isset($notification))
		{
	   ?>
	   <script>
		alert('<?php echo $notification;?>');
		</script>
	   <?php
	   }
	   ?>
	</head>


<body>







<div id="full" >
<div id="log_container" >



<div class="logbox">



<h5>
<fieldset><legend><strong>Inscription</strong></legend>
<form action="inscription.php" method="POST">


<em>Merci de remplir tout les champs ci-dessous pour vous inscrire et pouvoir participer au discussion.</em>
<fieldset><legend>Identifiant</legend>

<table>
<tr>
<td><label for="login">Login</label>:</td><td><input type="text" id="login" name="login" size="20" maxlength="15" placeholder="ex: Jay5" value=""></td>
<td rowspan="3"><p>Les majuscules et minuscules ne sont pas prises en compte</p></td>
</tr>

<tr>
<td><label for="mdp">Mot de Passe</label>:</td><td><input type="password" id="mdp" name="mdp" size="28" maxlength="20" placeholder="********"/></td>
</tr>

<tr>
<td><label for="mdp2">Confirmer le Mot de Passe</label>:</td><td><input type="password" id="mdp2" name="mdp2" size="28" maxlength="20" placeholder="Retaper le m�me mot de passe"/></td>
</tr>
</table>
</fieldset>
</p>
<p>
<fieldset><legend>Identit�</legend>
<table>

<tr>

<td><label for="prenom">Pr�nom</label>:</td><td><input type="text" id="prenom" name="prenom" size="28" maxlength="20" placeholder="ex: Jason" value=""/></td>


</tr>
<tr>



<td><label for="nom">Nom</label>:</td><td><input type="text" id="nom" name="nom" size="28" maxlength="20" placeholder="ex: Statham" value="" /></td>


</tr>

<td><label for="sexe">Sexe</label>:</td><td><select id="sexe" name="sexe">
									<option value="M">Masculin</option>
									<option value="F">F�minin</option>
								</select></td>

</tr>
<tr>

<td><label for="mail">Adresse Mail</label>:</td><td><input type="text" id="mail" name="mail" size="28" maxlength="85" placeholder="ex: jays.stath@free.fr" value=""/></td>

</tr>
<tr>

<td><label for="datenaissance">Date de Naissance</label>:</td><td><input type="text" id="datenaissance" size="3" maxlength="2" name="datenaissance1" value=""/> /
																	<input type="text" id="datenaissance" size="3" maxlength="2" name="datenaissance2" value=""/> /
																	<input type="text" id="datenaissance" size="5" maxlength="4" name="datenaissance3" value=""/></td>

</tr>

</table>
</fieldset>


<fieldset><legend>Engagement</legend>
<table>
<tr>

<td><label for="news">Je souhaite recevoir des mails m'informant des nouveaut�s, envoy� par les soins de l'administrateur </label>:</td><td><input type="checkbox" id="news" name="news" checked /></td>

</tr>
<tr>

<td><label for="confidence">J'accepte la charte de confidentialit� du site, disponible <a href="confidentialite.php">ici</a></label>:</td><td><input type="checkbox" id="confidence" name="charte" /></td>

</tr>
</table>

</fieldset>

<table>
<tr>
<td><input type="submit" name="envoyer" value="Valider"/></td>
<td><input type="reset" value="Reinitialis�"/></td>
<td><a href="index.php?"><input  type="button" value="Accueil"/></a></td>
</tr>

</table>

</form>

</fieldset>
</h5>


</div>

<script type="text/javascript">
 $(document).ready(function() {
    $("input[type=password]")[0].focus();
});
</script>



</div>
</div>






































<?php
include('notification.php');
?>
</body>
</html>

