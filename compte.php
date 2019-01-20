<?php
session_start();

if(!isset($_SESSION['spermission']))
{

include('mdp.php');
//mot de passe securit&eacute;
}
else
{
include('stock.php');
//connection base de donn&eacute;e
?>

<?php
//debut de traitement




//==============================  avatar    ========================================================


$nom=$_SESSION['snom'];
$prenom=$_SESSION['sprenom'];
$numero = $nom.' '.$prenom;
$preo = $_SESSION['proprietaire'];
$adresse = hexdec($numero);


// fin des variable pour le code de l'image


if(isset($_POST['envoyer']))
{

//declare les variable utile au traitement
$maxsize = 2000000;
$maxheight = 1000;
$maxwidth = 1000;



//fin declaration des variable en question

if(empty($_FILES['avatar']['size']))
{
$notification = 'Formulaire non-remplis';
}
else
{
//definit les extension a valider

$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'ico' , 'txt' );
$extension_upload = strtolower(  substr(  strrchr($_FILES['avatar']['name'], '.')  ,1)  );

$image_sizes = getimagesize($_FILES['avatar']['tmp_name']);

//fin

if($_FILES['avatar']['error'] > 0)
{
 $notifcation = "Erreur lors du transfert";
}

elseif ($_FILES['avatar']['size'] > $maxsize)
{
 $notification = "Le fichier est trop gros";
}
elseif(!in_array($extension_upload,$extensions_valides))
{

$notification =  "Extension incorrect";
}

elseif ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
{
 $notification = "Image trop grande";
}
else
{

//envoie de l'image


 $all = $adresse.'.'.$extension_upload.'';

  $trajet = 'Images/avatars/'.$adresse.'.'.$extension_upload.'';
  $proportion = $image_sizes[1] / $image_sizes[0];

if(move_uploaded_file($_FILES['avatar']['tmp_name'],$trajet))
{


$notification =  "Transfert r&eacute;ussi";



$pos = $basedonnees->prepare('UPDATE inscrit SET avatar = :avatar, avatarproportion = :avatarproportion WHERE id = :id');
$pos->execute(array(
					'avatar' => $all,
					'avatarproportion' => $proportion,
					'id' => $preo));



}//fin de l'envoie





}//fin de traitement de l'envoie


}

}//fin du si formulaire envoy&eacute;



$luidgi = $_GET['image'];


if(isset($luidgi))
{
$adresse = '0';



$pos = $basedonnees->prepare('UPDATE inscrit SET avatar=? WHERE id=?');
$pos->execute(array($adresse, $preo));


} //fin du si il y a une envie de suppression d'image











//==========================================    information   ===============================

