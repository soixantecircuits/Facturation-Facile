<table id="operations">
	<thead>
		<tr>
			<th class="date_operation">Date<br />op&eacute;ration</th>
			<th class="date_facture">Date<br />facture</th>
			<th class="categorie">Cat&eacute;gorie</th>
			<th class="provenance">Provenance</th>
			<th class="objet">Objet</th>
			<th class="compte">Compte</th>
			<th class="debit">D&eacute;bit</th>
			<th class="credit">Cr&eacute;dit</th>
			<th class="credit_tva">Cr&eacute;dit TVA</th>
			<th class="debit_tva">D&eacute;bit TVA</th>
			<th class="remarques">Remarques</th>
			<th class="editer_operation"></th>
			<th class="supprimer_operation"></th>
		</tr>
	</thead>
	<tbody id="tab_operations">
	<?php
	/*
	$selected_month = 1;
	$selected_year = 2009;
	
	$query = "SELECT * FROM operations WHERE MONTH(date_operation) = ".($selected_month+1)." AND YEAR(date_operation) = ".($selected_year)." ORDER BY date_operation DESC, id DESC";
    $donnees = mysql_query($query) or die('Erreur sur la requte : '.$query.'<br/>'.mysql_error());
    
	$i=0;
	while( $row = mysql_fetch_array($donnees) )
	{
        echo'
		<tr class="">
			<td>'.$row['date_operation'].'</td>
			<td>'.$row['date_facture'].'</td>
			<td>'.$row['categorie'].'</td>
			<td>'.$row['provenance'].'</td>
			<td>'.$row['objet'].'</td>
            <td>'.$row['compte'].'</td>
			<td class="currency">'.$row['debit'].' E</td>
			<td class="currency">'.$row['credit'].' E</td>
			<td class="currency">'.$row['credit_tva'].' E</td>
			<td class="currency">'.$row['debit_tva'].' E</td>
			<td>'.$row['remarques'].'</td>';
			
			echo'
			<td><button type="button" class="button"
		onclick="JavaScript: window.open(\'index.php?section=edit_operation&amp;id='.$row['id'].'\',\'Edit\',\'menubar=no, status=no, scrollbars=no, menubar=no, width=400, height=600\')"><span>Edit</span></td>
			
			<td><button type="button" class="button" onclick="JavaScript: if( confirm(\'Delete ?\')) { window.location.href=\'index.php?section='.$section.'&amp;action=del&amp;id='.$row['id'].'&mois='.$selected_month.'&amp;annee='.$selected_year.'\'}"><span>Delete</span></td>
		</tr>';
    }*/
    ?>
	</tbody>
	</table>
