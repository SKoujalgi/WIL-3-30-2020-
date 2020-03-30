<?php

    require_once ('dbConnect.php');
     $date = date("Y-m-d");
   
    try{
    
      
        $sql = "SELECT * FROM EVENTS where event_date < $date ";
       
        
        $stmt = $conn->prepare($sql);
        $stmt -> execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        
        $myJSON=json_encode($result);
       echo '{"results":'.$myJSON.'}';
   
        
     }catch(PDOException $e){
        
        echo "Error:" .$e->getMessage();
        
     }
    
     

?>