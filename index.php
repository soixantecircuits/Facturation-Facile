<?php require_once('restrict.php');?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Comptabilité - Soixante circuits</title>  
<link rel="stylesheet" type="text/css" href="css/comptabilite.css"/>
<link rel="stylesheet" type="text/css" href="css/datePicker.css"/>
<link rel="stylesheet" type="text/css" href="css/tableau.css"/>
</head>
<body>

<?php
echo '

<p>
<a href="'.$logoutAction.'">[LOGOUT]</a>
<text>|</text>
<text id="status"></text>
</p>

<text id="s" style="visibility:hidden">'.$_GET['section'].'</text>

<ul id="menu">
<li> <a class="menu_link" id="estimations" href="ct-documents.php?section=estimation">ESTIMATIONS</a> </li>
<li> <a class="menu_link" id="devis" href="ct-documents.php?section=devis">DEVIS</a> </li>
<li> <a class="menu_link" id="factures" href="ct-documents.php?section=facture">FACTURES</a> </li>
<li> <a class="menu_link" id="operations" href="ct-tableau.php?">OPÉRATIONS</a> </li>
</ul>

<div id="content">';
	$section = $_GET['section'];
	if ($section == 'facture' || $section == 'devis' || $section == 'estimation')
		include 'ct-documents.php';

echo '</div>'; ?>

<script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/js/comptabilite.js"></script>
<script type="text/javascript" src="/js/date.js"></script>
<script type="text/javascript" src="/js/jquery.datePicker.min-2.1.2.js"></script>
<script type="text/javascript" src="/js/tableau.js"></script>

<?php
echo'</body>';