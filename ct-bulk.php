<?php
function sqlToDate($d){
		$TabMois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "décembre");
    $separate_date = split('-',$d);
    return $separate_date[2]." ".$TabMois[$separate_date[1]-1]." ".$separate_date[0];
	} 
?>
<?php
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');

	$operation = $_GET['operation'];

	switch ($operation) {

		case "bulk_export":
			require_once('ct-db_connect.php');
			$type = $_GET['type'];
			
			$query = "SELECT number,xml FROM ".$type."s WHERE date < '".$_GET['date_end']."' && date > '".$_GET['date_begin']."'";
			$result = mysql_query($query);

			$msg = 0;

			
			
			while($row = mysql_fetch_array($result)){
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
						$output = shell_exec($fop_path.' -c fop.xconf -xml documents/document.xml -xsl documents/document.xsl -pdf documents/temp/'.$type.$row[0].'.pdf 2> log.txt');
						fclose($xmlFile);
						if (file_exists('documents/temp/'.$type.$row[0].'.pdf'))
							$msg++;
						else
							$msg .= " Oups, le fichier ".$row[0]." n'a pas pu être généré. :( ";
					}else{
						echo '{"success": false, "msg": "Oups, fop ne semble pas installé. :( "}';
						exit("fop is not installed"); 
					}
				}
			}
			if($msg > 0){
				$output = shell_exec("zip -rj documents/export_".$type."_".$_GET['date_end'].".zip documents/temp/");
				if (file_exists("documents/export_".$type."_".$_GET['date_end'].".zip")){
					$output = shell_exec("rm -rf documents/temp/*");
					echo '{"success": true, "msg": "'.$msg.' documents generated.", "filename": "documents/export_'.$type.'_'.$_GET["date_end"].'.zip"}';
				}
			}else{
				echo '{"success": false, "msg": "'.$msg.'"}';
			}
			break;	

		case "number_of_docs":
			require_once('ct-db_connect.php');
			try{
				$type = $_GET['type'];
				$query = "SELECT COUNT(date) FROM ".$type."s where date < '".$_GET['date_end']."' && date > '".$_GET['date_begin']."'";
				$result = mysql_query($query);
				$row = mysql_fetch_row($result);
				echo '{"success": true, "msg": "'.$row[0].'"}';						
			}catch(Exception $e){
				echo '{"success": false, "msg":'.json_encode($e).'}';
			}
			
			break;
	}
	
	?>
