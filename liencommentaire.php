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

if(isset($_GET['id']))
{

$id = htmlspecialchars($_GET['id']);
$theme = htmlspecialchars($_GET['theme']);

if(!preg_match("#[0-9]+#", $id))
{
$notification = 'Mauvaise adresse';
}
elseif(!preg_match("#^dm$|^cour$|^revision$|^ti$#", $theme))
{
$notification = 'adresse invalide.';
}
else//si il y a bien un id dans l'url
{
$var1 = $theme;
$idcorrect = $id;



$poster = $_POST['envoyer'];

if(isset($poster))//si un formu envoyer
{


$commentaire = htmlspecialchars($_POST['commentaire']);//commentaire


if (empty($_POST['commentaire']))
{
$notification = 'Tout les champs doivent &ecirc;tre remplis.';
}




elseif(!preg_match("#^[^\[\]]{2,}#", $commentaire))
{
$notification = 'Votre Reponse doit avoir une longueur minimum de 2 caracth&egrave;res et ne doit pas contenir de m&eacute;tacaracthere.';
}







else//si aucune erreur
{

//verifier si d&eacute;ja envoy&eacute; !
$req = $basedonnees ->prepare('SELECT commentaire FROM liencommentaire WHERE idquestion = ? AND proprietaire= ?');
$req->execute(array($id, $_SESSION['proprietaire']));


while($meme = $req ->fetch())
{

if($commentaire == $meme['commentaire'])
{
$notification = 'Ce message a d&eacute;ja &eacute;t&eacute; envoy&eacute;';
}

}
$req->closeCursor();




if(!isset($notification))//si c'est tjrs bon
{


//postage du message
$bdd1 = $basedonnees->prepare('INSERT INTO liencommentaire(idlien, proprietaire, commentaire, datecreation) VALUES (:idlien, :proprietaire, :commentaire, NOW())');
$bdd1->execute(array(
		'idlien' => $id,
		'proprietaire' => $_SESSION['proprietaire'],
		'commentaire' => $commentaire

		));

$notification = 'Votre message à bien &eacute;t&eacute; post&eacute;.';

}//fin de l'envoie

}//fin de si pas d'erreur
}//fin de post envoyer

}

}//fin get if




//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

</head><body  onload="javascript:change_onglet('<?php echo $_SESSION['songletchat'];?>');">

<?php

include('menu.php');
?>

<p>Bienvenue sur la page d'accueil du site</p>

<div id="contenu">
<?php


