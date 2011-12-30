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

</head>

<style type="text/css">

#conteneurgeneral {position:relative; width:900px; height:100%;  margin-left:auto; margin-right:auto; z-index:2;top:0px;}

#barretitre {position:relative; width:835px; height:60px; margin-left:auto; margin-right:auto; left:0px; z-index:4;top:20px;}

#contenu {position:relative; width:835px; height:85%; overflow:auto; margin-top:3px; padding: 0px 3px 0 0; margin-right:auto; margin-left:auto; right:0px; background-color:#FFFFFF; z-index:1}



.titre {

	position: relative;

color: #ff0000;

	width: 760px;

	float: left;

	height: auto;

	text-align: left;

vertical-align:middle;

	font-family:  verdana, "Times New Roman", Times, serif;

	font-size: 14px;

	font-style:normal;

font-weight: bold;

/*background-image: url(images/trame.png);*/

}



.date {

	float: left;	

	color:#666;

	font-family:  verdana, "Times New Roman", Times, serif;

	font-weight: bold;

	font-size: 12px;

	font-style: normal;

	text-align: left;

}







p {

	float: left;

width:90px;

	text-align: left;

	font-family: verdana, "Times New Roman", Times, serif;

	font-size: 10px;

	color: #666666;

	

	margin: 0 0 0px 0;

}

input { margin:0; padding:0}

.btn
{
	font-family: "Andale Mono";
	font-size: 8pt;
	background:white;
	border:solid 1px gray
}



</style>

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

</body>

</html>

