<?php



// $emmeteur
// $sujet
// $mail
// $envoieV
// $envoieX



if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}



//=====Cr&eacute;ation de la boundary
$boundary = "-----=".md5(rand());
//==========



//=====Cr&eacute;ation du header de l'e-mail.
$header = "From: \"".$emmeteur."\"<forum-unsa.fr@gmail.com>".$passage_ligne;
$header.= "Reply-to: \"Eviter de r&eacute;pondre\" <forum-unsa.fr@gmail.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Cr&eacute;ation du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========






if(mail($mail, $sujet, $message, $header))
{
$notification = $envoieV;
}

else
{
$notification= $envoieX;
}
?>
