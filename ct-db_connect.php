<?php	
	require_once('ct-config.php');
	$connection = mysql_pconnect(DB_HOST, DB_USER, DB_PASSWORD) or die("erreur de connexion au serveur");
	mysql_select_db(DB_NAME, $connection) or die("erreur de connexion a la base de donnees");
	mysql_query("SET NAMES utf8");
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>
