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
	</head>
