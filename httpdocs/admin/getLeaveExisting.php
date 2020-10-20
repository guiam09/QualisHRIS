 <?php
include '../db/connection.php';

       $emp = $_POST['employeeID'];
     $q = "SELECT tbl_leaveinfo.leaveID from tbl_leave JOIN tbl_leaveinfo ON tbl_leave.leaveID = tbl_leaveinfo.leaveID WHERE tbl_leaveinfo.employeeID = '$emp'";
  $s = $con->prepare($q);
  $s->execute();
  $ids = array();
  while($row = $s->fetch(PDO::FETCH_ASSOC)){
      $ids[] = $row['leaveID'];
    }
    

    if(!$ids){
            // select all data
   $query = "SELECT * FROM tbl_leave ORDER BY leaveName ASC";
   $stmt = $con->prepare($query);
   $stmt->execute();
   $num = $stmt->rowCount();
   // check if more than 0 record found
   if($num>0){
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

   echo  '<option value="'.$row['leaveID'].'">'.$row['leaveName']. '</option>';


    }
    // if no records found
    }else{
      echo "no records found";
    }
        
    }else{
            // select all data
   $query = "SELECT * FROM tbl_leave WHERE leaveID NOT IN (".implode(',',$ids).") ORDER BY leaveName ASC";
   $stmt = $con->prepare($query);
   $stmt->execute();
   $num = $stmt->rowCount();
   // check if more than 0 record found
   if($num>0){
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

   echo  '<option value="'.$row['leaveID'].'">'.$row['leaveName']. '</option>';


    }
    // if no records found
    }else{
      echo "no records found";
    }
    }
    
    
    

    
    
   ?>



 ?> 