<!--edit access level after presentation-->


<?php
        //select employee data
        $query_employee = "SELECT * FROM tbl_employees INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID WHERE tbl_employees.departmentID<=2 OR tbl_employees.positionID=15 ORDER BY lastName ASC";
        $stmt_employee = $con->prepare($query_employee);
        $stmt_employee->execute();
        $num_employee = $stmt_employee->rowCount();
        // check if more than 0 record found
        if($num_employee>0){
            while ($row_employee = $stmt_employee->fetch(PDO::FETCH_ASSOC)){
            
                // if($employeeID!=$row_employee['employeeID']){
                
                echo  '<option value="'.$row_employee['employeeID'].'"
                
                ';
                
                if(isset($row_leaveList['leaveGroup_approver']) && $row_employee['employeeID'] == $row_leaveList['leaveGroup_approver']){
                    echo " selected";
                }
                
                echo '>'.$row_employee['lastName'].", ".$row_employee['firstName']." ".$row_employee['middleName']." - ".$row_employee['positionName']."</option>";
                
                    
                // if no records found
            // }
            }
            
        }else{
                    echo "No records found";
            }   
    ?>
