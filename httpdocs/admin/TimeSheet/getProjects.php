<?php
    include_once ('../../includes/configuration.php');
    include ('../../db/connection.php');
 
    $query = "SELECT * FROM tbl_project ORDER BY project_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
?>