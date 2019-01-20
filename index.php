<?php
session_start();

if(isset($_GET['logout']))
{
session_destroy();
session_start();

}




include('stock.php');
//connection base de donn&eacute;e
?>

<?php
//debut de traitement

if(isset($_POST['entrer']))
{

$wrong = 'Mauvais mot de passe.';
$login = htmlspecialchars($_POST['login']);

$mdp = htmlspecialchars($_POST['mdp']);

$login = strtolower($login);
$mdp = strtolower($mdp);



if(empty($login) OR empty($mdp))
{
$notification = 'Tous les champs doivent &ecirc;tre remplis.';
}
elseif(!preg_match("#^[a-zA-Z0-9&eacute;&egrave;ï]{4,20}$#", $login))
{
$notification = 'Votre login doit contenir entre 5 et 20 caract&egrave;res';
}
elseif(!preg_match("#^[a-zA-Z0-9]{4,20}$#", $mdp))
{
$notification = 'Votre mot de passe doit contenir entre 5 et 20 caract&egrave;res.';
}
else
{

$passwd = base_convert($mdp, 16, 2);
//cryptage du code donn&eacute;e lors du formulaire



include('stock.php');
//connection base de donn&eacute;e


$connexion = $basedonnees -> query('SELECT id, avatar, avatarproportion, login, mdp, sexe, nom, prenom, mail, news, DATE_FORMAT(datenaissance, \'%d-%m-%y\') AS date, permission, chat FROM inscrit');
//conexion base de donn&eacute;e inscrit




while($identifiant = $connexion -> fetch())
{


if($login == $identifiant['login'] AND $passwd == $identifiant['mdp'])

{



//debut de difinition des sessions


$_SESSION['proprietaire'] = $identifiant['id'];
$_SESSION['savatar'] = $identifiant['avatar'];
$_SESSION['slogin'] = $identifiant['login'];
$_SESSION['ssexe'] = $identifiant['sexe'];
$_SESSION['snom'] = $identifiant['nom'];
$_SESSION['sprenom'] = $identifiant['prenom'];
$_SESSION['smail'] = $identifiant['mail'];
$_SESSION['sdate'] = $identifiant['date'];
$_SESSION['snews'] = $identifiant['news'];
$_SESSION['spermission'] = $identifiant['permission'];
$_SESSION['songletchat'] = $identifiant['chat'];
$_SESSION['savatarproportion'] = $identifiant['avatarproportion'];


//fin de definition des sessions



}
//fin de verification si c'est la bonne personne

}
$connexion ->closeCursor();
//fin de boucle


}
//fin du traitement des sessions







}
//fin du traitement des donn&eacute;e envoyer par l'utilisateur