if(isset($idcorrect))
{





// rappel de la question en elle m&ecirc;me
$req = $basedonnees -> prepare('SELECT q.titre titre, q.contenu contenu, i.prenom prenom, i.avatar avatar, i.avatarproportion avatarproportion, i.nom nom, i.mail mail, DATE_FORMAT(q.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM lien q INNER JOIN  inscrit i ON q.proprietaire = i.id WHERE q.id = ? ORDER BY q.datecreation ');
$req->execute(array($idcorrect));


while($rappel = $req->fetch())
{

//commande pour les lien du fichier

$bdd2 = $basedonnees -> prepare('SELECT titrefichier, streaming, size, compteur FROM fichier WHERE id_lien = ? ');
$bdd2->execute(array($idcorrect));

while ($fich = $bdd2->fetch())
{



$titrefichier = $fich['titrefichier'];
$size = $fich['size'] / 1000;
$size = round($size);
$acces = 'Images/telechargement.php?type='.$var1.'&fichier='.$fich['streaming'].'';
$compteur = $fich['compteur'];


}
$bdd2 ->closeCursor();
//les donn&eacute;e du fichier sont stock&eacute; dans des variables









$ficheur = $basedonnees -> prepare('SELECT id,titre, compteur FROM fiche WHERE id_lien = ? ');
$ficheur->execute(array($rappel['idcorrect']));

while ($anale = $ficheur->fetch())
{



$titretexte = $anale['titre'];
$accestexte = $anale['id'];
$compteurtexte = $anale['compteur'];


}
$ficheur ->closeCursor();






$traite = $rappel['contenu'];



$traite = preg_replace('#{(.+);(.+)}#i','<a href="$1">$2</a>' , $traite);
?>
<table class="tableauquestion">

<tr>
	<td colspan="2" style="width: 15%;"><?php echo $rappel['date']; ?></td>

	<td style="width: 75%; font-size: 20px; font-weight: bold;"><?php echo htmlspecialchars($rappel['titre']); ?></td>
</tr>
<tr>
	<td rowspan="2" style="height: 70px; width: 70px;"><a onclick="new MaxBox(this, '639', '356'); return false;" href="Images/avatars/<?php echo $rappel['avatar'];?>"><img height="<?php echo ($rappel['avatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $rappel['avatar'];?>"/></a></td>
	<td style="font-style: italic;"><?php echo htmlspecialchars($rappel['nom']); ?></td>
	<td rowspan="2"><?php echo nl2br($traite); ?></td>
</tr>
<tr>
	<td><?php echo htmlspecialchars($rappel['prenom']); ?></td>

</tr>


<?php
if(!empty($titrefichier))
{

?>

<tr>

<td style="background-color: #ecffe3;" colspan="2">Fichier:</td>
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

<td style="background-color: #ecffe3;" colspan="2">Fiche:</td>
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
$req->closeCursor();
// fin de rappel de al question


// liste des commentaire
$comment = $basedonnees->prepare('SELECT q.id id, q.commentaire commentaire, DATE_FORMAT(q.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date, i.avatar avatar, i.avatarproportion avatarproportion, i.nom nom, i.prenom prenom FROM liencommentaire q INNER JOIN inscrit i ON q.proprietaire = i.id WHERE q.idlien= ? ORDER BY q.datecreation');
$comment->execute(array($idcorrect));
?>
<table class="tableaureponses">
<?php
$i = 0;
while($fiull = $comment->fetch())
{
$i = 'ok';

$filtre = $fiull['commentaire'];
$filtre = preg_replace('#{(.+);(.+)}#i','<a href="$1" target="_blank">$2</a>' , $filtre);



?>


<tr>

	<td rowspan="2" style="height: 20px; width: 20px;"><a onclick="new MaxBox(this, '639', '356'); return false;" href="Images/avatars/<?php echo $fiull['avatar'];?>"><img height="<?php echo ($fiull['avatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $fiull['avatar'];?>"/></a></td>

	<td style="font-style: italic; width: 15%;"><?php echo htmlspecialchars($fiull['nom']); ?></td>

	<td rowspan="2"><?php echo nl2br($filtre); ?></td>

	<td rowspan="2" style="width: 15%;"><?php echo $fiull['date']; ?></td>
</tr>
<tr>

	<td><?php echo htmlspecialchars($fiull['prenom']); ?></td>

</tr>





<?php
}
$comment->closeCursor();
// fin de liste des commentaire
?>
</table>

<table class="tableaureponse">


<?php
if($i != 'ok')
{
?>

<tr><td><center>Il n'y a pas de notification</center</td></tr>
<?php
}//fin de pas de notification




}//si pas de notification


?>

<tr>
<form action="liencommentaire.php?theme=<?php echo $var1;?>&id=<?php echo $idcorrect;?>" method="POST">

	<td rowspan="2" style="height: 20px; width: 20px;"><a onclick="new MaxBox(this, '639', '356'); return false;" href="Images/avatars/<?php echo $_SESSION['savatar'];?>"><img height="<?php echo ($_SESSION['savatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $_SESSION['savatar'];?>"/></a></td>

	<td style="font-style: italic; width: 15%;"><?php echo $_SESSION['snom']; ?></td>

	<td rowspan="2"><textarea style="width: 95%; background-color: #aeff83;" name="commentaire" rows="5"  required autofocus><?php echo nl2br(htmlspecialchars($_POST['commentaire']))?></textarea></td>

	<td rowspan="2" style="width: 15%;"><input type="submit" name="envoyer" value="Poster"/></td>
</form>
</tr>
<tr>

	<td><?php echo $_SESSION['sprenom']; ?></td>


</tr>

</table>





<p><a href="partage.php?theme=<?php echo $var1;?>">Retourner au question</a></p>

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
