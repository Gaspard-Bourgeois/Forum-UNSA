<?php
session_start();

if(!isset($_SESSION['permission']))
{
include('mdp.php');
//mot de passe securité
}

else
{
include('stock.php');
//connection base de donnée
?>

<?php
//debut de traitement



$demande= $_GET['demande'];
$nbr= $_GET['nbr'];
if(!empty($demande) AND !empty($nbr))
{

if(!preg_match("#^apercu$|^modification$#", $demande) OR !preg_match("#[0-9]+#", $nbr))
{
$notification = 'Mauvaise URL';
}
else
{


if(isset($_POST['envoyer']))
{


$fiche = htmlspecialchars($_POST['fiche']);



$bdd1 = $basedonnees->prepare('UPDATE fiche SET contenu = :fiche WHERE id= :id');
$bdd1->execute(array(
					'fiche' => $fiche,
					'id' => $nbr));

}

}
}
//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

<body>

<?php

include('menu.php');






$demande= $_GET['demande'];
$nbr= $_GET['nbr'];
if(!empty($demande) AND !empty($nbr))
{

if(!preg_match("#^apercu$|^modification$#", $demande) OR !preg_match("#[0-9]+#", $nbr))
{
$notification = 'Mauvaise URL';
}
else
{
?>


<div id="contenu">
<?php
if($demande == 'apercu')
{




$bdd2 = $basedonnees->prepare('SELECT i.nom nom, i.prenom prenom, i.avatar avatar, f.titre titre, f.contenu contenu FROM fiche f INNER JOIN inscrit i ON f.proprietaire = i.id WHERE f.id_lien = :id');
$bdd2->execute(array('id' => $nbr));

while($bdd2_i = $bdd2->fetch())
{
?>

<td>Auteur:</td>
<td><img style="width: 70px; height: 70px;" src="Images/avatars/<?php echo $bdd2_i['avatar'];?>"/></td>
<td><?php echo htmlspecialchars($bdd2_i['nom']); ?></td>
<td><?php echo htmlspecialchars($bdd2_i['prenom']); ?></td>
</tr>
<tr>
<td colspan="4"><center><?php echo $bdd2_i['titre']; ?></center></td>
</tr>
<tr>
<td colspan ="4" style="width: 100%;"><?php echo $bdd2_i['text']; ?></td>


<?php
$contenu1 = nl2br($bdd2_i['contenu']);
}
$bdd2->closeCursor();

?>
</tr>
</table>

















<?php
}
if($demande == 'modification')
{




?>
<p>Si vous ne sauvegarder pas votre fichier au bout de 5 minutes une autre personne pourrai vous empecher de le faire.</p>


<table>
<tr>
<?php

//$bdd3 = $basedonnees->prepare('SELECT i.nom nom, i.prenom prenom, i.avatar avatar, f.titre titre, f.contenu contenu FROM fiche f INNER JOIN inscrit i ON f.proprietaire = i.id WHERE f.id_lien = ?');
$bdd3 = $basedonnees-> prepare('SELECT titre, contenu, datecreation, id_lien FROM fiche WHERE id = ?');
$bdd3->execute(array($nbr));

while($bdd3_i = $bdd3->fetch())
{


$bdd4 = $basedonnees->prepare('SELECT i.avatar avatar, i.nom nom, i.prenom prenom FROM lien l INNER JOIN inscrit i ON l.proprietaire = i.id WHERE l.id = ?');
$bdd4->execute(array($bdd3_i['id_lien']));

while($bdd4_i = $bdd4->fetch())
{
$avatar = $bdd4_i['avatar'];
$prenom = $bdd4_i['prenom'];
$nom = $bdd4_i['nom'];

}
$bdd4->closeCursor();

?>

<td>Auteur:</td>
<td><img style="width: 70px; height: 70px;" src="Images/avatars/<?php echo $avatar;?>"/></td>
<td><?php echo htmlspecialchars($nom); ?></td>
<td><?php echo htmlspecialchars($prenom); ?></td>
</tr>
<tr>
<td colspan="2" style="font: 20px;"><center><?php echo $bdd3_i['titre']; ?></center></td>
<td><?php echo $bdd3_i['datecreation'];?></td>

<?php
$contenu1 = nl2br($bdd3_i['contenu']);
}
$bdd3->closeCursor();

$contenu1_r = preg_replace('#<br />#', '', $contenu1);

?>
</tr>
</table>
<br/>
<form method="POST">
<table style="width: 100%; height: 300px;">

<tr>
<td><input type="submit" name="envoyer" value="sauvegarder" /></td>
<td><a href="http://forum-unsa.fr/fiche.php"><input type="button" name="test" value="actualiser" /></a></td>

</tr>
<tr>
<td ><p style="color: white; margin-left: 10px;">Lorsque vous copier coller un texte de <a href="http://forum-unsa.fr/liencommentaire.php?id=23">TI Program Editor</a>, <br/>
laisser le comme telle pour que les autres utilisateurs puisse le copier dans leur editeur personelle ! Plus rapidement<br/>
<br/>
Pour information lorsqu'il s'agit de code source de calculette provennant de la TI:<br/>
ù		=<br/>
ü		-><br/>
÷		<<br/>
ù		><br/>
ø		different<br/></p>
</td>

</tr>
<tr>
<td colspan ="2" style="width: 100%;"><textarea style="width: 100%;" rows="30" name="fiche"><?php echo $contenu1_r; ?></textarea></td>
</tr>

</table>

</form>














<?php
}
}
?>
</div>
<?php
}
?>









<?php
include('chat.php');
?>

</body>
</html>

<?php

}
?>