if(isset($_POST['modif']))
{

echo '1';
$old = htmlspecialchars($_POST['old']);
$new = htmlspecialchars($_POST['new']);
$new2 = htmlspecialchars($_POST['new2']);

$old = strtolower($old);
$new = strtolower($new);
$new2 = strtolower($new2);


if(empty($old) OR empty($new) OR empty($new2))
{
$notification = 'Tout les champs doivent &ecirc;tre remplis.';
}
elseif($new != $new2)
{
$notification = 'Les champs du nouveau mot de passe doivent &ecirc;tre identiques';
}
elseif(!preg_match("#^[a-zA-Z0-9]{4,20}$#", $new))
{
$notification = 'Votre mot de passe doit contenir entre 5 et 20 caract&egrave;res.';
}
else
{
echo '2';

$ancien = base_convert($old, 16, 2);

$paswd = $basedonnees->prepare('SELECT mdp FROM inscrit WHERE id=?');
$paswd->execute(array($_SESSION['proprietaire']));

while($pas = $paswd->fetch())
{

if ($ancien == $pas['mdp'])
{

$nouveau = base_convert($new, 16, 2);


$tintin = $basedonnees->prepare('UPDATE inscrit SET mdp = ? WHERE id = ?');
$tintin->execute(array($nouveau, $_SESSION['proprietaire']));



//envoie d'un mail

$message_txt = '
Votre mot de passe a &eacute;t&eacute; chang&eacute;, sur le site <a href="http://forum-unsa.fr">forum-unsa.fr</a>.
Vous devez donc dorennavent vous connecter avec ces identifiants.

Voici, ci-joint votre login et votre mot de passe:

Login: '.$_SESSION['slogin'].'
Mot de passe :'.$new.'

<a href="forum-unsa.fr">Se connecter</a>

Dorenavant, ceux-ci seront crypter, ce mail est donc l\'ultime sauvegarde de vos identifiants.';



$message_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Information</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </head>
</head><body>


<p>Votre mot de passe a &eacute;t&eacute; chang&eacute;, sur le site <a href="http://forum-unsa.fr">forum-unsa.fr</a>.</p>
<p>Vous devez donc dorennavent vous connecter avec ces identifiants.</p>

<p>Voici, ci-joint votre login et votre mot de passe:</p>

<table>
<tr>
<td>Login:</td><td>'.$_SESSION['slogin'].'</td>
</tr>
<tr>
<td>Mot de passe:</td><td>'.$new.'</td>
</tr>
<tr>
<td><a href="http://forum-unsa.fr">Se connecter</a></td>
</tr>
</table>

<p>Dorenavant, ceux-ci seront crypter, ce mail est donc l\'ultime sauvegarde de vos identifiants.</p>

</body>
</html>';







$emmeteur = 'Compte sur UNSA';
$sujet = 'Modification du mot de passe.';
$mail = $_SESSION['smail'];
$envoieV = 'Votre nouveau mot de passe vous a &eacute;t&eacute; envoy&eacute; par mail. Il est dorenavant valide';
$envoieX = 'Votre mot de passe a &eacute;t&eacute; chang&eacute;';
include('mail.php');




}


}
$paswd->closeCursor();





if(!isset($notification))
{

$notification = 'Ancien mot de passe invalide.';

}

}

}
//=============================================================   preference    ==============================================================








if(isset($_POST['chatvalider']))
{
$chat = htmlspecialchars($_POST['chatpreference']);

if(!preg_match("#^discussion$|^connecter$|^fermer$|^tout$#", $chat))
{
$notification = 'Mauvaise adresse';
}
else
{
$modifier = $basedonnees->prepare('UPDATE inscrit SET chat = :preference WHERE id = :proprietaire');
$modifier->execute(array(
						'preference'=> $chat,
						'proprietaire' => $_SESSION['proprietaire']
						));
$modifier->closeCursor();
$_SESSION['songletchat'] = $chat;

$notification = 'Modification effectu&eacute;';
}


}







//fin de traitement
$titre = 'Accueil';
include('head.php');

?>


    <script type="text/javascript">
         //<!--
                 function change_fenetre(nom)
                 {
                         document.getElementById('fenetre_'+anc_fenetre).className = 'fenetre_0 fenetre';
                         document.getElementById('fenetre_'+nom).classnom = 'fenetre_1 fenetre';
                         document.getElementById('contenu_fenetre_'+anc_fenetre).style.display = 'none';
                         document.getElementById('contenu_fenetre_'+nom).style.display = 'block';
                         anc_fenetre = nom;
                 }
         //-->
         </script>

</head><body  onload="javascript:change_onglet('<?php echo $_SESSION['songletchat'];?>');">


<?php

include('menu.php');
?>
<div id="contenu">
<p><center>Bienvenue sur la page d'acces a vos donn&eacute;es personnels</center></p>





<div id="all_fenetres">
        <div class="fenetre">
             <span class="fenetre_0 fenetre" id="fenetre_info" onclick="javascript:change_fenetre('info');">Information</span>
             <span class="fenetre_0 fenetre" id="fenetre_avatar" onclick="javascript:change_fenetre('avatar');">Avatar</span>
             <span class="fenetre_0 fenetre" id="fenetre_preference" onclick="javascript:change_fenetre('preference');">Preference</span>
         </div>



        <div class="contenu_fenetres">
             <div class="contenu_fenetre" id="contenu_fenetre_info">

 <table class="tableaucompte">

