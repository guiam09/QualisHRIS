<?php
include '../db/connection.php';

if (isset($_POST['search'])){
    $q = "";
    $emp = $_POST['employee'];
    $lt = $_POST['leaveType'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    
             // converting one date format into another
    
   
   
    if(!empty($_POST['employee'])){
        $q .= " employeeID='$emp' ";
        
        if(!empty($_POST['leaveType'])){
             $q .= " AND leaveID='$lt'";
             
             if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }else{
                 
             }
        }else{
            if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }
            
        }
    }else{
        if(!empty($_POST['leaveType'])){
             $q .= " leaveID='$lt'";
             
             if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }
        }else{
            if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " leaveFrom >= '$from' AND leaveTo <= '$to' "; 
            }
        }
    }
    
    
    $query = "SELECT * FROM tbl_leavedetails WHERE $q";
    $stmt = $con->prepare($query);
    $stmt->execute();
    


}




 ?>
