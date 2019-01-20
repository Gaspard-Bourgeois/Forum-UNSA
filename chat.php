<?php
include('stock.php');
?>
<div id="monchat">
<div id="all_onglets">

         <div class="onglets">
<span class="onglet_0 onglet" id="onglet_discussion" onclick="javascript:change_onglet('discussion');">Discussion</span>
<span class="onglet_0 onglet" ><form method="POST" name="Choix"><select name="Liste" onChange="change_onglet()" id="sujet">
																														<option class="onglet_0 onglet" id="onglet_naviguer" value="naviguer">!Agenda!</option>
																														<option class="onglet_0 onglet" id="onglet_descriptif" value="decriptif">En rapide</option>
																														<option class="onglet_0 onglet" id="onglet_francais" value="francais">Lundi</option>
																														<option class="onglet_0 onglet" id="onglet_espagnol" value="espagnol">Mardi</option>
																														<option class="onglet_0 onglet" id="onglet_math" value="math">Mercredi</option>
																													</select></form></span>
<span class="onglet_0 onglet" id="onglet_connecter" onclick="javascript:change_onglet('connecter');">Connectés</span>
<span class="onglet_0 onglet" id="onglet_fermer" onclick="javascript:change_onglet('fermer');">x</span>


         </div>


        <div class="contenu_onglets">
             <div class="contenu_onglet" id="contenu_onglet_discussion">

<div id="chat">


<?php

//=========================================================   envoye des donnée

if (isset($_POST['chat']))
{

$message = htmlspecialchars($_POST['message']);



if(empty($message))
{
echo 'Votre message est vide';
}
elseif(!preg_match("#[^\[\]]*$#", $message))
{
echo 'Les crochets sont interdits';
}
else
{

$verif = $basedonnees->query('SELECT message FROM chat ORDER BY datecreation');
while($boucle = $verif->fetch())
{
$dernier = $boucle['message'];
}
$verif->closeCursor();

if($message == $dernier)
{
echo 'Déja poster';
}
else
{




$tac = $basedonnees->prepare('INSERT INTO chat (proprietaire, message, heure, datecreation) VALUES(:proprietaire, :message, NOW(), NOW())');
$tac ->execute(array(
					'proprietaire' => $_SESSION['proprietaire'],
					'message' => $message
					));








}

}

}







//=================================================   recuperation des nouvels données


$chat = $basedonnees->prepare('SELECT i.prenom prenom, i.avatar avatar, c.message message, DATE_SUB(c.datecreation, INTERVAL 15 MINUTE) AS date FROM chat c INNER JOIN inscrit i ON i.id = c.proprietaire ORDER BY c.datecreation');
$chat->execute(array());



?>
<div id="chat2">
<table>
<?php
while($chatte = $chat->fetch())
{
$datecorrige = preg_replace("#([0-9]{4}[-][0-9]{2}[-][0-9]{2}[ ]{1})()#", '$2', $chatte['date']);
?>
<tr>
<td><label for="mes"><img src="Images/avatars/<?php echo $chatte['avatar'];?>" height="15" width="15"/></label></td>
<td><label for="mes"><?php echo $chatte['prenom'];?></label>:</td>
<td colspan="2"><?php echo $chatte['message'];?></td>
<td><?php echo $datecorrige;?></td>
</tr>


<?php
}

$chat->closeCursor();

?>
</table>
</div>
<table>
<form method="POST">
<tr style="background-color: #acded5; display: block; margin-left: 40px; ">
<td><label for="mes"><img src="Images/avatars/<?php echo $_SESSION['avatar'];?>" height="20" width="20"/></label></td>
<td><label for="mes"><?php echo $_SESSION['prenom'];?></label>:</td>
<td><input type="text" style="background-color: #acded5; padding-bottom: 0px;" id="mes" name="message" maxlenght="200" /></td>
<td><input type="submit" size="30" style="background-color: #ddf0ed;" name="chat" value="envoyer"/></td>
</tr>




</form>
</table>
</div>





</div>
			<div class="contenu_onglet" id="contenu_onglet_descriptif">
			<p>En attente</p>

			</div>

             <div class="contenu_onglet" id="contenu_onglet_math">

			<p>En cour de construction...</p>
			 </div>

			              <div class="contenu_onglet" id="contenu_onglet_francais">

			 <p>En cour de construction...</p>
			 </div>

			              <div class="contenu_onglet" id="contenu_onglet_espagnol">

			 <p>En cour de construction...</p>
			 </div>


             <div class="contenu_onglet" id="contenu_onglet_connecter">

<div id="chat3">
	<table>
<?php
$nowh = date('H');
$nowi = date('i');
$now = $nowh*60 + $nowi;
$nowlimit = $now - 5;


$conecteur = $basedonnees->query('SELECT prenom, nom, avatar, DATE_FORMAT(actif, \'%H\') AS heure, DATE_FORMAT(actif, \'%i\') AS minute FROM inscrit ORDER BY actif');

while($vert = $conecteur->fetch())
{
$bddnow = $vert['heure']*60 + $vert['minute'];


?>
<tr>
<td><img src="Images/<?php if($now >= $bddnow AND $nowlimit < $bddnow){ echo 'connect_on.gif'; } else{ echo 'connect_off.gif'; }?>" height="15" width="15" /></td>
<td><img src="Images/avatars/<?php echo $vert['avatar'];?>" height="15" width="15"/></td>
<td><?php echo $vert['nom'];?></td>
<td><?php echo $vert['prenom'];?></td>
</tr>


<?php
}
$conecteur->closeCursor();
?>
</table>
</div>



































        </div>
		<div class="contenu_onglet" id="contenu_onglet_fermer" style="background-color: transparent; height: 0px; padding-top: 0px; padding-bottom: 0px;">



		</div>
     </div>
	 </div>
<?php

$test = $_SESSION['chat'];

?>
     <script type="text/javascript">
         //<!--
                 var anc_onglet = 'discussion';
                 change_onglet(anc_onglet);
         //-->
         </script>

<script>
element = document.getElementById('chat2');
element.scrollTop = element.scrollHeight;
</script>

<script>
element = document.getElementById('chat3');
element.scrollTop = element.scrollHeight;
</script>

</div>
