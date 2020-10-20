<?php
include ('../db/connection.php');


function audit($con, $modified_by_id, $modified_timestamp, $module, $sub_module, $old_data, $new_data, $action){
      $query2 = "INSERT INTO tbl_auditLogs SET 
      modified_by_id=:modified_by_id, 
      modified_timestamp=:modified_timestamp, 
      module=:module, 
      sub_module=:sub_module, 
      old_data=:old_data, 
      new_data=:new_data, 
      action=:action";
      
  $stmt2 = $con->prepare($query2);



  // bind the parameters
  $stmt2->bindParam(':modified_by_id', $modified_by_id);
  $stmt2->bindParam(':modified_timestamp', $modified_timestamp);
  $stmt2->bindParam(':module', $module);
  $stmt2->bindParam(':sub_module', $sub_module);
  $stmt2->bindParam(':old_data', $old_data);
  $stmt2->bindParam(':new_data', $new_data);
  $stmt2->bindParam(':action', $action);
  // Execute the query
  if($stmt2->execute()){
  
      
    
}
}



?>