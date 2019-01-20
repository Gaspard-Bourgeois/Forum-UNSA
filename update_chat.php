<?php
     //connexion à la base de donn&eacute;e
     try {
$db = new PDO('mysql:host=localhost;dbname=basedonnees', 'root', 'arthur21');
     }
     catch (Exception $e) {
         die('Erreur : ' . $e->getMessage()); //impossible de se connect&eacute; à la base de donn&eacute;e
     }

     try {
$chat = $db ->prepare('SELECT i.prenom prenom, i.avatar avatar, i.avatarproportion avatarproportion, c.message message, DATE_SUB(c.datecreation, INTERVAL 15 MINUTE) AS date FROM chat c INNER JOIN inscrit i ON i.id = c.proprietaire ORDER BY c.datecreation');
$chat ->execute(array());



?>

<table>
<?php

         while($msg = $chat->fetch()) {
		 $largpropo = $msg['avatarproportion'];
		 $matcho = $largpropo*70;
		 $datecorrige = preg_replace("#([0-9]{4}[-][0-9]{2}[-][0-9]{2}[ ]{1})()#", '$2', $msg['date']);

?>

<tr>
<td><label for="mes"><img src="Images/avatars/<?php echo $msg['avatar'];?>" title="<?php echo $msg['avatar'];?>" alt="<?php echo $msg['avatar'];?>" height="<?php echo ($msg['avatarproportion']*15);?>" width="15" ></label></td>
<td><label for="mes"><?php echo $msg['prenom'];?></label>:</td>
<td style="background-color: #fef3cc;" colspan="2"><label for="mes"><?php echo $msg['message'];?></label></td>
<td><label for="mes"><?php echo $datecorrige;?></label></td>
</tr>

<?php
         }
		 ?>
</table>
<?php

$chat->closeCursor();
     }
     catch (Exception $e) {
         //impossible d'acc&eacute;der à la base de donn&eacute;e
     }
?>
