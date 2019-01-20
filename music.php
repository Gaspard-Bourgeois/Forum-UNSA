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


// debut des variable pour le code de limage


$preo = $_SESSION['proprietaire'];



// fin des variable pour le code de l'image

$envoyer = $_POST['envoyer'];

if(isset($envoyer))
{

//declare les variable utile au traitement
$maxsize = 2000000000;

$titre = $_POST['titre'];
$auteur = $_POST['auteur'];


//fin declaration des variable en question
if(empty($titre) OR empty($auteur))
{
$notification = 'Vous devez donner les informations li&eacute; à la chanson';
}
elseif(empty($_FILES['music']['size']) )
{
$notification = 'Vous devez indiquer un fichier';
}



else
{
//definit les extension a valider

$extensions_valides = array( 'wav' , 'mp3' , 'wmv' , 'mp2', 'm3u' , 'avi' );
$extension_upload = strtolower(  substr(  strrchr($_FILES['music']['name'], '.')  ,1)  );

$titre = strtolower($titre);
$titre = ucfirst($titre);
$auteur = strtolower($auteur);
$auteur = ucfirst($auteur);
$adresse = $auteur.'-'.$titre;


$chemin = ''.$adresse.'.'.$extension_upload.'';



$verif = $basedonnees->query('SELECT streaming FROM music');


while($simil = $verif->fetch())
{

if($simil['streaming'] == $chemin)
{
$notification = 'Vous avez d&eacute;ja poster ce fichier';
}


}
$verif->closeCursor();


if(!isset($notification))
{

//fin

if($_FILES['music']['error'] > 0)
{
 $notifcation = "Erreur lors du transfert";
}

elseif ($_FILES['music']['size'] > $maxsize)
{
 $notification = "Le fichier est trop gros";
}
elseif(!in_array($extension_upload,$extensions_valides))
{

$notification =  "Extension incorrect";
}

else
{

//envoie de la music




  $trajet = 'Images/music/'.$adresse.'.'.$extension_upload.'';

if(move_uploaded_file($_FILES['music']['tmp_name'],$trajet))
{




$notification =  "Transfert r&eacute;ussi";


$pos = $basedonnees->prepare('INSERT INTO music (proprietaire, titre, auteur, streaming, size, compteur, datecreation) VALUES (:proprietaire, :titre, :auteur, :streaming, :size, :compteur, NOW())');
$pos->execute(array(
					'proprietaire' => $preo,
					'titre' => $titre,
					'auteur' => $auteur,
					'streaming' => $chemin,
					'size' => $_FILES['music']['size'],
					'compteur' => '0'
					));



}//fin de l'envoie





}//fin de traitement de l'envoie

}
}

}//fin du si formulaire envoy&eacute;





//traitement de l'order

$ordre = $_GET['order'];

if(preg_match("#^auteur$|^titre$|^taille$|^datecreation$|^compteur$#", $ordre))
{

$orderby = 'm.'.$ordre ;

}
elseif(preg_match("#^uploadeur$#", $ordre))
{
$orderby = 'i.nom';
}
else
{
$orderby = 'm.datecreation';
}





//fin de traitemenr





//fin de traitement
$titre = 'Musicoth&egrave;que';
include('head.php');

?>

</head><body  onload="javascript:change_onglet('<?php echo $_SESSION['songletchat'];?>');">

<?php

include('menu.php');
?>





<div id="contenu">

<p><center>Bienvenue sur la page d'upload de music du site</center></p>






<?php




$req = $basedonnees -> query('SELECT m.titre titre, m.auteur auteur, m.streaming streaming, m.size size, m.compteur compteur, i.prenom prenom, i.nom nom, DATE_FORMAT(m.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM music m INNER JOIN  inscrit i ON m.proprietaire = i.id ORDER BY '.$orderby.' ');

?>

<table style="text-align: center;">
<tr>
<th><a href="music.php?order=compteur">Hits:</a></th>
<th><a href="music.php?order=auteur">Auteur:</a></th>
<th><a href="music.php?order=titre">Titre:</a></th>
<th><a href="music.php?order=size">Taille:</a></th>
<th><a href="music.php?order=uploadeur">Uploadeur:</a></th>
<th><a href="music.php?order=date">Date:</a></th>
</tr>
<?php

while ($discussion = $req->fetch())
{

$size = $discussion['size'] / 1000000;
$size = round($size);

?>


<tr>
<td style="width: 15px;"><a href="Images/telechargement.php?type=music&fichier=<?php echo $discussion['streaming'];?>"><input type="button" value="T&eacute;l&eacute;charger (<?php echo $discussion['compteur'];?>)"/></a></td>
<td style="width: 10px;"><?php echo $discussion['auteur'];?></td>
<td style="width: 15px;"><?php echo $discussion['titre'];?></td>
<td><i><?php echo $size;?>Mo</i></td>
<td style="width: 40px;">Upload by <?php echo $discussion['nom'];?> <?php echo $discussion['prenom'];?></td>
<td style="width: 20px;"><i><?php echo $discussion['date'];?></i></td>

</tr>





<?php
}
$req ->closeCursor();
?>

</table>





<div>

<form method="post" action="music.php" enctype="multipart/form-data">
<fieldset><legend>Ma music</legend>
<table style="width: 60%; margin: auto; text-align: center;">



<tr>
<td><label for="titre">Titre</label>:<input id="titre" type="text" name="titre" required/></td>
<td ><label for="auteur">Auteur</label>:<input  id="auteur" type="text" name="auteur" required/></td>
</tr>

<tr>
	<td colspan="2"><input type="file" name="music"/></td>
</tr>
<tr>

     <td colspan="2"><input type="submit" name="envoyer" value="Envoyer" /></td>

</tr>




</table>
</fieldset>
</form>


</div>





























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
