<?php
session_start();

if(!isset($_SESSION['permission']))
{
include('mdp.php');
//mot de passe securit�
}
else
{
include('stock.php');
//connection base de donn�e
?>

<?php
//debut de traitement

$poster = $_POST['poster'];

if(isset($poster))//si un formu envoyer
{



$titre = htmlspecialchars($_POST['titre']);//titre
$contenu = htmlspecialchars($_POST['contenu']);//contenu


if (empty($_POST['titre']) OR empty($_POST['contenu']))
{
$notification = 'Tout les champs doivent �tre remplis.';
}



elseif(!preg_match("#^[a-zA-Z0-9]{1}#", $contenu))
{
$notification = 'Vous ne pouvez commencer votre Question par un espace.';
}


elseif(!preg_match("#^.{10}#", $contenu))
{
$notification = 'Votre Qestion doit avoir une longueur minimum de 10 caracth�res.';
}



elseif(!preg_match("#^[a-zA-Z0-9]{1}#", $titre))
{
$notification = 'Votre Titre ne peut pas commencer par un espace.';
}

elseif(!preg_match("#^.{4}#", $titre))
{
$notification = 'Votre Titre doit avoir une longueur minimum de 4 caracth�res.';
}






else//si aucune erreur
{


//========traitement des liens


if(preg_match("#{(.+)}#", $contenu))
{
if(preg_match("#{www(.+)}#", $contenu))
{
$contenu = preg_replace("#(.+){(.+);(.+)}(.+)#isU", '$1{http://$2;$3}$4', $contenu);


}




}
else
{

$contenu = preg_replace("#http://[a-z0-9._/-]+#isU", '<a href="$0">$0</a>', $contenu);

if(!preg_match("#<a>#", $contenu))
{
$contenu = preg_replace("#www.[a-z0-9._/-]+#isU", '<a href="http://$0">$0</a>', $contenu);
}
}


//===========fin des liens








//verifier si d�ja envoy� !
$req = $basedonnees ->query('SELECT titre, contenu FROM question');

while($meme = $req ->fetch())
{

if($_POST['titre'] == $meme['titre'] AND $_POST['contenu'] == $meme['contenu'])
{
$notification = 'Ce message existe d�j� sur le forum.';
}

}
$req->closeCursor();




if(!isset($notification))//si c'est tjrs bon
{



//postage du message
$req = $basedonnees->prepare('INSERT INTO question(proprietaire, titre, contenu, datecreation) VALUES (:proprietaire, :titre, :contenu, NOW())');
$req->execute (array(
		'proprietaire' => $_SESSION['proprietaire'],
		'titre' => $titre,
		'contenu' => $contenu

		));

$notification = 'Votre message � bien �t� post�.';

}//fin de l'envoie

}//fin de si pas d'erreur
}//fin de post envoyer

//fin de traitement
$titre = 'Accueil';
include('head.php');

?>

<body>

<?php

include('menu.php');
?>


<div id="contenu">




<?php
//calcul du nombre de page

$table = 'question';
$pageweb = 'question.php?';
include('pagination.php');

//fin calcul des pages
$req = $basedonnees -> query('SELECT q.id id, q.titre titre, q.contenu contenu, i.prenom prenom, i.avatar avatar, i.nom nom, i.mail mail, DATE_FORMAT(q.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM question q INNER JOIN  inscrit i ON q.proprietaire = i.id ORDER BY q.datecreation DESC LIMIT '.$debut.', 5');



?>
</h5>
<?php
while ($discussion = $req->fetch())
{

$com = $basedonnees->prepare('SELECT COUNT(*) AS nbrcom FROM question_commentaire WHERE idquestion = ? ');
$com ->execute(array($discussion['id']));
$comment = $com->fetch();
$com ->closeCursor();

$traite = $discussion['contenu'];

$traite = preg_replace('#{(.+);(.+)}#i','<a href="$1" target="_blank">$2</a>' , $traite);



?>
<table class="tableauquestion">

<tr>
	<td colspan="2" style="width: 20%;"><?php echo $discussion['date']; ?></td>

	<td style="width: 70%; font-size: 20px; font-weight: bold;"><?php echo htmlspecialchars($discussion['titre']); ?></td>
	<td style="width: 10%;" rowspan="3"><a href="reponse.php?id=<?php echo htmlspecialchars($discussion['id']);?>"><em>R�ponses</em>(<?php echo $comment['nbrcom'];?>)</a></td>
</tr>
<tr>
	<td rowspan="2" style="height: 50px; width: 60px;"><img style="height: 100%; width: 100%;" src="Images/avatars/<?php echo $discussion['avatar'];?>"/></td>
	<td style="font-style: italic;"><?php echo htmlspecialchars($discussion['nom']); ?></td>
	<td rowspan="2"><?php echo nl2br($traite); ?></td>
</tr>
<tr>
	<td><?php echo htmlspecialchars($discussion['prenom']); ?></td>

</tr>

</table>



<?php
}
$req ->closeCursor();




$table = 'question';
$pageweb = 'question.php?';
include('pagination.php');
?>




<h5><i>Pour inserer une question dans un lien vous devez la formuler comme suit:<br/>{www.monlien.fr;coucou}  ====>  <a href="index.php">Coucou</a></i></h5>

<form method="POST">
<table class="tableauquestion">
<tr>


<td style="width: 15%;"><label for="titre">Titre</label>:</td>

<td style="width: 75%;" colspan="2"><input maxlength= "50" style="width: 96%;" id="titre" type="text" name="titre" /></td>
<td rowspan="2" style="height: 70%; width: 20%;"><input style=" width: 70%; height: 25px;" name="poster" type="submit" Value="Poster"/></td>
</tr>
<tr>
<td>Question:</td>

<td><textarea style="width: 95%;" name="contenu" rows="5"></textarea/></td>





</tr>
<tr>

</tr>


</table>
</form>







































<?php
include('chat.php');
?>
</div>
</body>
</html>
<?php
}
?>
