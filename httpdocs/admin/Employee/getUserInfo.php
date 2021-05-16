<?php
    include_once ('../../includes/configuration.php');
    include ('../../db/connection.php');

    $userID = $_SESSION['user_id'];
    // $query = "SELECT * FROM tbl_employees INNER JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID ORDER BY lastName ASC";
    // $stmt = $con->prepare($query);
    // $stmt->execute();

    // $result[] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = $userID;
    
    echo json_encode($result);
?>