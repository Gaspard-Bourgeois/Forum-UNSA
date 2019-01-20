<?php
if(isset($_SESSION['proprietaire']))
{




$actu = $basedonnees->prepare('UPDATE inscrit SET actif = NOW() WHERE id= :proprietaire');
$actu->execute(array('proprietaire' => $_SESSION['proprietaire'] ));




}



?>

<!DOCTYPE html>
<html>
    <head>
       <title><?php echo $titre;?></title>
	   <link rel="icon" href="Images/icone.png" />
       <meta charset="ISO-8859-15">
	   <link rel="stylesheet" media="screen" type="text/css" title="css" href="css.css" />
	   <link rel="stylesheet" type="text/css" media="print" href="print.css" />
	   <?php
	   if(isset($notification))
		{
	   ?>
	   <script>
		alert('<?php echo $notification;?>');
		</script>
	   <?php
	   }
	   ?>




    <script type="text/javascript">  //Switcher entre plusieurs onglets du chat
         //<!--
                function change_agenda(nom)
                 {
		 				 						i = document.Choix.Liste.selectedIndex;
						if (typeof nom=='undefined'){
						var nom = document.Choix.Liste.options[i].value;


						}



						  document.getElementById('agenda_'+anc_agenda).classnom = 'agenda_0 agenda';
                         document.getElementById('agenda_'+nom).classNom = 'agenda_1 agenda';
                         document.getElementById('contain_agenda_'+anc_agenda).style.display = 'none';
                         document.getElementById('contain_agenda_'+nom).style.display = 'block';
                         anc_agenda = nom;

                 }

         //-->
         </script>







		     <script type="text/javascript">  //Switcher entre plusieurs onglets du chat
         //<!--
                function change_onglet(name)
                 {

                         document.getElementById('onglet_'+anc_onglet).className = 'onglet_0 onglet';
                         document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
                         document.getElementById('contenu_onglet_'+anc_onglet).style.display = 'none';
                         document.getElementById('contenu_onglet_'+name).style.display = 'block';
                         anc_onglet = name;
                 }

         //-->
         </script>










					<script type="text/javascript">
     //actualise le chat
     function updateChat() {
         if (document.getElementById('chat2')) { //si le <div> existe


             var XHR = new XMLHttpRequest();
             XHR.open('GET', 'update_chat.php');
             XHR.onreadystatechange = function() {
                 if(XHR.readyState == 10) {
                     var msg = XHR.responseText;
                     if (msg != '') { //si la r&eacute;ponse du script n'est pas vide
                         document.getElementById('chat2').innerHTML = msg; //change le contain du <div>
                     }
                 }
             }
         XHR.send(null);
         }



     }

     //appelle la fontion updateChat() à intervalle de temps r&eacute;gulier
     function callUpdateChat() {
         updateChat(); //actualise le chat
         setTimeout("callUpdateChat()", 2000); //appelle la fonction toutes les 20 secondes
     }
 </script>





 <script type="text/javascript">
     callUpdateChat() //premier appel de la fonction

element = document.getElementById('chat2');
element.scrollTop = element.scrollHeight;

 </script>

