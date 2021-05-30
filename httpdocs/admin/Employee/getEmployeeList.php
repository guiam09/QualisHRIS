<?php
    include ('../../db/connection.php');

    $query = "SELECT * FROM tbl_employees INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID ORDER BY lastName ASC";
    $stmt = $con->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
?>