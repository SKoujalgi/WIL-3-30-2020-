<?php
require_once ('dbConnect.php');
    try {
      $conn->query("SET NAMES utf8");
      $result= $conn->query("SELECT first_name, last_name FROM users")->fetchAll(PDO::FETCH_ASSOC);
/*      
      $sql = "SELECT * FROM Chapter";
      $stmt = $conn->prepare($sql);
      $stmt -> execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/      
      //$result = $stmt->fetchColumn(1); 
      $myJSON=json_encode($result);   
      //echo '{"results":'.$myJSON.'}';   
      echo $myJSON;      
     } catch (PDOException $e) {  
        echo "Error:" .$e->getMessage();
      }
?>