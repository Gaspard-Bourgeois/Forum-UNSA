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


$commentaire = htmlspecialchars($_POST['commentaire']);//commentaire


if (empty($_POST['commentaire']))
{
$notification = 'Tout les champs doivent être remplis.';
}




elseif(!preg_match("#^[^\[\]]{2,}#", $commentaire))
{
$notification = 'Votre Reponse doit avoir une longueur minimum de 2 caracthères et ne doit pas contenir de métacaracthere.';
}







else//si aucune erreur
{

//verifier si déja envoyé !
$req = $basedonnees ->prepare('SELECT commentaire FROM liencommentaire WHERE idquestion = ? AND proprietaire= ?');
$req->execute(array($id, $_SESSION['proprietaire']));


while($meme = $req ->fetch())
{

if($commentaire == $meme['commentaire'])
{
$notification = 'Ce message a déja été envoyé';
}

}
$req->closeCursor();




if(!isset($notification))//si c'est tjrs bon
{


//postage du message
$fake = $basedonnees->prepare('INSERT INTO liencommentaire(idlien, proprietaire, commentaire, datecreation) VALUES (:idlien, :proprietaire, :commentaire, NOW())');
$fake->execute(array(
		'idlien' => $id,
		'proprietaire' => $_SESSION['proprietaire'],
		'commentaire' => $commentaire

		));

$notification = 'Votre message à bien été posté.';

}//fin de l'envoie

}//fin de si pas d'erreur
}//fin de post envoyer

}

}//fin get if




//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

<body>

<?php

include('menu.php');
?>

<p>Bienvenue sur la page d'accueil du site</p>

<div id="contenu">
<?php


if(isset($idcorrect))
{





// rappel de la question en elle même
$req = $basedonnees -> prepare('SELECT q.titre titre, q.contenu contenu, i.prenom prenom, i.avatar avatar, i.nom nom, i.mail mail, DATE_FORMAT(q.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM lien q INNER JOIN  inscrit i ON q.proprietaire = i.id WHERE q.id = ? ORDER BY q.datecreation ');
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
	<td rowspan="2" style="height: 70px; width: 70px;"><img style="height: 70px; width: 70px;" src="Images/avatars/<?php echo $rappel['avatar'];?>"/></td>
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
$comment = $basedonnees->prepare('SELECT q.commentaire commentaire, DATE_FORMAT(q.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date, i.avatar avatar, i.nom nom, i.prenom prenom FROM liencommentaire q INNER JOIN inscrit i ON q.proprietaire = i.id WHERE q.idlien= ? ORDER BY q.datecreation');
$comment->execute(array($id));
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

	<td rowspan="2" style="height: 20px; width: 20px;"><img style="height: 70px; width: 70px;" src="Images/avatars/<?php echo $fiull['avatar'];?>"/></td>

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
<form action="liencommentaire.php?id=<?php echo $id;?>" method="POST">

	<td rowspan="2" style="height: 20px; width: 20px;"><img style="height: 60px;; width: 60px;;" src="Images/avatars/<?php echo $_SESSION['avatar'];?>"/></td>

	<td style="font-style: italic; width: 15%;"><?php echo $_SESSION['nom']; ?></td>

	<td rowspan="2"><textarea style="width: 95%; background-color: #aeff83;" name="commentaire" rows="5"><?php echo nl2br(htmlspecialchars($_POST['commentaire']))?></textarea></td>

	<td rowspan="2" style="width: 15%;"><input type="submit" name="envoyer" value="Poster"/></td>
</form>
</tr>
<tr>

	<td><?php echo $_SESSION['prenom']; ?></td>


</tr>

</table>





<p><a href="question.php">Retourner au question</a></p>

</div>

































<?php
include('chat.php');
?>


</body>
</html>

<?php

}
?>
