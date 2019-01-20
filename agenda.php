<?php
include('stock.php');
?>

<div id="monagenda">
<div id="all_agendas" >




  <div class="agendas">
<span class="agenda_0 agenda" id="agenda_tout" onclick="javascript:change_agenda('tout');">Tout</span>
<span class="agenda_0 agenda" ><form method="POST" name="Choix"><select name="Liste" onChange="change_agenda()" id="sujet">
																														<option class="agenda_0 agenda" id="agenda_agenda" value="agenda">Jour...</option>


<?php
//requete sur la date
$id = $_SESSION['proprietaire'];
$agende = $basedonnees->prepare('SELECT actif, WEEKDAY(actif) AS jour, MONTH(actif) AS mois, LAST_DAY(actif) AS nbrmois, DATE_FORMAT(actif, \'%d\') AS day FROM inscrit WHERE id = ?');
$agende->execute(array($id));


//boucle pour la requete
while($ordre = $agende->fetch())
{
//definition des variable
$numerojour = $ordre['jour'];
$day = $ordre['day'];
$mois = $ordre['mois'];
$nbrmois = $ordre['nbrmois'];
$nbrmois = preg_replace("#([0-9]{4})[-]([0-9]{2})[-]([0-9]{2})#", "$3", $nbrmois);
$nbrmois = $nbrmois +1;
$nbrtour = 1;
//boucle pour le nombre de jour voulu
while($nbrtour <= 7)
{
$nbrtour = $nbrtour +1 ;
//traite les passage a de nouveau mois
if($day == $nbrmois)
{
$day = 1;
$mois = $mois +1;
if($mois == 13)
{
$mois = 1;
}
}
//traite le mois en toute lettre
if($mois == 1)
{
$moisagenda = 'Janvier';
}
elseif($mois == 2)
{
$moisagenda = 'Fevrier';
}
elseif($mois == 3)
{
$moisagenda = 'Mars';
}
elseif($mois == 4)
{
$moisagenda = 'Avril';
}
elseif($mois == 5)
{
$moisagenda = 'Mai';
}
elseif($mois == 6)
{
$moisagenda = 'Juin';
}
elseif($mois == 7)
{
$moisagenda = 'Juillet';
}
elseif($mois == 8)
{
$moisagenda = 'Aout';
}
elseif($mois == 9)
{
$moisagenda = 'Septembre';
}
elseif($mois == 10)
{
$moisagenda = 'Octobre';
}
elseif($mois == 11)
{
$moisagenda = 'Novembre';
}
elseif($mois == 12)
{
$moisagenda = 'Decembre';
}
//traite le jour en tout lettre
if($numerojour == 0)
{
$dateagenda = 'Lundi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 1)
{
$dateagenda = 'Mardi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 2)
{
$dateagenda = 'Mercredi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 3)
{
$dateagenda = 'Jeudi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 4)
{
$dateagenda = 'Vendredi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 5)
{
$dateagenda = 'Samedi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 6)
{
$dateagenda = 'Dimanche '.$day.' '.$moisagenda.'';
$numerojour = 0 ;
}
//fin du traitage des donn&eacute;e
//definition des variable a utiliser
$dateagendatiret = preg_replace("#[ ]#", "_", $dateagenda);
// $date agenda comporte la date en toute lettre avec les esaces
?>
<option class="agenda_0 agenda" id="agenda_<?php echo $dateagendatiret; ?>" value="<?php echo $dateagendatiret; ?>"><?php echo $dateagenda; ?></option>
<?php
//passer au jour suivant
$day = $day +1 ;
}
}
$agende->closeCursor();
//fin des boucles et de la connexion a base de don&eacute;&eacute;
?>





</select></form></span>

<span class="agenda_0 agenda" id="agenda_fermer" onclick="javascript:change_agenda('fermer');">x</span>


         </div>


        <div class="contain_agendas">











		<?php //================================================================================================================================GLOBAL    automatique!!!!!==========================================?>

             <div class="contain_agenda" id="contain_agenda_tout">


<?php

if(!empty($_POST['chatenvoyer']))
{

$chatmatiere = htmlspecialchars($_POST['chatmatiere']);
$chattype = htmlspecialchars($_POST['chattype']);
$chatsujet = htmlspecialchars($_POST['chatsujet']);
$chatreviser = htmlspecialchars($_POST['chatreviser']);
$chatdate1 = htmlspecialchars($_POST['chatdate1']);
$chatdate2 = htmlspecialchars($_POST['chatdate2']);
$chatdate3 = htmlspecialchars($_POST['chatdate3']);
$chataide = htmlspecialchars($_POST['chataide']);

if(!isset($chatmatiere) OR !isset($chattype) OR !isset($chatsujet) OR !isset($chatdate1) OR !isset($chatdate2) OR !isset($chatdate3) )
{
$rouge =  'Tout les champs doives &ecirc;tre remplis';
}
elseif(!preg_match("#^math$|^physique$|^svt$|^eps$|^histoiregeographie$|^francais$|^espagnol$|^allemand$#", $chatmatiere))
{
$rouge =  'Erreur dans le champs matiere.';
}
elseif(!preg_match("#.{2,}#",$chatsujet))
{
$rouge = 'Le sujet doit au moin &ecirc;tre compos&eacute; de 2 carcath&egrave;res.';
}
elseif(!preg_match("#^dm$|^exodm$|^exo$|^oral$|^dst$|^expo$#", $chattype))
{
$rouge = 'Erreur dans le champ Type';
}
elseif(!preg_match("#^[0-9]{2}$#", $chatdate1) OR !preg_match("#^[0-9]{2}$#", $chatdate2) OR !preg_match("#^[0-9]{4}$#", $chatdate3) )
{
$rouge = 'Date non-valide.';
}

else
{

if(isset($chataide))
{
if(!preg_match("#unsa#", $chataide))
{
$rouge = 'le lien doit se trouver sur le site';
}
else
{
$chataide = preg_replace("#(.+)/([a-z&20]+).php#", "$2.php", $chataide);

if(preg_match("#/#", $chataide))
{
$rouge = 'Erreur dans le lien';

}
}

}



if(!isset($rouge))
{

$chatdate = ''.$chatdate3.'-'.$chatdate2.'-'.$chatdate1.'';


$inserer = $basedonnees->prepare('INSERT INTO agenda (idlien, matiere, sujet, type, areviser, dateexecution) VALUES (:idlien, :matiere, :sujet, :type, :areviser, :date)');
$inserer->execute(array(
						'idlien' => $chataide,
						'matiere' => $chatmatiere,
						'sujet' => $chatsujet,
						'type' => $chattype,
						'areviser' => $chatreviser,
						'date' => $chatdate
						));

$rouge = 'Devoir ajout&eacute;.';
$inserer->closeCursor();

}
}

if(isset($rouge))
{
?>
<script>

alert('<?php echo $rouge; ?>');



</script>
<?php
}






}










?>















<?php

//===================================================  affichage des don&eacute;ee et de la date !
//requete sur la date
$id = $_SESSION['proprietaire'];
$agende = $basedonnees->prepare('SELECT actif, WEEKDAY(actif) AS jour, MONTH(actif) AS mois, LAST_DAY(actif) AS nbrmois, DATE_FORMAT(actif, \'%d\') AS day, DATE_FORMAT(actif, \'%Y\') AS annee FROM inscrit WHERE id = ?');
$agende->execute(array($id));


//boucle pour la requete
while($ordre = $agende->fetch())
{
//definition des variable
$numerojour = $ordre['jour'];
$day = $ordre['day'];
$mois = $ordre['mois'];
$nbrmois = $ordre['nbrmois'];
$nbrmois = preg_replace("#([0-9]{4})[-]([0-9]{2})[-]([0-9]{2})#", "$3", $nbrmois);
$nbrmois = $nbrmois +1;
$annee = $ordre['annee'];
$nbrtour = 1;
//boucle pour le nombre de jour voulu
while($nbrtour <= 7)
{
$nbrtour = $nbrtour +1 ;
//traite les passage a de nouveau mois
if($day == $nbrmois)
{
$day = 1;
$mois = $mois +1;
if($mois == 13)
{
$mois = 1;
}
}
//traite le mois en toute lettre
if($mois == 1)
{
$moisagenda = 'Janvier';
}
elseif($mois == 2)
{
$moisagenda = 'Fevrier';
}
elseif($mois == 3)
{
$moisagenda = 'Mars';
}
elseif($mois == 4)
{
$moisagenda = 'Avril';
}
elseif($mois == 5)
{
$moisagenda = 'Mai';
}
elseif($mois == 6)
{
$moisagenda = 'Juin';
}
elseif($mois == 7)
{
$moisagenda = 'Juillet';
}
elseif($mois == 8)
{
$moisagenda = 'Aout';
}
elseif($mois == 9)
{
$moisagenda = 'Septembre';
}
elseif($mois == 10)
{
$moisagenda = 'Octobre';
}
elseif($mois == 11)
{
$moisagenda = 'Novembre';
}
elseif($mois == 12)
{
$moisagenda = 'Decembre';
}
//traite le jour en tout lettre
if($numerojour == 0)
{
$dateagenda = 'Lundi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 1)
{
$dateagenda = 'Mardi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 2)
{
$dateagenda = 'Mercredi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 3)
{
$dateagenda = 'Jeudi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 4)
{
$dateagenda = 'Vendredi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 5)
{
$dateagenda = 'Samedi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 6)
{
$dateagenda = 'Dimanche '.$day.' '.$moisagenda.'';
$numerojour = 0 ;
}
//fin du traitage des donn&eacute;e
//definition des variable a utiliser
$dateagendatiret = preg_replace("#[ ]#", "_", $dateagenda);
$daterecherche = $annee.'-'.$mois.'-'.$day.'';
// $date agenda comporte la date en toute lettre avec les esaces
?>

<p><span class="mechantpapa" id="agenda_<?php echo $dateagendatiret;?>" onclick="javascript:change_agenda('<?php echo $dateagendatiret;?>');"><?php echo $dateagenda; ?></span></p>


<table id="tableautout">

<?php

$recherche = $basedonnees->prepare('SELECT idlien, matiere, sujet, type, areviser FROM agenda WHERE dateexecution = ?');
$recherche->execute(array($daterecherche));

while($actus= $recherche->fetch())
{
?>
<tr>
<td><?php echo $actus['matiere'];?>:</td>
<td><?php echo $actus['sujet'];?></td>
<td><i><?php echo $actus['type'];?></i></td>
<?php
if(!empty($actus['idlien']))
{
?>
<td><a href="<?php echo $actus['idlien'];?>"><input type="button" value="Aide" /></a></td>

<?php
}
?>



</tr>

<?php
}

$recherche->closeCursor();
?>

</table>





<?php
//passer au jour suivant
$day = $day +1 ;
}
}
$agende->closeCursor();
//fin des boucles et de la connexion a base de don&eacute;&eacute;
?>





</div>



<?php //================================================================================================================================journ&eacute;e automatique !!!!!!!!==========================================?>




<?php
//requete sur la date
$id = $_SESSION['proprietaire'];
$agende = $basedonnees->prepare('SELECT actif, WEEKDAY(actif) AS jour, MONTH(actif) AS mois, LAST_DAY(actif) AS nbrmois, DATE_FORMAT(actif, \'%d\') AS day, DATE_FORMAT(actif, \'%Y\') AS annee FROM inscrit WHERE id = ?');
$agende->execute(array($id));


//boucle pour la requete
while($ordre = $agende->fetch())
{
//definition des variable
$numerojour = $ordre['jour'];
$day = $ordre['day'];
$mois = $ordre['mois'];
$nbrmois = $ordre['nbrmois'];
$nbrmois = preg_replace("#([0-9]{4})[-]([0-9]{2})[-]([0-9]{2})#", "$3", $nbrmois);
$nbrmois = $nbrmois +1;
$annee = $ordre['annee'];
$nbrtour = 1;
//boucle pour le nombre de jour voulu
while($nbrtour <= 7)
{
$nbrtour = $nbrtour +1 ;
//traite les passage a de nouveau mois
if($day == $nbrmois)
{
$day = 1;
$mois = $mois +1;
if($mois == 13)
{
$mois = 1;
}
}
//traite le mois en toute lettre
if($mois == 1)
{
$moisagenda = 'Janvier';
}
elseif($mois == 2)
{
$moisagenda = 'Fevrier';
}
elseif($mois == 3)
{
$moisagenda = 'Mars';
}
elseif($mois == 4)
{
$moisagenda = 'Avril';
}
elseif($mois == 5)
{
$moisagenda = 'Mai';
}
elseif($mois == 6)
{
$moisagenda = 'Juin';
}
elseif($mois == 7)
{
$moisagenda = 'Juillet';
}
elseif($mois == 8)
{
$moisagenda = 'Aout';
}
elseif($mois == 9)
{
$moisagenda = 'Septembre';
}
elseif($mois == 10)
{
$moisagenda = 'Octobre';
}
elseif($mois == 11)
{
$moisagenda = 'Novembre';
}
elseif($mois == 12)
{
$moisagenda = 'Decembre';
}
//traite le jour en tout lettre
if($numerojour == 0)
{
$dateagenda = 'Lundi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 1)
{
$dateagenda = 'Mardi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 2)
{
$dateagenda = 'Mercredi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 3)
{
$dateagenda = 'Jeudi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 4)
{
$dateagenda = 'Vendredi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 5)
{
$dateagenda = 'Samedi '.$day.' '.$moisagenda.'';
$numerojour = $numerojour +1 ;
}
elseif($numerojour == 6)
{
$dateagenda = 'Dimanche '.$day.' '.$moisagenda.'';
$numerojour = 0 ;
}
//fin du traitage des donn&eacute;e
//definition des variable a utiliser
$dateagendatiret = preg_replace("#[ ]#", "_", $dateagenda);
$daterecherche = $annee.'-'.$mois.'-'.$day.'';
// $date agenda comporte la date en toute lettre avec les esaces
?>

<div class="contain_agenda" id="contain_agenda_<?php echo $dateagendatiret; ?>">






<p><?php echo $dateagenda; ?></p>

<table id="tableauagenda">

<?php

$recherche = $basedonnees->prepare('SELECT idlien, matiere, sujet, type, areviser FROM agenda WHERE dateexecution = ?');
$recherche->execute(array($daterecherche));

while($actus= $recherche->fetch())
{




?>
<tr>
<td><?php echo $actus['matiere'];?>:</td>
<td><i><?php echo $actus['type'];?></i></td>
<td><?php echo $actus['sujet'];?></td>
</tr>
<tr>
<td><?php echo $actus['areviser'];?></td>
<?php
if(!empty($actus['idlien']))
{
?>
<td><a href="<?php echo $actus['idlien'];?>"><input type="button" value="Aide" /></a></td>

<?php
}
?>
</tr>

<?php
}

$recherche->closeCursor();
?>

</table>

<form method="POST">
<fieldset><legend>Devoirs:</legend>
<table class="tableauagendaformulaire">

<tr>
<td><label for="chatdate">Date</label>:</td><td><input type="text" for="chatdate" name="chatdate1" maxlenght="2" size="3" value="<?php echo $day;?>" required />  /  <input type="text" for="chatdate" name="chatdate2" maxlenght="2" size="3" value="<?php echo $mois;?>" required />  /  <input type="text" for="chatdate" name="chatdate3" maxlenght="4" size="5" value="<?php echo $annee;?>" required /></td>
</tr>
<tr>
<td><label for="chatmatiere">Mati&egrave;re</label>:</td><td><select id="chatmatiere" name="chatmatiere">
															<option value="math">Math</option>
															<option value="physique">Physique</option>
															<option value="eps">EPS</option>
															<option value="svt">SVT</option>
															<option value="histoiregeographie">Histoire & Geo</option>
															<option value="espagnol">Espagnol</option>
															<option value="allemand">Allemand</option>
															<option value="francais">Francais</option></td>
</tr>

<tr>

<td><label for="chattype">Type</label>:</td><td><select id="chatype" name="chattype">
															<option value="dm">Devoir Maison</option>
															<option value="exodm">Exercice (ou dm)</option>
															<option value="exo">Exercice</option>
															<option value="oral">Oral</option>
															<option value="dst">DST</option>
															<option value="expo">Expos&eacute;</option></td>


</tr>


<tr>
<td><label for="chatsujet">Sujet</label>:</td><td><input type="text" for="chatsujet" name="chatsujet" maxlenght="50" size="20" required /></td>
</tr>

<tr>
<td><label for="chatreviser">A R&eacute;viser</label>:</td><td><textarea for="chatreviser" name="chatreviser" rows="4"></textarea></td>
</tr>
<tr>
<td colspan="2" style="color: red;"><center>Indiquer un corrig&eacute; ou une aide (facultatif)</center></td>
</tr>

<tr>
<td><label for="chataide">Aide (copier l'URL du fichier ou commentaire en question)</label>:</td><td><input id="chataide" name="chataide" type="text" name="numero" size="20" maxlenght="2"/></td>

</tr>


</fieldset>
<tr>
<td><input type="submit" name="chatenvoyer" value="Valider" /></td>
</tr>
</table>
</fieldset>
</form>

<span class="mechantpapa" id="agenda_tout" onclick="javascript:change_agenda('tout');">Retour au general</span>


</div>


<?php
//passer au jour suivant
$day = $day +1 ;
}
}
$agende->closeCursor();
//fin des boucles et de la connexion a base de don&eacute;&eacute;
?>




<?php //================================================================================================================================FIN des journee automatique==========================================?>

		<div class="contain_agenda" id="contain_agenda_fermer" style="background-color: transparent; height: 0px; padding-top: 0px; padding-bottom: 0px;">



		</div>
     </div>
	 </div>
<?php

$test = $_SESSION['chat'];

?>
     <script type="text/javascript">
         //<!--
                 var anc_agenda = 'tout';
                 change_agenda(anc_agenda);
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
