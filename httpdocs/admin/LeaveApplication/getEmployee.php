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

    if ($_GET['id'] == ''){
        $userID = $_SESSION['user_id'];
        $query = "SELECT * FROM tbl_employees WHERE employeeCode = '$userID'";
    } else if ($_GET['id'] != ''){
        $query = "SELECT lastName, firstName, middleName FROM tbl_employees WHERE employeeID=" .$_GET['id'];
    }
    

    $stmt = $con->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
?>