<?php
    include '../db/connection.php';
    session_start();


    if(isset($_POST['updatedLimit'])){
        $newLimit = $_POST['updatedLimit'];
        $query_updateLimit = "UPDATE tbl_attendancedetails SET attendanceDetails_maxtimein='$newLimit'";
        $stmt_updateLimit = $con->prepare($query_updateLimit);
        $stmt_updateLimit->execute();
        if($stmt_updateLimit->execute()){
            $query_updateEmployeeTable = "UPDATE tbl_employees SET employee_attendanceLimit='$newLimit'";
            $stmt_updateEmployeeTable = $con->prepare($query_updateEmployeeTable);
            $stmt_updateEmployeeTable->execute();
            
          header("Location: attendance.php");
        }
    }

?>