<!DOCTYPE html>
<html>
    <head>
       <title>Mot de passe</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="css" href="login.css" />
	   <link rel="icon" href="Images/cadena.png" />



	</head>


<body>







<div id="full" class="" >
<div id="log_container" class="" >



<div class="logbox">
  <h2>Acc&egrave;s Unsa</h2>
  <form action="index.php" method="post" id="pass" class="form-password">
<table id="inscript">
  <tr>
    <td><input type="text" name="login" placeholder="login" maxlength="20" style="baground-color: #bdd76e;" autofocus/></td>
	</tr>
	<tr>
    <td><input type="password" name="mdp" /></td>
		</tr>

  <?php
  if(isset($wrong)){
  ?>
 <tr>
<td><div id="bad-pass"><?php echo $wrong; ?></div></td>
</tr>

<?php
}
?>




	<tr>
    <td><input type="submit" name="entrer" value="Entrer"/></td>
	</tr>

  </form>

</table>
<table id="inscript">

<tr>
<td><a href="inscription.php"><input class="inscription" type="button" value="Inscription" /></a></td>
</tr>
<tr>
<td><a href="mdpoublie.php"><input class="mdp" type="button" value="Mot de passe oubli&eacute;" /></a></td>
</tr>
</table>
</div>





</div>
</div>





</body>

</html>