if(!isset($_SESSION['spermission']))
{
include('mdp.php');
//mot de passe securit&eacute;
}
else
{


//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

</head><body  onload="javascript:change_onglet('<?php echo $_SESSION['songletchat'];?>');">

<?php

include('menu.php');
?>
<div id="contenu">
<p><center>Bienvenue sur la page d'accueil du site, vous avez disponible ci-dessous un rapide apercu des nouveaut&eacute; du site</center></p>





<?php


$i = 1;
while($i<=4)
{
if($i == 1)
{
$var1 = 'dm';
}
if($i == 2)
{
$var1 = 'cour';
}
if($i == 3)
{
$var1 = 'revision';
}
if($i == 4)
{
$var1 = 'ti';
}



$i = $i +1;
//recherche des donn&eacute;e de tite....de la page
$bdd1 = $basedonnees -> prepare('SELECT l.id id, l.titre titre, l.contenu contenu, i.avatar avatar, i.prenom prenom, i.nom nom, i.mail mail, DATE_FORMAT(l.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM lien l INNER JOIN  inscrit i ON l.proprietaire = i.id WHERE sujet = :sujet ORDER BY l.datecreation DESC LIMIT 0, 3');
$bdd1->execute(array('sujet' => $var1));
while ($bdd1_i = $bdd1->fetch())
{
//compter le nombre de commentaire par boucle
$bdd2 = $basedonnees->prepare('SELECT COUNT(*) AS nbrcom FROM liencommentaire WHERE idlien = ? ');
$bdd2 ->execute(array($bdd1_i['id']));
$bdd2ment = $bdd2->fetch();
$bdd2 ->closeCursor();
//s'occuper des liens
$traite = nl2br($bdd1_i['contenu']);
$traite = preg_replace('#\[(.+)\;(.+)\]#i','<a href="$1">$2</a>' , $traite);
//commande pour les lien du fichier
$bdd3 = $basedonnees -> prepare('SELECT titrefichier, streaming, size, compteur FROM fichier WHERE id_lien = ? ');
$bdd3->execute(array($bdd1_i['id']));
while ($bdd3_i = $bdd3->fetch())
{
$titrefichier = $bdd3_i['titrefichier'];
$size = $bdd3_i['size'] / 1000;
$size = round($size);
$acces = 'Images/telechargement.php?type='.$var1.'&fichier='.$bdd3_i['streaming'].'';
$compteur = $bdd3_i['compteur'];
}
$bdd3 ->closeCursor();
//les donn&eacute;e du fichier sont stock&eacute; dans des variables
$bdd4 = $basedonnees -> prepare('SELECT id, titre, compteur FROM fiche WHERE id_lien = ? ');
$bdd4->execute(array($bdd1_i['id']));
while ($bdd4_i = $bdd4->fetch())
{
$titretexte = $bdd4_i['titre'];
$accestexte = $bdd4_i['id'];
$compteurtexte = $bdd4_i['compteur'];
}
$bdd4 ->closeCursor();
//afichage du tableau pour chaque commentaire
?>
<table class="tableauindex">

<tr>
<td><?php echo htmlspecialchars($var1); ?></td>
<td style="width: 75%; font-size: 20px; font-weight: bold;"><a href="liencommentaire.php?theme=<?php echo $var1;?>&id=<?php echo $bdd1_i['id'];?>"><?php echo htmlspecialchars($bdd1_i['titre']); ?></a></td>
<td style="font-style: italic; height: 35px; width: 15%;"><?php echo htmlspecialchars($bdd1_i['nom']); ?></td>
<td style="height: 35px; width: 15%;"><?php echo htmlspecialchars($bdd1_i['prenom']); ?></td>
<td style="width: 15%;"><?php echo $bdd1_i['date']; ?></td>
</tr>


<?php
if(!empty($titrefichier))
{
?>
<tr>

<td style="background-color: #ecffe3;">Fichier:</td>
<td style="width: 70px; background-color: #ecffe3;"><?php echo htmlspecialchars($titrefichier); ?></td>
<td style="background-color: #ecffe3;"><?php echo $size; ?>Ko</td>
<td style="background-color: #ecffe3;" colspan="2"><a href="<?php echo htmlspecialchars($acces); ?>"><input type="button" value="T&eacute;l&eacute;charg&eacute; (<?php echo htmlspecialchars($compteur); ?>)"/></a></td>

</tr>
<?php
}
if(!empty($titretexte))
{
?>
<tr>

<td style="background-color: #ecffe3;">Fiche:</td>
<td style="width: 70px; background-color: #ecffe3;"><?php echo htmlspecialchars($titretexte); ?></td>
<td style="background-color: #ecffe3;">  <a href="fiche.php?demande=apercu&nbr=<?php echo $accestexte;?>">  <input type="button" value="Apercu"/>  </a>  </td>
<td style="background-color: #ecffe3;" colspan="2"><a href="fiche.php?demande=modification&nbr=<?php echo $accestexte;?>">  <input type="button" value="Modifi&eacute; (<?php echo htmlspecialchars($compteurtexte); ?>)"/></a></td>

</tr>
<?php
}
?>
</table>
<?php
}
$bdd1->closeCursor();
}
?>























</div>
<?php
include('agenda.php');
?>



<?php
include('chat.php');
?>

</body>
</html>
<?php

}
?>
