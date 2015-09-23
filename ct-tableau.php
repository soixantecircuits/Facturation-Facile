<?php
	include 'ct-db_connect.php';
	$selected_month = isset($_GET['mois']) ? $_GET['mois'] : CURR_MONTH;
	$selected_year = isset($_GET['annee']) ? $_GET['annee'] : CURR_YEAR;

	// Retreive totals compte and caisse
	$query = "SELECT * FROM comptes";
	//$result = mysql_query($query) or die('Erreur sur la requète : '.$query.'<br/>'.mysql_error());
  $result = mysqli_query($link, $query);

	while( $row = mysqli_fetch_array($result, MYSQLI_ASSOC) )
	{
    	if ( $row['compte'] == 'Compte' )
        	$compte_banque = $row['montant'];
    	else if ( $row['compte'] == 'Caisse' )
        	$compte_caisse = $row['montant'];
	}

	echo '
	<div id="resume">
	<table>
        <tr><td> Banque </td><td id="total_banque">'.$compte_banque.' E</td></tr>
        <tr><td> Caisse </td><td id="total_caisse">'.$compte_caisse.' E</td></tr>
    </table>
    </div>';

	// Display month and year selection
	echo '
	<div>
	<ul>
	  <li>
        <select name="a_venir" onchange="operations_selection();">
          <option value=0>Op&eacute;rations</option>
          <option value=1>Op&eacute;rations &agrave; venir</option>
        </select>
      </li>

	  <li>
        <select name="compte_choice" onchange="operations_selection();">
          <option selected="selected" value="Compte">Compte</option>
          <option value="Caisse">Caisse</option>
        </select>
      </li>

	  <li>
        <select name="mois" onchange="operations_selection();">';
        for ($i = 0; $i < 12; $i++)
        {
        	if ($i == CURR_MONTH)
          		echo'<option selected="selected" value='.$i.'>'.$mois[$i].'</option>';
          	else
          		echo'<option value='.$i.'>'.$mois[$i].'</option>';
        }
        echo'
        </select>
      </li>

      <li>
        <select name="annee" onchange="operations_selection();">';
        for ($i = CURR_YEAR; $i >= FIRST_YEAR; $i--)
        {
        	if ($i == (CURR_YEAR))
          		echo'<option selected="selected"value='.$i.'>'.$i.'</option>';
          	else
          		echo'<option value='.$i.'>'.$i.'</option>';
        }
        echo'
        </select>
      </li>
	</ul>
	</div>';

	include 'ct-tableau/ct-new-operation.php';
	include 'ct-tableau/ct-tab-operations.php';