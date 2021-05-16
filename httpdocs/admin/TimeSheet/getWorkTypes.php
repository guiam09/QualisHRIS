<?php
    include_once ('../../includes/configuration.php');
    include ('../../db/connection.php');
 
    $query = "SELECT * FROM tbl_worktype ORDER BY work_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
?>