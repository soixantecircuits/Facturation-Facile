
<?php
	
	echo '<script type="text/javascript" src="js/operations.js"></script>';
	echo '<script type="text/javascript" src="js/comptabilite.js"></script>';
	
	
	include 'ct-db_connect.php';
	
	// get the new facture number
	$query = "SELECT number,name,resume FROM factures ORDER BY number DESC";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$last_facture_number = sprintf('%06d',$row[0]);
	$last_year_facture = substr($last_facture_number, 0, 2);
	$last_month_facture = substr($last_facture_number, 2, 2);
	$last_number_facture = substr($last_facture_number, 4, 2);
	if ($last_month_facture == date('m') && $last_year_facture == date('y'))
		$FAC_new_number_facture = date('y').date('m').sprintf('%02d',($last_number_facture + 1));
	else
		$FAC_new_number_facture = date('y').date('m').'01';
		
	// get the new devis number
	$query = "SELECT number,name,resume FROM deviss ORDER BY number DESC";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$last_facture_number = sprintf('%06d',$row[0]);
	$last_year_facture = substr($last_facture_number, 0, 2);
	$last_month_facture = substr($last_facture_number, 2, 2);
	$last_number_facture = substr($last_facture_number, 4, 2);
	if ($last_month_facture == date('m') && $last_year_facture == date('y'))
		$DEV_new_number_facture = date('y').date('m').sprintf('%02d',($last_number_facture + 1));
	else
		$DEV_new_number_facture = date('y').date('m').'01';
		
	// get the new estimation number
	$query = "SELECT number,name,resume FROM estimations ORDER BY number DESC";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$last_facture_number = sprintf('%06d',$row[0]);
	$last_year_facture = substr($last_facture_number, 0, 2);
	$last_month_facture = substr($last_facture_number, 2, 2);
	$last_number_facture = substr($last_facture_number, 4, 2);
	if ($last_month_facture == date('m') && $last_year_facture == date('y'))
		$EST_new_number_facture = date('y').date('m').sprintf('%02d',($last_number_facture + 1));
	else
		$EST_new_number_facture = date('y').date('m').'01';

	$section = $_GET['section'];
	
	if ($section == 'facture' || $section == 'devis' || $section == 'estimation')
	{
		$query = "SELECT number,name,resume FROM ".$section."s ORDER BY number DESC";
	
		$result = mysql_query($query);
		
		$factures = array();
		$names = array();
		$resumes = array();
		
		$row = mysql_fetch_row($result);
		array_push($factures, sprintf('%06d',$row[0]));
		array_push($names, $row[1]);
		array_push($resumes, $row[2]);
		$number = sprintf('%06d',$row[0]);
		$last_year_facture = substr($number, 0, 2);
		$last_month_facture = substr($number, 2, 2);
		$last_number_facture = substr($number, 4, 2);
		
		while($row = mysql_fetch_row($result)){
			array_push($factures, sprintf('%06d',$row[0]));
			array_push($names, $row[1]);
			array_push($resumes, $row[2]);
			$number = sprintf('%06d', $row[0]);
		}
		
		if ($last_month_facture == date('m') && $last_year_facture == date('y'))
			$new_number_facture = date('y').date('m').sprintf('%02d',($last_number_facture + 1));
		else
			$new_number_facture = date('y').date('m').'01';
		
		echo '<br/><a href="ct-operations.php?operation=new_document&type='.$section.'&number='.$new_number_facture.'">[+] '.$section.' '.$new_number_facture.'</a><br/>';
		//echo '<br/> <button class="btn new_document" id="'.$section.$number.'" type="button">New '.$section.'</button> <br/>';
		
		$i = 0;
		
		foreach ($factures as $number)
		{
			echo '<a class="open_document" href="ct-document.php?type='.$section.'&number='.$number.'" title="'.$resumes[$i].'">'.$section.' '.$number.'</a> ';
			echo '<text>'.$names[$i].'</text> ';

			echo '<a class="delete_document" id="'.$section.$number.'" onClick="return confirm(\'Supprimer ?\')">[-]</a>';
			echo '<a class="copy_document" id="'.$section.$number.$new_number_facture.'"  href="ct-operations.php?operation=copy_document&type='.$section.'&old_number='.$number.'&number='.$new_number_facture.'">[+]</a>';
			if ($section == 'devis')
			{
				echo '<a class="transform_document" href="ct-operations.php?operation=transform_document&old_number='.$number.'&number='.$FAC_new_number_facture.'&old_type=devis&type=facture">[facture]</a>';
			}
			else if ($section == 'estimation')
			{
				echo '<a class="transform_document" href="ct-operations.php?operation=transform_document&old_number='.$number.'&number='.$DEV_new_number_facture.'&old_type=estimation&type=devis">[devis]</a>';
				echo '<a class="transform_document" href="ct-operations.php?operation=transform_document&old_number='.$number.'&number='.$FAC_new_number_facture.'&old_type=estimation&type=facture">[facture]</a>';
				
			}

				
			echo '<br/>';
			
			$i++;
		}
	}
	?>
