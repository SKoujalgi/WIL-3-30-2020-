<?php
    $servername = "127.0.0.1"; //$servername = 'localhost';//$servername = 'localhost:0';
    $dbname = "WIL_DB";
    $username = "root";
    $password = "SqlPwd4Me"; 
     try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=UTF8", $username, $password);
        //$conn->exec('SEt CHARACTER SET UTF8');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
     } catch(PDOException $e){
        echo "Connection failed:" .$e->getMessage();
    }
?>