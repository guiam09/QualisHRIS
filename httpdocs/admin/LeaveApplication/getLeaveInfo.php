<?php
    include_once ('../../includes/configuration.php');

    include ('../../db/connection.php');
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // include login checker
    $page_title="Admin";
    $access_type ="Admin";
    
    // include login checker
    $require_login=true;
    include_once "../../includes/loginChecker.php";
    
    $CURRENT_PAGE="Leave Application";

    $query = "SELECT * FROM tbl_leaveinfo WHERE employeeID=".$_GET["employeeId"];
    $stmt = $con->prepare($query);
    $stmt->execute();
    $result[] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
?>