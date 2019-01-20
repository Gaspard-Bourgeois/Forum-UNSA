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

//diferente page
$theme = htmlspecialchars($_GET['theme']);


if(!isset($_GET['theme']))
{
$notification = 'adresse invalide.';

echo 'Il n\'y a rien a affich&eacute; ici.';
}
elseif(!preg_match("#^dm$|^cour$|^revision$|^ti$#", $theme))
{
$notification = 'adresse invalide.';

echo 'Il n\'y a rien a affich&eacute; ici.';

}
else
{

//traite les donn&eacute;e li&eacute; à la page
$var1 = $theme;







$envoyer = $_POST['envoyer'];

if(isset($envoyer))
{





//===============================================traitement de l'envoie des fichiers


$titrefichier = $_POST['titrefichier'];



//fin declaration des variable en question
$transf = 'aucun';


if(!empty($titrefichier))
{

$transf= 'erreur';

//declare les variable utile au traitement
$maxsize = 20000000;

if(empty($_FILES['fichier']['size']) )
{
$notification = 'Vous devez indiquer un fichier';
}
elseif(!preg_match("#[a-zA-Z0-9]+#", $titrefichier))
{
$notification = 'Votre titre de fichier ne peut contenir de metacaracthere.';
}
else
{


//definit les extension a valider

$extensions_valides = array( 'pdf' , 'jpg' , 'jpeg' , 'gif', 'png', 'doc' , 'pps' , 'ppt' , '8xp' , 'ico' , 'zip');
$extension_upload = strtolower(  substr(  strrchr($_FILES['fichier']['name'], '.')  ,1)  );


$titrefichier = strtolower($titrefichier);
$titrefichier = ucfirst($titrefichier);
$adresse = $titrefichier;

$chemin = ''.$adresse.'.'.$extension_upload.'';


//verification d'un doublon
$verif = $basedonnees->query('SELECT streaming FROM fichier');

while($simil = $verif->fetch())
{

if($simil['streaming'] == $chemin)
{
$notification = 'Vous avez d&eacute;ja poster ce fichier';
}


}
$verif->closeCursor();
//fin verif doublon






if(!isset($notification))
{
//fin


if($_FILES['fichier']['error'] > 0)
{
 $notifcation = "Erreur lors du transfert";
}

elseif ($_FILES['fichier']['size'] > $maxsize)
{
 $notification = "Le fichier est trop gros";
}
elseif(!in_array($extension_upload,$extensions_valides))
{

$notification =  "Extension incorrect";
}

else
{

//envoie de la fichier



$fold = $var1;




 if($extension_upload == 'zip')
 {

 $fichier = $_FILES['fichier']['tmp_name'];

function unzip($fichier){
     $zip = zip_open($fichier);
     if(is_resource($zip)){
         $tree = "";
         while(($zip_entry = zip_read($zip)) !== false){
             echo "Unpacking ".zip_entry_name($zip_entry)."\n";
             if(strpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR) !== false){
                 $last = strrpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR);
                 $dir = substr(zip_entry_name($zip_entry), 0, $last);
                 $fichier = substr(zip_entry_name($zip_entry), strrpos(zip_entry_name($zip_entry), DIRECTORY_SEPARATOR)+1);
                 if(!is_dir($dir)){
                     @mkdir($dir, 0755, true) or die("Unable to create $dir\n");
                 }
                 if(strlen(trim($fichier)) > 0){
                     $return = @file_put_contents($dir."/".$fichier, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                     if($return === false){
                         die("Unable to write file $dir/$fichier\n");
                     }
                 }
             }else{
                 file_put_contents($fichier, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
             }
         }
     }else{
         echo "Unable to open zip file\n";
     }
 }



 }
 else
 {

  $trajet = 'Images/'.$fold.'/'.$adresse.'.'.$extension_upload.'';



if(move_uploaded_file($_FILES['fichier']['tmp_name'],$trajet))
{

$envoyefichier = 'ok';





}//fin de l'envoie
}

}//fin de traitement de l'envoie

}
}



}//fin du si formulaire envoy&eacute;













//=================================================traite les donn&eacute;e du formulaire decriptif  ===========================================


$message = 'erreur';


$titre = htmlspecialchars($_POST['titre']);//titre
$descri = htmlspecialchars($_POST['descri']);//descri


if (empty($titre) OR empty($descri))
{
$notification = 'Tout les champs doivent &ecirc;tre remplis.';
}



elseif(!preg_match("#^[a-zA-Z0-9]{1}#", $descri))
{
$notification = 'Vous ne pouvez commencer votre Question par un espace.';
}


elseif(!preg_match("#^.{3,}#", $descri))
{
$notification = 'Votre Qestion doit avoir une longueur minimum de 10 caracth&egrave;res.';
}


elseif(!preg_match("#^[a-zA-Z0-9]{1}#", $titre))
{
$notification = 'Votre Titre ne peut pas commencer par un espace.';
}

elseif(!preg_match("#^.{2,}#", $titre))
{
$notification = 'Votre Titre doit avoir une longueur minimum de 2 caracth&egrave;res.';
}





else//si aucune erreur
{



//verifier si d&eacute;ja envoy&eacute; !
$req = $basedonnees ->query('SELECT titre, contenu FROM lien');

while($meme = $req ->fetch())
{

if($titre == $meme['titre'] AND $decri == $meme['contenu'])
{
$notification = 'Ce message existe d&eacute;jà sur le forum.';
}

}
$req->closeCursor();




if(!isset($notification))//si c'est tjrs bon
{


$envoyetxt= 'ok';

//postage du message



}




//============================================================================  traite les donn&eacute;e du texte ==============================
$text= 'aucun';

$titretexte = htmlspecialchars($_POST['titretexte']);
$texte = htmlspecialchars($_POST['texte']);
if(!empty($titretexte))
{


$text = 'erreur';
if(!preg_match("#[a-zA-Z0-9&eacute;&egrave;à]#", $titretexte))
{
$notification = 'Le titre de votre texte facultatif ne doit pas contenir de metacaracth&egrave;re.';
}
elseif(!preg_match("#[a-zA-Z0-9&eacute;&egrave;à]#", $texte))
{
$notification = 'Votre texte facultatif ne doit pas contenir de metacaracth&egrave;re.';
}
else
{

$envoyefacult = 'ok';
}


}



















}








//=============================================================================envoie tout sur les base de donner et dossier
if(!isset($notification))
{






if(isset($envoyetxt))
{



$req = $basedonnees->prepare('INSERT INTO lien(proprietaire, sujet, titre, contenu, datecreation) VALUES (:proprietaire, :sujet, :titre, :contenu, NOW())');
$req->execute (array(
		'proprietaire' => $_SESSION['proprietaire'],
		'sujet' => $var1,
		'titre' => $titre,
		'contenu' => $descri

		));





$message = 'succes';
}









$bdd2 = $basedonnees->prepare('SELECT id FROM lien WHERE titre = ? AND contenu = ?');
$bdd2->execute(array($titre, $descri));
while($bdd2_i = $bdd2->fetch())
{
$rick = $bdd2_i['id'];
}
$bdd2->closeCursor();









if(isset($envoyefichier))//fichier
{





$bdd3 = $basedonnees->prepare('INSERT INTO fichier (id_lien, titrefichier, streaming, size, compteur, datecreation) VALUES (:id_lien, :titrefichier, :streaming, :size, :compteur, NOW())');
$bdd3->execute(array(
					'id_lien' => $rick,
					'titrefichier' => $titrefichier,
					'streaming' => $chemin,
					'size' => $_FILES['fichier']['size'],
					'compteur' => '0'
					));

$transf = 'succ&eacute;s';
}








if(isset($envoyefacult))
{

$new = $basedonnees->prepare('INSERT INTO fiche(id_lien, proprietaire, titre, contenu, datecreation) VALUES(:idlien, :proprietaire, :titre, :contenu, NOW())');
$new->execute(array(
					'idlien' => $rick,
					'proprietaire' => $_SESSION['proprietaire'],
					'titre' => $titretexte,
					'contenu' => $texte
					));




$text = 'succes';




}



































$notification = 'Message: '.$message.';  Transfert: '.$transf.';  Texte: '.$text.'';
}
}
}
}








