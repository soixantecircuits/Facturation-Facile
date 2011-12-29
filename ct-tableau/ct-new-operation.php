<table>
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
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<tr>
    <td class="date_operation"><input class="new_operation" id="date_operation_new" type="text" class=datepicker name="date_operation" /></td>
    <td class="date_facture"><input class="new_operation" id="date_facture_new" type="text" class=datepicker name="date_facture" value=<?php echo '"'.$today.'"'; ?> /></td>
    <td class="categorie"><select class="new_operation" id="categorie_new" name="categorie">
        <?php
            foreach($categories as $cat)
            {
                echo'<option value="'.$cat.'">'.$cat.'</option>';
            }
         ?>
        </select></td>
    <td class="provenance"><input class="new_operation" id="provenance_new" type="text" name="provenance"/></td>
    <td class="objet"><input class="new_operation" id="objet_new" type="text" name="objet" /></td>
    <td class="compte"><select class="new_operation" id="compte_new" name="compte">
        <?php
            foreach($comptes as $com)
            {
                echo'<option value="'.$com.'">'.$com.'</option>';
            }
         ?>
        </select></td>
    <td class="debit"><input class="new_operation" id="debit_new" type="text" name="debit" /></td>
    <td class="credit"><input class="new_operation" id="credit_new" type="text" name="credit" /></td>
    <td class="credit_tva"><input class="new_operation" id="credit_tva_new" type="text" name="credit_tva" /></td>
    <td class="debit_tva"><input class="new_operation" id="debit_tva_new" type="text" name="debit_tva" /></td>
    <td class="remarques"><input class="new_operation" id="remarques_new" type="text" name="remarques" /></td>
    <td></td>
    <td><input class="button" id="ajouter_operation" type=submit value="Ajouter" /></td>
    </tr>
    </tbody>
</table>
