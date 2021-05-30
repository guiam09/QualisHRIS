<?php
    include '../../db/connection.php';
    
    //select employee data
    $query_employee = "SELECT * FROM tbl_employees INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID WHERE tbl_employees.departmentID<=2 OR tbl_employees.positionID=15 ORDER BY lastName ASC";
    $stmt_employee = $con->prepare($query_employee);
    $stmt_employee->execute();

    $result = $stmt_employee->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
?>
