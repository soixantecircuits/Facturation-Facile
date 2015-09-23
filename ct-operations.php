<?php
function sqlToDate($d){
		$TabMois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "décembre");
    $separate_date = explode('-', $d);
    return $separate_date[2]." ".$TabMois[$separate_date[1]-1]." ".$separate_date[0];
	}
?>
<?php
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	$operation = $_GET['operation'];
	switch ($operation) {
		case "save_option":
			require_once('ct-db_connect.php');
			try{
				$query = "UPDATE options SET value=".$_GET["deviss_count"]." WHERE name = 'deviss_count'";
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
				$query = "UPDATE options SET value=".$_GET["factures_count"]." WHERE name = 'factures_count'";
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
				$query = "UPDATE options SET value=".$_GET["estimations_count"]." WHERE name = 'estimations_count'";
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
				echo '{"success": true, "msg":"saved"}';
			}catch(Exception $e){
					echo '{"success": false, "msg": '.json_encode("10").'}';
					exit;
			}

			break;
		case "new_document": // OK
			header('Content-type: text/html');
			include 'ct-document.php';
			break;
		case "delete_document": // OK
			require_once('ct-db_connect.php');
			$datas = $_GET['datas'];
			$type = substr($datas, 0, -6);
			$number = substr($datas, -6, 6);

			$query = 'DELETE FROM '.$type.'s WHERE number='.$number.'';
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);
			if($result){
				echo '{"success": true, "msg": "'.$type.' deleted", "redirect":"'.$type.'"}';
			}else{
				echo '{"success": false, "msg": "'.$type.' not deleted", "redirect":"'.$type.'"}';
			}
			break;

		case "copy_document": // OK
			header('Content-type: text/html');
			require_once('ct-db_connect.php');
			$type = $_GET['type'];
			$number = $_GET['number'];
			$old_number = $_GET['old_number'];

			$query = "UPDATE ".$type." SET number=".$old_number.", xml=(SELECT xml FROM ".$type."s WHERE number=".$old_number.")";
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			$query = "INSERT INTO ".$type."s (number) VALUES (".$number.")";
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			$query = "UPDATE ".$type."s SET xml=(SELECT xml FROM ".$type." WHERE number=".$old_number.") WHERE number=".$number."";
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			$numberplusplus = $_GET['number'] + 1;
			$query = 'UPDATE options SET value = '.$numberplusplus.' WHERE name = "'. $_GET['type'].'s_count"' ;
			try{
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
			}catch(Exception $e){
				echo '{"success": false, "msg": '.json_encode("10").'}';
				exit;
			}

			include 'ct-document.php';

			break;

		case "transform_document":
			header('Content-type: text/html');
			require_once('ct-db_connect.php');
			$old_type = $_GET['old_type'];
			$type = $_GET['type'];
			$old_number = $_GET['old_number'];
			$number = $_GET['number'];

			$query = "UPDATE ".$type." SET number=".$number.", xml=(SELECT xml FROM ".$old_type."s WHERE number=".$old_number.")";
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			$query = "INSERT INTO ".$type."s (number) VALUES (".$number.")";
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			$query = "UPDATE ".$type."s SET xml=(SELECT xml FROM ".$old_type."s WHERE number=".$old_number.") WHERE number=".$number."";
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			$numberplusplus = $_GET['number'] + 1;
			$query = 'UPDATE options SET value = '.$numberplusplus.' WHERE name = "'. $_GET['type'].'s_count"' ;
			try{
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
			}catch(Exception $e){
				echo '{"success": false, "msg": '.json_encode("10").'}';
				exit;
			}



			include 'ct-document.php';

			break;

		case "getpdf":
			require_once('ct-db_connect.php');
			$type = $_GET['type'];
			$number = $_GET['number'];

			$query = "SELECT number,xml FROM ".$type."s WHERE number=".$_GET['number']."";
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);
			//$row = mysql_fetch_row($result);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			$output = shell_exec('ls > log.txt');

			if (!($xmlFile = fopen("documents/document.xml","w"))){
				echo '{"success": false, "msg": "Oups, le fichier temporaire n\'a pas été généré. :( "}';
				exit("Unable to open file!");
			}
			else
			{
				fwrite($xmlFile, stripcslashes($row[1]));
				$fop_path = shell_exec("which fop | tr -d '\n'");
				if($fop_path != null){
					$output = shell_exec($fop_path.' -c fop.xconf -xml documents/document.xml -xsl documents/document.xsl -pdf documents/'.$type.'/'.$type.$number.'.pdf 2> log.txt');
					//$output = shell_exec('java -Djava.awt.headless=true org.apache.fop.cli.Main -c fop.xconf -xml documents/document.xml -xsl documents/document.xsl -pdf documents/'.$type.'/'.$type.$number.'.pdf 2> log.txt');
					//$output = shell_exec('fop -c /var/www/factures/fop.xconf -xml /var/www/factures/facture.xml -xsl /var/www/factures/facture.xsl -pdf /var/www/factures/'.$type.'/'.$type.$number.'.pdf');
					fclose($xmlFile);
					if (file_exists('documents/'.$type.'/'.$type.$number.'.pdf'))
						echo '{"success": true, "msg": "pdf generated"}';
					else
						echo '{"success": false, "msg": "Oups, le fichier n\'a pas pu être généré. :( --'.$fop_path.'"}';
				}else{
					echo '{"success": false, "msg": "Oups, fop ne semble pas installé. :( "}';
				}

			}

			break;

		case "save_document": // OK
			require_once('ct-config.php');
			require_once('ct-db_connect.php');

			$query = "SELECT COUNT(*) FROM ".$_GET['type']."s WHERE number = ".$_GET['number'];
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);
			//$row = mysql_fetch_row($result);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			if($row[0]<1){
					$numberplusplus = $_GET['number'] + 1;
					$query = 'UPDATE options SET value = '.$numberplusplus.' WHERE name = "'. $_GET['type'].'s_count"' ;
				try{
					//$result = mysql_query($query);
					$result = mysqli_query($link, $query);
				}catch(Exception $e){
					echo '{"success": false, "msg": '.json_encode("10").'}';
					exit;
				}
			}

			$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="facture-html.xsl"?><facture></facture>
