<?php
require_once('ct-config.php');
header("Access-Control-Allow-Origin: *");
if(isset($_GET['token']) && $_GET['token'] === TOKEN_ACCESS){
    header('HTTP/1.0 200 OK');
    header('Content-Type: application/json');
    if(isset($_GET['section']) && in_array($_GET['section'], Array('devis', 'facture', 'estimation'), true)){
      include 'ct-db_connect.php';
      $section = $_GET['section'];
      $year = (isset($_GET['year']) && !empty($_GET['year'])) ? $_GET['year'] : date("Y");
      $query = "SELECT number, date, name, resume, total_ht, id FROM ".$section."s WHERE YEAR(Date) = ".$year." ORDER BY id DESC";
      //echo $query;
      $result = mysqli_query($link, $query);
      $rows = array();
      while($r = mysqli_fetch_assoc($result)) {
          $rows[] = $r;
      }
      echo json_encode(Array('data' => $rows ));
      exit;
    } else {
      header('HTTP/1.0 422 Unprocessable Entity');
      header('Content-Type: application/json');
      echo json_encode(Array('error' => 'No known section provided.'));
    }
} else {
    header('HTTP/1.0 401 Unauthorized');
    header('Content-Type: application/json');
    echo json_encode(Array('error' => 'not autorized, sorry mate.'));
    exit;
}