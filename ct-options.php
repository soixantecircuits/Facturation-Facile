<?php
  include 'ct-db_connect.php';
  //-----------------------------------------------------------------------------------------------------
  // get the new facture number
  $query = "SELECT value FROM options";
  $result = mysql_query($query);
  $row = mysql_fetch_row($result);
  ?>
  <form id="options">
  <p><br/>Options pour l'application : </p>
  <ul>
    <li>Prochain numéro d'estimation : <input id="estimations_count" name="estimations_count" value="<?php echo $row[0];?>"/></li>
    <?php $row = mysql_fetch_row($result); ?>
    <li>Prochain numéro de devis : <input id="deviss_count" name="deviss_count" value="<?php echo $row[0];?>"/></li>
    <?php $row = mysql_fetch_row($result); ?>
    <li>Prochain numéro de facturation : <input id="factures_count" name="factures_count" value="<?php echo $row[0] ;?>"/></li>
    </ul>
    <input id="operation" name="operation" value="save_option" style="display:none"/>
    <input type="submit" class="btn" name="save-options" value="Save" id="save-options"/>
  </form>
