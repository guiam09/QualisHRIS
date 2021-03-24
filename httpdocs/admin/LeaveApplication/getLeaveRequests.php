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

    $user = $_SESSION['employeeID'];
    $query = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leaveName,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leavedetails_leaveFrom FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID  INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leavedetails.employeeID = '$user' group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $result[] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
?>