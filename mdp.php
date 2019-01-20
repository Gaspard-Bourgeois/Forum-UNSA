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
  <h2>Accès Site Web</h2>
  <form action="index.php" method="post" id="pass" class="form-password">
    <input type="text" name="login" placeholder="login" maxlength="20" style="baground-color: #bdd76e;" />
    <input type="password" name="mdp" />
    <input type="submit" name="entrer" value="Entrer"/>
  </form>
  <span class="bad-pass"  > </span><br />

<table id="inscription"> 
<tr>
<td><a href="inscription.php"><input class="inscription" type="button" value="Inscription" /></a></td>
</tr>
<tr>
<td><a href="mdpoublie.php"><input class="mdp" type="button" value="Mot de passe oublié" /></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
 $(document).ready(function() {
    $("input[type=password]")[0].focus();
});
</script>
  


</div>
</div>





</body>

</html>
