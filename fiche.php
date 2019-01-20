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



$fake = $basedonnees->prepare('UPDATE fiche SET contenu = :fiche WHERE id= :id');
$fake->execute(array(
					'fiche' => $fiche,
					'id' => $nbr));

}

}
}
//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

<script language="Javascript">
function imprimer(){window.print();}
</script>

</head><body  onload="javascript:change_onglet('<?php echo $_SESSION['songletchat'];?>');">

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

?>

<p>Si vous ne sauvegarder pas votre fichier au bout de 5 minutes une autre personne pourrai vous empecher de le faire.</p>


<table>
<tr>
<?php

$pardon = $basedonnees-> prepare('SELECT titre, contenu, datecreation, id_lien FROM fiche WHERE id = ?');
$pardon->execute(array($nbr));

while($conte = $pardon->fetch())
{


$auteur = $basedonnees->prepare('SELECT i.avatar avatar, i.avatarproportion avatarproportion, i.nom nom, i.prenom prenom FROM lien l INNER JOIN inscrit i ON l.proprietaire = i.id WHERE l.id = ?');
$auteur->execute(array($conte['id_lien']));

while($proprio = $auteur->fetch())
{
$avatar = $proprio['avatar'];
$prenom = $proprio['prenom'];
$nom = $proprio['nom'];

}
$auteur->closeCursor();

?>

<td><a onclick="new MaxBox(this, '639', '356'); return false;" href="Images/avatars/<?php echo $avatar;?>"><img height="<?php echo ($proprio['avatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $avatar;?>"/></a></td>
<td><?php echo htmlspecialchars($nom); ?></td>
<td><?php echo htmlspecialchars($prenom); ?></td>
</tr>
<tr>
<td colspan="2" style="font-size: 50px;"><center><?php echo $conte['titre']; ?></center></td>
<td><?php echo $conte['datecreation'];?></td>

<?php
$var1 = nl2br($conte['contenu']);
}
$pardon->closeCursor();

$var2 = preg_replace('#<br />#', '', $var1);

if($_GET['convertir'])
{

$var2 = preg_replace('#[ù]#', '=', $var2);
$var2 = preg_replace('#[ü]#', '->' , $var2);
$var2 = preg_replace('#[÷]#', '<', $var2);
$var2 = preg_replace('#[ù]#', '>', $var2);
$var2 = preg_replace('#[ø]#', '!=', $var2);
$var2 = preg_replace('#[ú]#', '-', $var2);

}

?>
</tr>
<form>
<tr>


<td rowspan="3"><div onclick="imprimer()"><?php echo nl2br($var2);?></div></td>

<td id="nonafficher"><a href="fiche.php?demande=<?php echo $demande;?>&nbr=<?php echo $nbr;?>&convertir=avantimpression" ><input name="B1" type="button" value="Convertir"></a></td>

</tr>
<tr>

<td><div id="nonafficher"><input name="B1" onclick="imprimer()" type="button" value="Imprimer"/></div></td>
</tr>
<tr>
<td><div id="nonafficher"><a href="fiche.php?demande=modification&nbr=<?php echo $nbr;?>"><input type="button" value="Editer"/></a></div></td>

</tr>
</form>
</table>

<a href="partage.php?theme=ti">Retour au menu TI</a>



















<?php
}
if($demande == 'modification')
{




?>
<p>Si vous ne sauvegarder pas votre fichier au bout de 5 minutes une autre personne pourrai vous empecher de le faire.</p>


<table>
<tr>
<?php

//$pardon = $basedonnees->prepare('SELECT i.nom nom, i.prenom prenom, i.avatar avatar, f.titre titre, f.contenu contenu FROM fiche f INNER JOIN inscrit i ON f.proprietaire = i.id WHERE f.id_lien = ?');
$pardon = $basedonnees-> prepare('SELECT titre, contenu, datecreation, id_lien FROM fiche WHERE id = ?');
$pardon->execute(array($nbr));

while($conte = $pardon->fetch())
{


$auteur = $basedonnees->prepare('SELECT i.avatar avatar, i.avatarproportion avatarproportion i.nom nom, i.prenom prenom FROM lien l INNER JOIN inscrit i ON l.proprietaire = i.id WHERE l.id = ?');
$auteur->execute(array($conte['id_lien']));

while($proprio = $auteur->fetch())
{
$avatar = $proprio['avatar'];
$prenom = $proprio['prenom'];
$nom = $proprio['nom'];

}
$auteur->closeCursor();

?>

<td>Auteur:</td>
<td><a onclick="new MaxBox(this, '639', '356'); return false;" href="Images/avatars/<?php echo $avatar;?>"><img height="<?php echo ($proprio['avatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $avatar;?>"/></a></td>
<td><?php echo htmlspecialchars($nom); ?></td>
<td><?php echo htmlspecialchars($prenom); ?></td>
</tr>
<tr>
<td colspan="2" style="font: 20px;"><center><?php echo $conte['titre']; ?></center></td>
<td><?php echo $conte['datecreation'];?></td>

<?php
$var1 = nl2br($conte['contenu']);
}
$pardon->closeCursor();

$var2 = preg_replace('#<br />#', '', $var1);

?>
</tr>
</table>
<br/>
<form method="POST">
<table style="width: 100%; height: 300px;">

<tr>
<td><input type="submit" name="envoyer" value="sauvegarder" /></td>
<td><a href="fiche.php?demande=apercu&nbr=<?php echo $nbr;?>"><input type="button" name="test" value="Apercu" /></a></td>

</tr>
<tr>
<td ><p style="color: grey; margin-left: 10px;">Lorsque vous copier coller un texte de <a href="http://forum-unsa.fr/liencommentaire.php?id=23">TI Program Editor</a>, <br/>
laisser le comme telle pour que les autres utilisateurs puisse le copier dans leur editeur personelle ! Plus rapidement<br/>
</p>
</td>

</tr>
<tr>
<td colspan ="2" style="width: 100%;"><textarea style="width: 100%;" rows="30" name="fiche"  required><?php echo $var2; ?></textarea></td>
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