$titre = 'Partage de fichier';
include('head.php');

?>

</head><body  onload="javascript:change_onglet('<?php echo $_SESSION['songletchat'];?>');">

<?php

include('menu.php');
?>



<div id="contenu">


<p><center>Bienvenue sur la page d'upload de fichier du site</center></p>
<p><center>Tout les fichiers que vous souhaitez upload ne doivent pas d&eacute;passer 20Mo.</center></p>






<?php

//diferente page
$theme = htmlspecialchars($_GET['theme']);


if(!isset($_GET['theme']))
{
$notification = 'adresse invalide.';

echo 'Il n\'y a rien a affich&eacute; ici.';
}
elseif(!preg_match("#^dm$|^cour$|^revision$|^ti$#", $theme))
{
$notification = 'adresse invalide.';

echo 'Il n\'y a rien a affich&eacute; ici.';

}
else
{

//traite les donn&eacute;e li&eacute; à la page
$var1 = $theme;











//pagination
$table = 'lien';
$pageweb = 'partage.php?theme='.$var1.'';
include('pagination.php');


//recherche des donn&eacute;e de tite....de la page
$papa = $basedonnees -> prepare('SELECT l.id id, l.titre titre, l.contenu contenu, i.avatar avatar, i.avatarproportion avatarproportion, i.prenom prenom, i.nom nom, i.mail mail, DATE_FORMAT(l.datecreation, \'%d / %m / %y - %Hh%imin%ss\') AS date FROM lien l INNER JOIN  inscrit i ON l.proprietaire = i.id WHERE sujet = :sujet ORDER BY l.datecreation DESC LIMIT '.$debut.', 5');
$papa->execute(array('sujet' => $var1));
while ($maman = $papa->fetch())
{




//compter le nombre de commentaire par boucle
$com = $basedonnees->prepare('SELECT COUNT(*) AS nbrcom FROM liencommentaire WHERE idlien = ? ');
$com ->execute(array($maman['id']));
$comment = $com->fetch();
$com ->closeCursor();





//s'occuper des liens
$traite = nl2br($maman['contenu']);
$traite = preg_replace('#{(.+);(.+)}#i','<a href="$1" target="_blank">$2</a>' , $traite);











//commande pour les lien du fichier

$bdd4 = $basedonnees -> prepare('SELECT titrefichier, streaming, size, compteur FROM fichier WHERE id_lien = ? ');
$bdd4->execute(array($maman['id']));

while ($fich = $bdd4->fetch())
{



$titrefichier = $fich['titrefichier'];
$size = $fich['size'] / 1000;
$size = round($size);
$acces = 'Images/telechargement.php?type='.$var1.'&fichier='.$fich['streaming'].'';
$compteur = $fich['compteur'];


}
$bdd4 ->closeCursor();
//les donn&eacute;e du fichier sont stock&eacute; dans des variables









$ficheur = $basedonnees -> prepare('SELECT id,titre, compteur FROM fiche WHERE id_lien = ? ');
$ficheur->execute(array($maman['id']));

while ($anale = $ficheur->fetch())
{



$titretexte = $anale['titre'];
$accestexte = $anale['id'];
$compteurtexte = $anale['compteur'];


}
$ficheur ->closeCursor();








//afichage du tableau pour chaque commentaire
?>









<table class="tableaulien">

<tr>
<td colspan="2" style="width: 15%;"><?php echo $maman['date']; ?></td>
<td colspan="3" style="width: 75%; font-size: 20px; font-weight: bold;"><?php echo htmlspecialchars($maman['titre']); ?></td>
<td rowspan="3"><a href="liencommentaire.php?theme=<?php echo $var1;?>&id=<?php echo htmlspecialchars($maman['id']);?>"><em>Afficher commentaire</em>(<?php echo $comment['nbrcom'];?>)</a></td>

</tr>

<tr>
<td rowspan="2" style="height: 70px; width: 70px;"><a onclick="new MaxBox(this, '639', '356'); return false;" href="Images/avatars/<?php echo $maman['avatar'];?>"><img height="<?php echo ($maman['avatarproportion']*70);?>" width="70" src="Images/avatars/<?php echo $maman['avatar'];?>"/></a></td>
	<td style="font-style: italic; height: 35px; width: 15%;"><?php echo htmlspecialchars($maman['nom']); ?></td>
	<td colspan="3" rowspan="2"><?php echo $traite; ?></td>
</tr>
<tr>

	<td style="height: 35px; width: 15%;"><?php echo htmlspecialchars($maman['prenom']); ?></td>

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

<p></p>









<?php
}
$papa->closeCursor();


//pagination de bas de page
$table = 'lien';
$pageweb = 'partage.php?theme='.$var1.'';
include('pagination.php');

?>






























<p></p>

<div>

<form method="post" action="partage.php?theme=<?php echo $var1;?>" enctype="multipart/form-data">

<table class="tableaufichier" >




<tr>
<td style="background-color: #e0f5fb;" colspan="2"><h5>Votre texte</h5></td>

</tr>
<tr>
<td style="width: 50%;"><label for="titre">Titre</label>:</td><td style="width: 50%;"><input id="titre" type="text" name="titre" required/></td>
</tr>

<tr>
<td  style="width: 50%;"><label for="descri">Desciption</label>:</td><td style="width: 50%;"> <textarea style="background-color: #e0f5fb;" id="descri" name="descri" rows="5"  required></textarea/></td>
</tr>




<tr>
<td style="background-color: #e0f5fb;" colspan="2"><h5>Joindre un fichier (facultatif)</h5></td>

</tr>
<tr>
<td style="width: 50%;"><label for="titre">Titre du fichier</label>:</td><td style="width: 50%;"><input id="titre" type="text" name="titrefichier"/></td>
</tr>

<tr>
	<td><label for="fichier">Choisir le fichier</label>:</td><td><input id="fichier" type="file" name="fichier"/></td>
</tr>

<tr>
<td style="background-color: #e0f5fb;" colspan="2"><h5>Joindre un texte editable (facultatif)</h5></td>

</tr>
<tr>
<td style="width: 50%;"><label for="titretexte">Titre du Text</label>:</td><td style="width: 50%;"><input id="titretexte" type="text" name="titretexte"/></td>
</tr>

<tr>
<td><label for="texte">Votre texte</label>:</td><td><textarea style="background-color: #e0f5fb;" id="texte" name="texte" rows="10"></textarea/></td>
</tr>
<tr>

     <td style="background-color: #e0f5fb;"  colspan="2"><input type="submit" name="envoyer" value="Envoyer" /></td>

</tr>




</table>

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
