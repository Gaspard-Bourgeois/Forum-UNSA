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

if(!preg_match("#[0-9]+#", $id))
{
$notification = 'Mauvaise adresse';
}
else//si il y a bien un id dans l'url
{
$idcorrect = 'oui';


$poster = $_POST['envoyer'];

if(isset($poster))//si un formu envoyer
{

$contenu = htmlspecialchars($_POST['contenu']);//contenu


if (empty($_POST['contenu']))
{
$notification = 'Tout les champs doivent &ecirc;tre remplis.';
}




elseif(!preg_match("#^[^\[\]]{2,}#", $contenu))
{
$notification = 'Votre Reponse doit avoir une longueur minimum de 2 caracth&egrave;res et ne doit pas contenir de m&eacute;tacaracthere.';
}







else//si aucune erreur
{

//verifier si d&eacute;ja envoy&eacute; !
$req = $basedonnees ->prepare('SELECT contenu FROM question_commentaire WHERE idquestion = ? AND proprietaire= ?');
$req->execute(array($id, $_SESSION['proprietaire']));


while($meme = $req ->fetch())
{

if($contenu == $meme['contenu'])
{
$notification = 'Ce message a d&eacute;ja &eacute;t&eacute; envoy&eacute;';
}

}
$req->closeCursor();




if(!isset($notification))//si c'est tjrs bon
{


//postage du message
$req = $basedonnees->prepare('INSERT INTO question_commentaire(idquestion, proprietaire, contenu, datecreation) VALUES (:idquestion, :proprietaire, :contenu, NOW())');
$req->execute (array(
		'idquestion' => $id,
		'proprietaire' => $_SESSION['proprietaire'],
		'contenu' => $contenu

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
<div id="contenu">
<p>Bienvenue sur la page d'accueil du site</p>


<?php


if(isset($idcorrect))
{





// rappel de la question en elle m&ecirc;me
$req = $basedonnees -> prepare('SELECT q.titre titre, q.contenu contenu, i.prenom prenom, i.avatar avatar, i.avatarproportion avatarproportion, i.nom nom, i.mail mail, DATE_FORMAT(q.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM question q INNER JOIN  inscrit i ON q.proprietaire = i.id WHERE q.id = ? ORDER BY q.datecreation ');
$req->execute(array($id));


while($rappel = $req->fetch())
{

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

</table>


<?php
}
$req->closeCursor();
// fin de rappel de al question


// liste des commentaire
$comment = $basedonnees->prepare('SELECT q.contenu contenu, DATE_FORMAT(q.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date, i.avatar avatar, i.avatarproportion avatarproportion, i.nom nom, i.prenom prenom FROM question_commentaire q INNER JOIN inscrit i ON q.proprietaire = i.id WHERE q.idquestion=? ORDER BY q.datecreation');
$comment->execute(array($id));
?>
<table class="tableaureponses">
<?php
$i = 0;
while($fiull = $comment->fetch())
{
$i = 'ok';

$filtre = $fiull['contenu'];
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
<form action="reponse.php?id=<?php echo $id;?>" method="POST">

	<td rowspan="2" style="height: 20px; width: 20px;"><a onclick="new MaxBox(this, '639', '356'); return false;" href="Images/avatars/<?php echo $_SESSION['savatar'];?>"><img height="<?php echo ($_SESSION['savatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $_SESSION['savatar'];?>"/></a></td>

	<td style="font-style: italic; width: 15%;"><?php echo $_SESSION['snom']; ?></td>

	<td rowspan="2"><textarea style="width: 95%; background-color: #aeff83;" name="contenu" rows="5" required autofocus><?php echo nl2br(htmlspecialchars($_POST['contenu']))?></textarea></td>

	<td rowspan="2" style="width: 15%;"><input type="submit" name="envoyer" value="Poster"/></td>
</form>
</tr>
<tr>

	<td><?php echo $_SESSION['sprenom']; ?></td>


</tr>

</table>





<p><a href="question.php">Retourner au question</a></p>


































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