XML;

			$xml_output = new SimpleXMLElement($xmlstr);
			$xml_output->addChild('type', $_GET['type']);
			$xml_output->addChild('acompte', $_GET['acompte']);
			$xml_output->addChild('number', $_GET['number']);
			$xml_output->addChild('date', sqlToDate($_GET['date']));
			$xml_output->addChild('follower', htmlspecialchars(urldecode($_GET['follower']), ENT_NOQUOTES, 'UTF-8'));
			$xml_output->addChild('client');
			$xml_output->client->addChild('name', htmlspecialchars(urldecode($_GET['name']), ENT_NOQUOTES, 'UTF-8'));
			$xml_output->client->addChild('contact', htmlspecialchars(urldecode($_GET['contact']), ENT_NOQUOTES, 'UTF-8'));
			$xml_output->client->addChild('address', htmlspecialchars(urldecode($_GET['address']), ENT_NOQUOTES, 'UTF-8'));
			$xml_output->client->addChild('zip', $_GET['zip']);
			$xml_output->client->addChild('city', htmlspecialchars(urldecode($_GET['city']), ENT_NOQUOTES, 'UTF-8'));
			$xml_output->client->addChild('country', htmlspecialchars(urldecode($_GET['country']), ENT_NOQUOTES, 'UTF-8'));

			$xml_output->addChild('resume');

			for ($i = 0; $i < $_GET['resume_lines']; $i++)
				$xml_output->resume->addChild('resume_line', str_replace(' ', '&#160;', htmlspecialchars(urldecode($_GET['resume_line'.$i]), ENT_NOQUOTES, 'UTF-8')));

			$nSection = -1;
			$nItem = -1;
			foreach( $_GET as $Nom => $Valeur )
			{
				if (substr($Nom, 0, 7) == "section")
				{
					list($name, $number) = explode("_", $Nom, 2);
					$xml_output->addChild('section');
					$nSection++;
					$nItem = -1;
					$xml_output->section[$nSection]->addChild('title', $Valeur);
				}

				if (substr($Nom, 0, 11) == "description")
				{
					list($description, $section, $line) = explode("_", $Nom, 3);
					$xml_output->section[$nSection]->addChild('item');
					$nItem++;
					$xml_output->section[$nSection]->item[$nItem]->addChild('description', $Valeur);
				}

				if (substr($Nom, 0, 8) == "quantity")
				{
					list($quantity, $section, $line) = explode("_", $Nom, 3);
					$xml_output->section[$nSection]->item[$nItem]->addChild('quantity', $Valeur);
				}

				if (substr($Nom, 0, 9) == "unitprice")
				{
					list($unitprice, $section, $line) = explode("_", $Nom, 3);
					$xml_output->section[$nSection]->item[$nItem]->addChild('unit_price', $Valeur);
				}
			}

			$xml_output->addChild('remise', $_GET['remise']);

			if (isset($_GET['tva']))
				$xml_output->addChild('tva', $_GET['tva']);
			else
				$xml_output->addChild('tva', TVA);


			$xml_output->addChild('pourc_acompte', $_GET['pourc_acompte']);
			$xml_output->addChild('acompte_verse', $_GET['acompte_verse']);

			$xml_output->addChild('conditions');

			for ($i = 0; $i < $_GET['conditions_lines']; $i++)
				//$xml_output->resume->addChild('conditions_line', $_GET['conditions_line'.$i]);
				$xml_output->conditions->addChild('conditions_line', str_replace(' ', '&#160;', $_GET['conditions_line'.$i]));

			$xml = mysqli_real_escape_string($link, str_replace("\n",NULL,$xml_output->asXML()));

			$query = 'INSERT INTO '.$_GET['type'].'s ('.
			'number,'.
			' xml,'.
			' name,'.
			' resume,'.
			' total_ht,'.
			' status) VALUES '.
			'('.$_GET['number'].', '.
			'"'.$xml.'", '.
			'"'.$_GET['name'].'", '.
			'"'.$_GET['resume'].'", '.
			'"'.$_GET['total_ht'].'", '.
			'"'.$_GET["status"].'") ON DUPLICATE KEY UPDATE '.
			'xml="'.$xml.'", '.
			'name="'.$_GET['name'].'", '.
			'resume="'.$_GET['resume'].'", '.
			'total_ht="'.$_GET['total_ht'].'", '.
			'status="'.$_GET['status'].'", '.
			'date="'.$_GET['date'].'"';

			try{
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
				$firstquery = json_encode($result);
			}catch(Exception $e){
				echo '{"success": false, "msg": '.json_encode("10").'}';
				exit;
			}

			$query = 'INSERT IGNORE INTO clients (name, contact, address, zip, city, country) VALUES ("'.$_GET['name'].'","'.$_GET['contact'].'","'.$_GET['address'].'","'.$_GET['zip'].'","'.$_GET['city'].'","'.$_GET['country'].'");';

			try{
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
				$secondquery = json_encode($result);
				echo '{"success": true, "msg": '.$secondquery.'}';
			}catch(Exception $e){
				echo '{"success": false, "msg": '.json_encode("10").'}';
				exit;
			}

		break;

		case "get_clients":
			require('ct-config.php');
			require('ct-db_connect.php');

			$query = 'SELECT DISTINCT id, name, contact, address, zip, city, country FROM clients WHERE name LIKE  "%'.$_GET["name_contain"].'%" ORDER BY name;';
			try{
				//$result = mysql_query($query);
				$result = mysqli_query($link, $query);
				$allres = array();
    		while ($allres[] = mysql_fetch_object($result)) {}
    		array_pop($allres);

				echo '{"success": true, "data": '.json_encode($allres).'}';
			}catch(Exception $e){
				echo '{"success": false, "msg": '.json_encode("10").'}';
				exit;
			}
		break;

		case "display_operations":
			header('Content-type: text/xml');
			require_once('ct-db_connect.php');

			if ($_GET['a_venir'])
				$query = 'SELECT * FROM operations WHERE date_operation = 0000-00-00 AND compte = "'. ($_GET['compte']) .'" ORDER BY date_facture DESC, id DESC';
			else
            {
                if (($_GET['compte']) == "Compte")
                    $query = 'SELECT * FROM operations WHERE MONTH(date_operation) = '.($_GET['month'] + 1).' AND YEAR(date_operation) = '.($_GET['year']).' AND compte != "Caisse" ORDER BY date_operation DESC, id DESC';
                else
                    $query = 'SELECT * FROM operations WHERE MONTH(date_operation) = '.($_GET['month'] + 1).' AND YEAR(date_operation) = '.($_GET['year']).' AND compte = "Caisse" ORDER BY date_operation DESC, id DESC';
            }

    		//$result = mysql_query($query) or die('Erreur sur la requète : '.$query.'<br/>'.mysql_error());
        $result = mysqli_query($link, $query);

    		$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl"?><tableau_operations></tableau_operations>
