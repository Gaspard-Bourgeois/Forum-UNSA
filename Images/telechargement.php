<?php
session_start();
if(isset($_SESSION['permission']))
{



if(!empty($_GET['fichier']))
{
$fichier = htmlspecialchars($_GET['fichier']);
$type = htmlspecialchars($_GET['type']);

if(!preg_match("#^music$|^fichier$|^film$|^ti$#", $type))
{

echo 'Votre lien est mort.';
}
else
{
$fichier = preg_replace("#%20#", '[ ]{1}', $fichier);
$folder = $type;

echo $fichier;
echo $folder;


 $chemin = $folder.'/' . $fichier;
 if(file_exists($chemin))
 {

//connexion BBD
try
{
$basedonnees = new PDO('mysql:host=localhost;dbname=basedonnees', 'root', 'arthur21');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
//fin connexion









$compteur = $basedonnees->prepare('UPDATE '.$folder.' SET compteur = (compteur + 1) WHERE streaming = ? ');
$compteur->execute(array($fichier));


  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename=' . basename($chemin));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  header('Content-Length: ' . filesize($chemin));
  readfile($chemin);
  exit;


}
else
{
echo 'adresse invalide.';

}
}
}
}
else
{
echo 'Vous devez être connecter pour acceder au fichier';
}
echo 'Erreur lors de la tentative de téléchargement';
?>
