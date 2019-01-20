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
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="css" href="css.css" />
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

    <script type="text/javascript">
         //<!--
                function change_onglet(name)
                 {
				 						i = document.Choix.Liste.selectedIndex;
						if (i != 0 && typeof name=='undefined'){
						var name = document.Choix.Liste.options[i].value;
						}
                         document.getElementById('onglet_'+anc_onglet).className = 'onglet_0 onglet';
                         document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
                         document.getElementById('contenu_onglet_'+anc_onglet).style.display = 'none';
                         document.getElementById('contenu_onglet_'+name).style.display = 'block';
                         anc_onglet = name;
                 }

         //-->
         </script>
	</head>
