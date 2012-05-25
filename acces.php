<?php
require_once('ct-auth.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Facturation Facile v0.1 - Soixante circuits - Accès à l'administration</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex,nofollow" />
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css"/>
  <link rel="stylesheet" type="text/css" href="css/comptabilite.css"/>
</head>

<body>
  <div id="conteneurgeneral">
    <div id="presente">
        <h1>Bienvenue ! <br/></h1>
        <h2>Simplifiez-vous la facturation en ligne avec un outil simple.</h2>
    </div>
    <div id="barretitre">
      <form name="form1" id="presente2" method="POST" action="<?php echo $loginFormAction; ?>">
        <p>Identifiant:</p><input type="text" name="log" /><br />
        <p>Mot de passe:</p> <input type="password" name="pass" />
        <input class="btn"  type="submit" name="Submit" value="OK">
      </form>
    </div>
    <div id="contenu"></div>
  </div>  

<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/js/jquery-color.js"></script>
<script type="text/javascript" src="/js/comptabilite.js"></script>
<script type="text/javascript" src="/js/date.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/js/tableau.js"></script>
</body>
</html>

