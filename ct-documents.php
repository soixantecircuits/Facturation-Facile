<?php
	include 'ct-db_connect.php';
	//-----------------------------------------------------------------------------------------------------
	// get the new facture number
	$query = "SELECT number, name, resume, id FROM factures ORDER BY id DESC";
	//$result = mysql_query($query);
	$result = mysqli_query($link, $query);
	//$row = mysql_fetch_row($result);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);

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
	//$result = mysql_query($query);
	$result = mysqli_query($link, $query);
	//$row = mysql_fetch_row($result);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);
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
	//$result = mysql_query($query);
	$result = mysqli_query($link, $query);
	//$row = mysql_fetch_row($result);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);
	$last_facture_number = sprintf('%06d',$row[0]);
	$last_year_facture = substr($last_facture_number, 0, 2);
	$last_month_facture = substr($last_facture_number, 2, 2);
	$last_number_facture = substr($last_facture_number, 4, 2);
	if ($last_month_facture == date('m') && $last_year_facture == date('y'))
		$EST_new_number_facture = date('y').date('m').sprintf('%02d',($last_number_facture + 1));
	else
		$EST_new_number_facture = date('y').date('m').'01';

	$query = "SELECT value from options WHERE name = 'estimations_count'";
	//$result = mysql_query($query);
	$result = mysqli_query($link, $query);
	//$row = mysql_fetch_row($result);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);

	$EST_new_number_facture = sprintf('%06d', $row[0]);

	$query = "SELECT value from options WHERE name = 'factures_count'";
	//$result = mysql_query($query);
	$result = mysqli_query($link, $query);
	//$row = mysql_fetch_row($result);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);

	$FAC_new_number_facture = sprintf('%06d', $row[0]);

	$query = "SELECT value from options WHERE name = 'deviss_count'";
	//$result = mysql_query($query);
	$result = mysqli_query($link, $query);
	//$row = mysql_fetch_row($result);
	$row = mysqli_fetch_array($result, MYSQLI_NUM);

	$DEV_new_number_facture = sprintf('%06d', $row[0]);

	//-----------------------------------------------------------------------------------------------------
	$section = $_GET['section'];

	if ($section == 'facture' || $section == 'devis' || $section == 'estimation')
	{
		$query = "SELECT number, name, resume, xml, total_ht, id FROM ".$section."s ORDER BY id DESC";

		//$result = mysql_query($query);
		$result = mysqli_query($link, $query);

		$factures = array();
		$names = array();
		$total_ht = array();
		$resumes = array();

		//$row = mysql_fetch_row($result);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);

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

		while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
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
		//$result = mysql_query($query);
		$result = mysqli_query($link, $query);
		//$row = mysql_fetch_row($result);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);

		$new_number_facture = sprintf('%06d', $row[0]);

		echo '<br/><a href="ct-operations.php?operation=new_document&type='.$section.'&number='.$new_number_facture.'">[+] '.$section.' '.$new_number_facture.'</a><br/>';

		?>
		<p>Export de masse (choisissez un interval et cliquez sur GO) :</p>
		<p><form id="bulk_action"><input id="type" type="text" style="display:none;" name="type" value="<?php echo $section ?>"/>Date de début : <input type="text" name="date_begin" class="date_picker" id="date_begin" size="20"/>, date de fin : <input type="text" name="date_end" class="date_picker" id="date_end" size="20"/> <a class="btn" id="bulk_action_button" href="ct-bulk.php?operation=bulk_export">GO</a></form></p>
		<?php

		$i = 0;
		?>
		<table>
    	<thead>
        <tr>
            <th><?php echo $section;?></th>
            <th>Résumé</th>
            <th>Budget(€)</th>
            <th>Opération</th>
        </tr>
    	</thead>
    	<tbody>

		<?php foreach ($factures as $number)
		{
			?>
			<tr class="document-line">
        			<td><a class="open_document" href="ct-document.php?type=<?php echo $section;?>&number=<?php echo $number;?>" title="<?php $resumes[$i];?>"><?php echo $section.' '.$number;?></a></td>
							<td><text class="main"><?php echo $names[$i];?>, <span style="width:100%;white-space: nowrap;overflow: hidden;-o-text-overflow: ellipsis;text-overflow:ellipsis;font-style:italic;"><?php echo $resumes[$i]; ?></span></text></td>
							<td class="price"><?php echo $total_ht[$i];?> € HT</td>
							<td class="action">
								<a class="delete_document" id="<?php echo $section.$number; ?>" onClick="return confirm(\'Supprimer ?\')">[-]</a>
								<a class="copy_document" id="<?php echo $section.$number.$new_number_facture; ?>"  href="ct-operations.php?operation=copy_document&type=<?php echo $section.'&old_number='.$number.'&number='.$new_number_facture;?>">[+]</a>
								<?php if ($section == 'devis')
								{?>
									<a class="transform_document" href="ct-operations.php?operation=transform_document&old_number=<?php echo $number.'&number='.$FAC_new_number_facture;?>&old_type=devis&type=facture">[facture]</a>
								<?php
								}
								else if ($section == 'estimation')
								{
								?>
									<a class="transform_document" href="ct-operations.php?operation=transform_document&old_number=<?php echo $number.'&number='.$DEV_new_number_facture;?>&old_type=estimation&type=devis">[devis]</a>
									<a class="transform_document" href="ct-operations.php?operation=transform_document&old_number=<?php echo $number.'&number='.$FAC_new_number_facture;?>&old_type=estimation&type=facture">[facture]</a>
								<?php
								}
							?>
							</td>
			</tr>
	<?php $i++;
		}
	}?>
	  	</tbody>
		</table>