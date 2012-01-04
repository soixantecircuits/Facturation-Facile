<?php require_once('restrict.php');?>
<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title> <?php if (isset($_GET['type'])) echo filter_var($_GET['type'], FILTER_SANITIZE_STRING); ?> - Soixante circuits</title>
<link rel="stylesheet" type="text/css" href="css/document.css"/>
<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css"/>
<link rel="stylesheet" type="text/css" href="css/comptabilite.css"/>
</head>


<body>

<div id="entete">

<a id="return" href="index.php">[RETURN]</a>
<text>|</text>
<text id="status"></text>
<form>
<table width="100%" >

<input type="hidden" name="type" value="'.$_GET['type'].'" />
<tr><td width="40.7%"></td><td width="23.3%">
<?php

require_once('ct-db_connect.php');

echo "<select name='current_status' id='current_status'>";

$query = 'SELECT status FROM '.$_GET['type'].'s WHERE number = '.$_GET["number"];

try{
	$result = mysql_query($query);			
	$firstquery = json_encode($result);
}catch(Exception $e){
	echo '{"success": false, "msg": '.json_encode("10").'}';
	exit;
}

$row = mysql_fetch_array($result);

$status_available = array('awaiting','sended','paied','overdue');
foreach($status_available as $value)
	if($row[0] == $value)
    echo "<option selected value=$value>$value</option>";
  else
  	echo "<option value=$value>$value</option>";
echo "</select>";
?>
</td><td width="36%" class="title"><text id="type"><?php echo $_GET['type']?></text>
N&#176;  <text id="number"><?php echo $_GET['number']?></text></td></td></tr>

<tr><td></td><td></td><td><input id="date" type="text" name="date" size="30" maxlength="30"/> <button class="btn" type="button" id="today">Today</button> </td></tr>
<tr><td></td><td></td><td>Affaire suivie par <input type="text" name="follower" size="20" maxlength="20"/> </td></tr>
<tr><td></td><td>31, rue Louis Blanc</td><td><input type="text" id="clients_name" name="name" style="width:50%;" maxlength="40"/> <input type="text" id="contact" name="contact" style="width:46%;" maxlength="40"/></td></tr>
<tr><td></td><td>75010 Paris</td><td><input type="text" id="address" name="address" style="width:100%;" maxlength="100"/></td></tr>
<tr><td></td><td>Tel. : 01 80 50 76 14</td><td><input type="text" id="zip" name="zip" style="width:15%;" maxlength="5"/> <input type="text" name="city" style="width:81%;" id="city" maxlength="31"/></td></tr>
<tr><td></td><td>www.soixantecircuits.fr</td><td><input id="country" type="text" name="country" style="width:100%;" maxlength="40"/></td></tr>

</table>

</div>

<button class="btn" type="button" id="getpdf">Get PDF</button>
<button class="btn" type="button" id="save">Save</button>
<input type="hidden" name="number" value="" />

<div id="options">
	<text class="title">Options</text><br />
	<div id="options_content">
		Montant journalier par défaut : <input name="default_val" type="text" id="default_val" value="450" style="width:5%;"/><span id="update_default_val">☑<span>
	</div>
</div>

<div id="resume">
<text class="title">DESCRIPTION</text><br />
<textarea name="resume" cols="117" rows="4"></textarea>
</div>

<input type="hidden" id="id" value="0" />
<input type="hidden" id="lineid" value="0" />
<div id="sections"></div>

<p id="addSection"><a href="#" onClick="addSection('TITRE'); return false;">[+]</a></p>

<div id="remise">
<table width="100%">
<tr><td width="57.4%" align=left class="linefirst">REMISE</td> <td width="8.9%" align=right class="linefirst"> </td> <td width="16.8%" align=right class="linefirst"><input id="remise" type="text" name="remise" value="" style="width:85%; text-align:right"/> </td> <td width="16.8%" align=right class="linefirst"> <text id="remise">0</text> &euro;</td></tr>
</table>
</div>

<div id="totaux">
<table width="100%">
<tr><input id="total_ht_auto" name="total_ht" type="text" value="null" style="display:none"/><td width="73.8%"></td><td width="13.1%" align=right>TOTAL HT</td> <td width="13.1%" align=right><text id="total_ht">0</text> &euro;</td> </tr>
<tr><td width="73.8%"></td><td width="13.1%" align=right>TVA (<input id="tva" type="text" name="tva" value="" style="width:40%; text-align:right"/>)</td> <td width="13.1%" align=right><text id="total_tva">0</text> &euro;</td> </tr>
<tr><td width="73.8%"></td><td width="13.1%" align=right>TOTAL TTC</td> <td width="13.1%" align=right><text id="total_ttc">0</text> &euro;</td> </tr>
<tr id="ligne_acompte"><td width="73.8%"></td><td width="13.1%" align=right>Acompte</td> <td width="13.1%" align=right><input id="pourc_acompte" type="text" name="pourc_acompte" value="" style="width:50%; text-align:right"/></td> </tr>
<tr id="ligne_net_a_payer"><td width="73.8%"></td><td width="13.1%" align=right>NET À PAYER</td> <td width="13.1%" align=right><text id="net_a_payer">0</text> &euro;</td> </tr>
<tr id="ligne_acompte_verse"><td width="73.8%"></td><td width="13.1%" align=right>Acompte versé</td> <td width="13.1%" align=right><input id="acompte_verse" type="text" name="acompte_verse" value="" style="width:50%; text-align:right"/> &euro;</td> </tr>
<tr id="ligne_montant_reste"><td width="73.8%"></td><td width="13.1%" align=right>RESTE À PAYER</td> <td width="13.1%" align=right><text id="montant_reste">0</text> &euro;</td> </tr>
</table>
</div>

<div id="conditions">
<textarea name="conditions" cols="80" rows="6"></textarea>
</div>

<div id="signature">
date et signature du client <br />(précédé de la mention «bon pour accord»)
</div>
</form>
</body>
</html>

<?php
	include 'ct-db_connect.php';
	
	$query = "SELECT number,xml FROM ".$_GET['type']."s WHERE number=".$_GET['number']."";
	
	$result = mysql_query($query);
	
	$row = mysql_fetch_row($result);
	
	echo '<script type="text/javascript">
	//var xmlstring = "'.str_replace('<br/>',' ',$row[1]).'";
	var xmlstring = "'.addslashes($row[1]).'"; // osx version
	</script>
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>	
	<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>	
	<script type="text/javascript" src="js/document.js"></script>';
	
	?>
