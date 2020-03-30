<?php

    require_once ('dbConnect.php');
     
   
    try{
    
      
        $sql = "SELECT * FROM Events";
       
        
        $stmt = $conn->prepare($sql);
        $stmt -> execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        
        $myJSON=json_encode($result);
       echo '{"results":'.$myJSON.'}';
   
        
     }catch(PDOException $e){
        
        echo "Error:" .$e->getMessage();
        
     }
    
     

?>