XML;

			$xml_tab = new SimpleXMLElement($xmlstr);

    		$i=0;
			while( $row = mysql_fetch_array($result) )
			{
				$operation = $xml_tab->addChild('operation');
				$operation->addAttribute('id', $row['id']);
				$operation->addChild('id', $row['id']);
				$operation->addChild('date_operation', $row['date_operation']);
				$operation->addChild('date_facture', $row['date_facture']);
				$operation->addChild('categorie', htmlspecialchars($row['categorie'], ENT_NOQUOTES, 'UTF-8'));
				$operation->addChild('provenance', htmlspecialchars($row['provenance'], ENT_NOQUOTES, 'UTF-8'));
				$operation->addChild('objet', htmlspecialchars($row['objet'], ENT_NOQUOTES, 'UTF-8'));
				$operation->addChild('compte', $row['compte']);
				$operation->addChild('debit', $row['debit']);
				$operation->addChild('credit', $row['credit']);
				$operation->addChild('credit_tva', $row['credit_tva']);
				$operation->addChild('debit_tva', $row['debit_tva']);
				$operation->addChild('remarques', htmlspecialchars($row['remarques'], ENT_NOQUOTES, 'UTF-8'));
            }

            echo $xml_tab->asXML();

			break;

		case "add_operation":
			require_once('ct-db_connect.php');

			$query = 'INSERT INTO operations (date_operation, date_facture, categorie, provenance, objet, compte, debit, credit, credit_tva, debit_tva, remarques) VALUES ("'.$_GET['date_operation'].'" , "'.$_GET['date_facture'].'" , "'.$_GET['categorie'].'" , "'.$_GET['provenance'].'" , "'.$_GET['objet'].'" , "'.$_GET['compte'].'" , "'.$_GET['debit'].'" , "'.$_GET['credit'].'" , "'.$_GET['credit_tva'].'" , "'.$_GET['debit_tva'].'" , "'.$_GET['remarques'].'" );';
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			if($result){
				echo '{"success": true, "msg": "added"}';
			}else{
				echo '{"success": false, "msg": "not added"}';
			}

		break;

		case "delete_operation":
			require_once('ct-db_connect.php');

			$query = 'DELETE FROM operations WHERE id='.$_GET['id'].';';
			//$result = mysql_query($query);
			$result = mysqli_query($link, $query);

			if($result){
				echo '{"success": true, "msg": "deleted"}';
			}else{
				echo '{"success": false, "msg": "not deleted"}';
			}

		break;

    case "save_operation":
	    require_once('ct-db_connect.php');

		  $query = 'UPDATE operations SET '. $_POST['type'] .' = "'. $_POST['value'] .'" WHERE id = '. $_GET['id'] .'';
		  //$result = mysql_query($query);
		  $result = mysqli_query($link, $query);

		  echo $query;

    break;

    case "refresh_total":
      require_once('ct-db_connect.php');

      $query = 'SELECT compte, credit, debit FROM operations WHERE date_operation != 0000-00-00';
      //$result = mysql_query($query);
      $result = mysqli_query($link, $query);

      $compte = 0;
      $caisse = 0;

      while( $row = mysql_fetch_array($result) )
			{
          // Calcul du solde dans le compte en banque
          if (($row['compte'] == 'Compte') || ($row['compte'] == 'CB'))
              $compte += $row['credit'] - $row['debit'];
          // Calcul du solde en caisse
          if ($row['compte'] == 'Caisse')
              $caisse += $row['credit'] - $row['debit'];
      }

      $query = 'UPDATE comptes SET montant = '.$compte.' WHERE compte="Compte"';
      //$result = mysql_query($query);
      $result = mysqli_query($link, $query);

      $query = 'UPDATE comptes SET montant = '.$caisse.' WHERE compte="Caisse"';
      //$result = mysql_query($query);
      $result = mysqli_query($link, $query);

      echo $compte.'|'.$caisse;

    break;
	}