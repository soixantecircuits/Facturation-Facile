<?php
require_once('ct-db_connect.php');


// *** Validate request to login to this site.

session_start();

$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($accesscheck)) {

  $GLOBALS['PrevUrl'] = $accesscheck;

  $_SESSION['PrevUrl']=$accesscheck;

}



if (isset($_POST['log'])) {

  $loginUsername=$_POST['log'];

  $password=md5($_POST['pass']);

  $MM_fldUserAuthorization = "";

  $MM_redirectLoginSuccess = "index.php";

  $MM_redirectLoginFailed = "erreur.php";

  $MM_redirecttoReferrer = false;

  //mysql_select_db("comptabilite", $connection);
  $link->select_db(DB_NAME);


  $LoginRS__query = sprintf("SELECT log, pass FROM administrateurs WHERE log='%s' AND pass='%s'",

    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password));

//  $LoginRS = mysql_query($LoginRS__query, $connection) or die(mysql_error());
  $LoginRS = mysqli_query($link, $LoginRS__query) or die(mysql_error());

  //$loginFoundUser = mysql_num_rows($LoginRS);
  $loginFoundUser = mysqli_num_rows($LoginRS);

  if ($loginFoundUser) {

     $loginStrGroup = "";

    //declare two session variables and assign them

    $GLOBALS['MM_Username'] = $loginUsername;

    $GLOBALS['MM_UserGroup'] = $loginStrGroup;


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