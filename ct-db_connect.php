<?php
	require_once('ct-config.php');

  $link = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  /* Vérification de la connexion */
  if (mysqli_connect_errno()) {
      printf("Error while connecting: %s\n", mysqli_connect_error());
      exit();
  }

	//$connection = mysql_pconnect(DB_HOST, DB_USER, DB_PASSWORD) or die("erreur de connexion au serveur mysql");
	//mysql_select_db(DB_NAME, $connection) or die("erreur de connexion a la base de donnees");
	//mysql_query("SET NAMES utf8");
  if (!mysqli_set_charset($link, "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($link));
    exit();
  }