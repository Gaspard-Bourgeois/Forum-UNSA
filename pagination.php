<?php




//compter le nbr de reponse
$nbr = $basedonnees -> query('SELECT count(*) AS nombre FROM '.$table.'');


while($nombre = $nbr ->fetch())
{
$nbrbdd = $nombre['nombre'];
}

$nbr ->closeCursor();
//compter le nombre de valeur dans la table









$nbrpage = ceil($nbrbdd/5);


if (isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] >= 2 AND $nbrpage >= 2 AND $_GET['page'] <= $nbrpage)
{
$debut = $_GET['page'] * 5 - 5;
$pageactu = $_GET['page'];





}
else
{
$numero = 1;
$debut = 0;

}







?>
<h5>
<?php
if($numero == 1)
{
echo '<strong>1</strong>';
 }
else
{
echo '<a href="'.$pageweb.'">1</a>';

}

$numer = 2;
while($numer <= $nbrpage)
{
if ($numer != $pageactu)
{
echo ' - <a href="'.$pageweb.'&page='.$numer.'">'.$numer.'</a>';

}
else
{
echo ' - <strong>'.$numer.'</strong>';
}
$numer = $numer + 1;

}

?>
</h5>