<form method="POST" action="#">
<tr>
<td colspan="2">Login:</td>
<td colspan="2"><?php echo $_SESSION['slogin'];?></td>
</tr>
<tr>
<td colspan="2">Ancien Mot de passe:</td>
<td colspan="2"><input type="password" name="old"  required /></td>
</tr>
<tr>
<td colspan="2">Nouveau :</td>
<td colspan="2"><input type="password" name="new"  required /></td>
</tr>
<tr>
<td colspan="2">Retaper le nouveau:</td>
<td colspan="2"><input type="password" name="new2" required /></td>
</tr>
<tr>
<td colspan="2">Prenom:</td>
<td colspan="2"><?php echo $_SESSION['sprenom'];?></td>
</tr>
<tr>
<td colspan="2">Nom:</td>
<td colspan="2"><?php echo $_SESSION['snom'];?></td>
</tr>
<tr>
<td colspan="2">Adresse Mail:</td>
<td colspan="2"><?php echo $_SESSION['smail'];?></td>
</tr>
<tr>
<td colspan="2">Date de naissance:</td>
<td colspan="2"><?php echo $_SESSION['sdate'];?></td>
</tr>
<tr>
<td colspan="2">Vous etes inscrit au news:</td>
<td colspan="2"><?php echo $_SESSION['snews'];?></td>
</tr>
<tr>
<td colspan="2">Valider les modification:</td>
<td colspan="2"><input type="submit" name="modif" value="Modifier" /></td>
</tr>
</form>





 </table>


             </div>
             <div class="contenu_fenetre" id="contenu_fenetre_avatar">



<?php

//=============================================================   avatar ==============================================
//verif BDD
$imag = $basedonnees ->prepare('SELECT avatar, avatarproportion FROM inscrit WHERE id = ?');
$imag ->execute(array($preo));
while($good = $imag->fetch())
{
?>


<table class="tableaucompte">
		<form method="post" action="#" enctype="multipart/form-data">







<tr>
<td rowspan="2" style="height: 70px; width: 20%;"><img height="<?php echo ($good['avatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $good['avatar'];?>"/></td>
	<td style="font-style: italic; height: 35px; width: 10%;"><?php echo $_SESSION['snom']; ?></td>
	<td><label for="avatar">Changer votre avatar :</label></td><td>    (Taille max : 2 Mo)</td>

</tr>
<tr>

	<td style="height: 35px; width: 15%;"><?php echo $_SESSION['sprenom'];  ?></td>
	<td colspan="2"><input type="file" name="avatar" id="avatar"  required /></td>
</tr>
<tr>

		<td><a href="http://forum-unsa.fr/compte.php"><input type="button" value="Actualiser l'image" /></a></td>
		<td><a href="avatar.php?image=none"><input type="button" value="Supprimer l'image" /></a></td>
        <td colspan="2"><input type="submit" name="envoyer" value="Modifier son profil" /></td>

</tr>




</form>
</table>

<?php
}
$imag->closeCursor();
//========================================================== fin avatar =========================================================
?>













             </div>
             <div class="contenu_fenetre" id="contenu_fenetre_preference">

<table class="tableaucompte">

<form method="POST">

<tr>
<td colspan="2"><label for="chat">Onglet preferencielle du chat:</label></td>
<td><select name="chatpreference" id="chat">
				<option value="discussion">Discussion</option>
				<option value="tout">Agenda</option>
				<option value="connecter">Connect&eacute;</option>
				<option value="fermer">Fermer</option>
				</select></td>
<td><input type="submit" value ="enregistrer" name="chatvalider"/></td>
</tr>
</form>




</table>
             </div>

</div>






</div>






    <script type="text/javascript">
         //<!--
                 var anc_fenetre = 'info';
                 change_fenetre(anc_fenetre);
         //-->
         </script>


























<?php
include('agenda.php');
?>





<?php
include('chat.php');
?>
</div>
</body>
</html>

<?php

}
?>
