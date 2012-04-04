<?php	
	include 'ct-db_connect.php';
	//-----------------------------------------------------------------------------------------------------
	// get the new facture number
	$query = "SELECT number, name, resume, id FROM factures ORDER BY id DESC";
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

	//-----------------------------------------------------------------------------------------------------	
	// get the new devis number
	$query = "SELECT number, name, resume, id FROM deviss ORDER BY id DESC";
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

	//-----------------------------------------------------------------------------------------------------	
	// get the new estimation number
	$query = "SELECT number, name, resume, id FROM estimations ORDER BY id DESC";
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

	$query = "SELECT value from options WHERE name = 'estimations_count'";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);

	$EST_new_number_facture = sprintf('%06d', $row[0]);

	$query = "SELECT value from options WHERE name = 'factures_count'";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);

	$FAC_new_number_facture = sprintf('%06d', $row[0]);

	$query = "SELECT value from options WHERE name = 'deviss_count'";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);

	$DEV_new_number_facture = sprintf('%06d', $row[0]);

	//-----------------------------------------------------------------------------------------------------
	$section = $_GET['section'];
	
	if ($section == 'facture' || $section == 'devis' || $section == 'estimation')
	{
		$query = "SELECT number, name, resume, xml, total_ht, id FROM ".$section."s ORDER BY id DESC";
	
		$result = mysql_query($query);
		
		$factures = array();
		$names = array();
		$total_ht = array();
		$resumes = array();
	
		$row = mysql_fetch_row($result);

		$sxe = new SimpleXMLElement($row[3]);
		$node = $sxe->children();
		$resume	= $node[6]->resume_line;
	
		array_push($resumes, $resume);	
		array_push($factures, sprintf('%06d',$row[0]));
		array_push($names, $row[1]);
		array_push($total_ht, $row[4]);

		$number = sprintf('%06d',$row[0]);
		$last_year_facture = substr($number, 0, 2);
		$last_month_facture = substr($number, 2, 2);
		$last_number_facture = substr($number, 4, 2);
		
		while($row = mysql_fetch_row($result)){
			array_push($factures, sprintf('%06d',$row[0]));
			array_push($names, $row[1]);
			array_push($total_ht, $row[4]);
			
			$sxe = new SimpleXMLElement($row[3]);
			$node = $sxe->children();
			$resume	= $node[6]->resume_line;
			
			array_push($resumes, $resume);

			$number = sprintf('%06d', $row[0]);
		}


		/*
		if ($last_month_facture == date('m') && $last_year_facture == date('y'))
			$new_number_facture = date('y').date('m').sprintf('%02d',($last_number_facture + 1));
		else
			$new_number_facture = date('y').date('m').'01';*/

		$query = "SELECT value from options WHERE name = '".$section."s_count'";
		$result = mysql_query($query);
		$row = mysql_fetch_row($result);

		$new_number_facture = sprintf('%06d', $row[0]);
		
		echo '<br/><a href="ct-operations.php?operation=new_document&type='.$section.'&number='.$new_number_facture.'">[+] '.$section.' '.$new_number_facture.'</a><br/>';
		
		$i = 0;
		
		foreach ($factures as $number)
		{
			echo '<div class="document-line">';
			echo '<a class="open_document" href="ct-document.php?type='.$section.'&number='.$number.'" title="'.$resumes[$i].'">'.$section.' '.$number.'</a> ';
			echo '<text class="main">'.$names[$i].', <span style="width:100%;white-space: nowrap;overflow: hidden;-o-text-overflow: ellipsis;text-overflow:ellipsis;font-style:italic;">'.$resumes[$i].'</span></text> ';

			echo '<span class="price">'.$total_ht[$i].' € HT</span> ';

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
			echo '</div>';
			$i++;
		}
	}
	echo '<script type="text/javascript" src="js/jquery-color.js"></script>';
?>
