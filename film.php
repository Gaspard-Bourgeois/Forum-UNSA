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
$notification = 'Vous devez donner les informations li&eacute; à la chanson';
}
elseif(empty($_FILES['film']['size']) )
{
$notification = 'Vous devez indiquer un fichier';
}



else
{
//definit les extension a valider

$extensions_valides = array( 'mp4', 'mpg' , 'avi' );
$extension_upload = strtolower(  substr(  strrchr($_FILES['film']['name'], '.')  ,1)  );

$titre = strtolower($titre);
$titre = ucfirst($titre);
$datesortie = strtolower($datesortie);
$datesortie = ucfirst($datesortie);
$adresse = $datesortie.'-'.$titre;


$chemin = ''.$adresse.'.'.$extension_upload.'';



$verif = $basedonnees->query('SELECT streaming FROM film');


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




$notification =  "Transfert r&eacute;ussi";


$pos = $basedonnees->prepare('INSERT INTO film (proprietaire, titre, datesortie, streaming, size, compteur, datecreation) VALUES (:proprietaire, :titre, :datesortie, :streaming, :size, :compteur, NOW())');
$pos->execute(array(
					'proprietaire' => $preo,
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

}//fin du si formulaire envoy&eacute;





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
$titre = 'Filmoth&egrave;que';
include('head.php');

?>

</head><body  onload="javascript:change_onglet('<?php echo $_SESSION['songletchat'];?>');">

<?php

include('menu.php');
?>

<p><center>Bienvenue</center></p>








<div id="contenu">

<p><center>Bienvenue sur la page d'upload de film du site</center></p>






<?php




$req = $basedonnees -> query('SELECT m.titre titre, m.datesortie datesortie, m.streaming streaming, m.size size, m.compteur compteur, i.prenom prenom, i.nom nom, DATE_FORMAT(m.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM film m INNER JOIN  inscrit i ON m.proprietaire = i.id ORDER BY '.$orderby.' ');

?>

<table style="text-align: center;">
<tr>
<th><a href="film.php?order=compteur">Hits:</a></th>
<th><a href="film.php?order=datesortie">datesortie:</a></th>
<th><a href="film.php?order=titre">Titre:</a></th>
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
<td style="width: 15px;"><a href="Images/telechargement.php?type=film&fichier=<?php echo $discussion['streaming'];?>"><input type="button" value="T&eacute;l&eacute;charger (<?php echo $discussion['compteur'];?>)"/></a></td>
<td style="width: 10px;"><?php echo $discussion['datesortie'];?></td>
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

<form method="post" action="film.php" enctype="multipart/form-data">
<fieldset><legend>Ma film</legend>
<table style="width: 60%; margin: auto; text-align: center;">



<tr>
<td><label for="titre">Titre</label>:<input id="titre" type="text" name="titre" required/></td>
<td ><label for="datesortie">datesortie</label>:<input  id="datesortie" type="text" name="datesortie" required/></td>
</tr>

<tr>
	<td colspan="2"><input type="file" name="film"/></td>
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

</body>
</html>

<?php

}
?>
