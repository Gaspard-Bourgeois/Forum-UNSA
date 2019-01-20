<?php
$fourbe = $basedonnees->query('SELECT i.prenom prenom, i.avatar avatar, c.message message, FORMAT_DATE(c.datecreation, \'%H %i\') AS date FROM chat c INNER JOIN inscrit i ON i.id = c.proprietaire ORDER BY c.datecreation');
?>
<div id="chat2">
<table>
<?php
while($chatte = $fourbe->fetch())
{
?>
<tr>
<td><label for="mes"><img src="Images/avatars/<?php echo $chatte['avatar'];?>" height="15" width="15"/></label></td>
<td><label for="mes"><?php echo $chatte['prenom'];?></label>:</td>
<td colspan="2"><?php echo $chatte['message'];?></td>
<td><?php echo $chatte['date'];?></td>
</tr>


<?php
}

$chat->closeCursor();

?>
