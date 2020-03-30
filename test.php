<?php

    $servername = "127.0.0.1"; //$servername = 'localhost';//$servername = 'localhost:0';
    $dbname = "WIL_DB";
    $username = "root";
    $password = "SqlPwd4Me";
    
  

     try{
      
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
         
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "Connected successfully";
         
     }catch(PDOException $e){
        
        echo "Connection failed:" .$e->getMessage();
        
    }

    //getting data
    try{
        $sql = "SELECT * FROM Chapter";
  
          $stmt = $conn->prepare($sql);
          $stmt -> execute();
          
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
          $myJSON=json_encode($result);
          
          echo '{"results":'.$myJSON.'}';
     
          
       }catch(PDOException $e){
          
          echo "Error:" .$e->getMessage();
          
       }



?>