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


// debut des variable pour le code de limage


$preo = $_SESSION['proprietaire'];



// fin des variable pour le code de l'image

$envoyer = $_POST['envoyer'];

if(isset($envoyer))
{

//declare les variable utile au traitement
$maxsize = 2000000000;

$titre = $_POST['titre'];
$datesortie = $_POST['datesortie'];


//fin declaration des variable en question
if(empty($titre) OR empty($datesortie))
{
$notification = 'Vous devez donner les informations lié au film';
}
elseif(!preg_match("#[1-2]{1}[6789012]{1}[0-9]{2}#", $datesortie))
{
$notification = 'La date de sortie doit être une date valide';
}
elseif(empty($_FILES['film']['size']) )
{
$notification = 'Vous devez indiquer un fichier';
}



else
{
//definit les extension a valider

$extensions_valides = array( 'avi' , 'mp4' , 'wmv' );
$extension_upload = strtolower(  substr(  strrchr($_FILES['film']['name'], '.')  ,1)  );

$titre = strtolower($titre);
$titre = ucfirst($titre);
$datesortie = strtolower($datesortie);
$datesortie = ucfirst($datesortie);
$adresse = $titre.'('.$datesortie.'';


$chemin = ''.$adresse.'.'.$extension_upload.'';



$verif = $basedonnees->query('SELECT streaming FROM film');


while($simil = $verif->fetch())
{

if($simil['streaming'] == $chemin)
{
$notification = 'Vous avez déja poster ce fichier';
}


}
$verif->closeCursor();


if(!isset($notification))
{

//fin

if($_FILES['film']['error'] > 0)
{
 $notifcation = "Erreur lors du transfert";
}

elseif ($_FILES['film']['size'] > $maxsize)
{
 $notification = "Le fichier est trop gros";
}
elseif(!in_array($extension_upload,$extensions_valides))
{

$notification =  "Extension incorrect";
}

else
{

//envoie de la film




  $trajet = 'Images/film/'.$adresse.'.'.$extension_upload.'';

if(move_uploaded_file($_FILES['film']['tmp_name'],$trajet))
{




$notification =  "Transfert réussi";


$pos = $basedonnees->prepare('INSERT INTO film (proprietaire, titre, datesortie, streaming, size, compteur, datecreation) VALUES (:proprietaire, :titre, :datesortie, :streaming, :size, :compteur, NOW())');
$pos->execute(array(
					'proprietaire' => $_SESSION['proprietaire'],
					'titre' => $titre,
					'datesortie' => $datesortie,
					'streaming' => $chemin,
					'size' => $_FILES['film']['size'],
					'compteur' => '0'
					));



}//fin de l'envoie





}//fin de traitement de l'envoie

}
}

}//fin du si formulaire envoyé





//traitement de l'order

$ordre = $_GET['order'];

if(preg_match("#^datesortie$|^titre$|^taille$|^datecreation$|^compteur$#", $ordre))
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
$titre = 'Cinémathèque';
include('head.php');

?>

<body>

<?php

include('menu.php');
?>





<div id="contenu">

<p><center>Bienvenue sur la page d'upload de film du site</center></p>






<?php




$req = $basedonnees -> query('SELECT m.titre titre, m.datesortie datesortie, m.streaming streaming, m.size size, m.compteur compteur, i.prenom prenom, i.nom nom, DATE_FORMAT(m.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM film m INNER JOIN  inscrit i ON m.proprietaire = i.id ORDER BY '.$orderby.' ');

?>

<table style="width: 100%; text-align: center; margin: auto;">
<tr>
<th><a href="film.php?order=compteur">Hits:</a></th>
<th><a href="film.php?order=titre">Titre:</a></th>
<th><a href="film.php?order=datesortie">datesortie:</a></th>
<th><a href="film.php?order=size">Taille:</a></th>
<th><a href="film.php?order=uploadeur">Uploadeur:</a></th>
<th><a href="film.php?order=date">Date:</a></th>
</tr>
<?php

while ($discussion = $req->fetch())
{

$size = $discussion['size'] / 1000000;
$size = round($size);

?>


<tr>
<td style="width: 15%;"><a href="Images/telechargement.php?type=film&fichier=<?php echo $discussion['streaming'];?>"><input type="button" value="Télécharger (<?php echo $discussion['compteur'];?>)"/></a></td>
<td style="width: 15%;"><?php echo $discussion['titre'];?></td>
<td style="width: 10%;"><?php echo $discussion['datesortie'];?></td>
<td><i><?php echo $size;?>Mo</i></td>
<td style="width: 40%;">Upload by <?php echo $discussion['nom'];?> <?php echo $discussion['prenom'];?></td>
<td style="width: 20%;"><i><?php echo $discussion['date'];?></i></td>

</tr>





<?php
}
$req ->closeCursor();
?>

</table>





<div style="width: 60%; margin: auto;">

<form method="post" action="film.php" enctype="multipart/form-data">
<fieldset><legend>Mes films</legend>
<table style="width: 60%; margin: auto; text-align: center;">



<tr>
<td><label for="titre">Titre</label>:<input id="titre" type="text" name="titre"/></td>
<td ><label for="datesortie">Date de sortie</label>:<input  id="datesortie" type="text" name="datesortie" maxlenght="4"/></td>
</tr>

<tr>
	<td colspan="2"><input type="file" name="film"/> (Max: 2 000Mo)</td>
</tr>
<tr>

     <td colspan="2"><input type="submit" name="envoyer" value="Envoyer" /></td>

</tr>




</table>
</fieldset>
</form>


</div>































<?php
include('chat.php');
?>

</div>
</body>
</html>

<?php

}
?>
