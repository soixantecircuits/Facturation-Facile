<?php //require_once('../connection/connect_dl_blog.php');
require_once('ct-db_connect.php');


// *** Validate request to login to this site.

session_start();



$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($accesscheck)) {

  $GLOBALS['PrevUrl'] = $accesscheck;

  session_register('PrevUrl');

  $_SESSION['PrevUrl']=$accesscheck;

}



if (isset($_POST['log'])) {

  $loginUsername=$_POST['log'];

  $password=md5($_POST['pass']);

  $MM_fldUserAuthorization = "";

  $MM_redirectLoginSuccess = "index.php";

  $MM_redirectLoginFailed = "erreur.php";

  $MM_redirecttoReferrer = false;
  
  mysql_select_db("comptabilite", $connection);  

  $LoginRS__query=sprintf("SELECT log, pass FROM administrateurs WHERE log='%s' AND pass='%s'",

    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
    
  $LoginRS = mysql_query($LoginRS__query, $connection) or die(mysql_error());

  $loginFoundUser = mysql_num_rows($LoginRS);

  if ($loginFoundUser) {

     $loginStrGroup = "";

    //declare two session variables and assign them

    $GLOBALS['MM_Username'] = $loginUsername;

    $GLOBALS['MM_UserGroup'] = $loginStrGroup;	      



    //register the session variables

    session_register("MM_Username");

    session_register("MM_UserGroup");

	$_SESSION['MM_Username']=$loginUsername;

	$_SESSION['MM_UserGroup']=$loginStrGroup;



    if (isset($_SESSION['PrevUrl']) && false) {

      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	

    }

    header("Location: " . $MM_redirectLoginSuccess );

  }

  else {

    header("Location: ". $MM_redirectLoginFailed );

  }

}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>Facturation Facile v0.1 - Soixante circuits - Accès à l'administration</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="robots" content="noindex,nofollow" />
<link rel="stylesheet" type="text/css" href="css/comptabilite.css"/>

</head>

<body>

<div id="conteneurgeneral">



  <div id="barretitre">

  <form name="form1" id="presente2" method="POST" action="<?php echo $loginFormAction; ?>">

 

 <p>identifiant:</p><input type="text" name="log" /><br />

   

  <p>mot de passe:</p> <input type="password" name="pass" />

  <input class="btn"  type="submit" name="Submit" value="OK">

  </form>

  <div id="presente">

  <?php  //echo  ($bonjour[$rand_keys[0]]) ;?>

</div>

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

