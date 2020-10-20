<?php

    function generateEmpID(){
      include '../db/connection.php';
        //Get the last value of employeeCode in tbl_employees
    //     $query = "SELECT MAX(employeeID) FROM tbl_employees";
    //     $stmt = $con->prepare($query);
    //     $stmt->execute();
    //
    // //get last used employee_ID. used FETCH_BOTH to output index 0
    //     $lastEmpID = $stmt->fetch(PDO::FETCH_BOTH);
    //     $empID = $lastEmpID[0];
    //
    //     //extract employee number from last employee_ID
    //     $lastEmpNumber = substr($empID,2);
    //
    //     //generate new employee number
    //     $newEmpNumber = (int)$lastEmpNumber + 1;
    $query = "SELECT MAX(employeeID) AS max_id FROM tbl_employees";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $row['max_id'] + 1;
    $num = sprintf('%04d', $num);

        //get current year in 2 digits
        $currentYear = date("y");
        //format new employee Number
        $formattedEmpNumber = str_pad($num, 3, '0', STR_PAD_LEFT);

        //generate new employee_ID
        $newEmpID = (int)($currentYear.$formattedEmpNumber);

        return $newEmpID;
    }

?>
