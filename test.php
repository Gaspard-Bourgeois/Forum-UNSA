<?php

$chataide = 'google.fr/question.php?untest=2&reference=zesf';

if(!preg_match("#unsa#", $chataide))
{

$chataide = 'le lien doit se trouver sur le site';
}
else
{
$chataide = preg_replace("#(.+)/([a-z&20]+).php#", "$2.php", $chataide);

if(preg_match("#/#", $chataide))
{
$chataide = 'Erreur dans le lien';

}
else
{



}
}



			 
?>