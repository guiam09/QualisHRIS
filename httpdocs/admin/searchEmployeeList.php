
<!--<select class="form-control col-md-6" data-plugin="select2"  id="employeeId" name="employeeId" data-placeholder="Select Employee">-->
    <!--<option value="" selected></option>-->
    <?php
        //select employee data
        $query_employee = "SELECT * FROM tbl_employees INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID ORDER BY lastName ASC";
        $stmt_employee = $con->prepare($query_employee);
        $stmt_employee->execute();
        $num_employee = $stmt_employee->rowCount();
        // check if more than 0 record found
        if($num_employee>0){
            echo  '<option value="">Select Employee...</option>';

            while ($row_employee = $stmt_employee->fetch(PDO::FETCH_ASSOC)){
            
        echo  '<option value="'.$row_employee['employeeID'].'">'.$row_employee['lastName'].", ".$row_employee['firstName']." ".$row_employee['middleName'];
        
        // - ".$row_employee['positionName']."</option>";
        
            }
        // if no records found
        }else{
            echo "No records found";
        }                                    
    ?>
<!--</select>-->