<?php
class Response {  
    /* Member variables */
    var $status; 
    var $message;      
    /* Member functions */
    function setStatus($par){ 
       $this->status = $par; 
    }      
    function getStatus(){ 
       return $this->status; 
    }      
    function setMessage($par){ 
       $this->message = $par; 
    }      
    function getMessage(){ 
       return $this->message; 
    } 
 }
 ?> 