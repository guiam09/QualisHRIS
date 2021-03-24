<?php
    session_start();
    include ('../../db/connection.php');

    $employeeID = $_SESSION['employeeID'];
    $range = $_POST['range'];
    $from_date = date_create($_POST['from_date']);
    $from_date = date_format($from_date,'Y-m-d');
    $to_date = date_create($_POST['to_date']);
    $to_date = date_format($to_date,'Y-m-d');;

    //$employeeIDEntry = " AND tbl_leaveGroup.employeeID=".$employeeID;
    $userID = $_SESSION['user_id'];

    $status = '';
    if (!empty($_POST['status'])) {
        $status = ' AND tbl_leaveGroup.leaveGroup_status = "'.$_POST['status'].'"';
    }          

    if ($employeeID != "") {
        if ($range == "currentWeek") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND MONTH(tbl_leaveGroup.leaveGroup_dateFiled) = MONTH(CURDATE()) AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) = WEEK(CURDATE()) ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "lastWeek") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 1 AND WEEK(CURDATE()) ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "lastTwoWeeks") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 2 AND WEEK(CURDATE()) ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "lastMonth") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND MONTH (tbl_leaveGroup.leaveGroup_dateFiled) = MONTH( CURDATE() ) - 1 ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "custom") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND tbl_leaveGroup.leaveGroup_dateFiled BETWEEN '$from_date' AND '$to_date' ".$status."  ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID ASC ";
        }
    } else {
        if ($range == "currentWeek") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND MONTH(tbl_leaveGroup.leaveGroup_dateFiled) = MONTH(CURDATE()) AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) = WEEK(CURDATE()) ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "lastWeek") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 1 AND WEEK(CURDATE()) ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "lastTwoWeeks") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 2 AND WEEK(CURDATE()) ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "lastMonth") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE YEAR(tbl_leaveGroup.leaveGroup_dateFiled) = YEAR(CURDATE()) AND MONTH (tbl_leaveGroup.leaveGroup_dateFiled) = MONTH( CURDATE() ) - 1 ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else if ($range == "custom") {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.leaveGroup_dateFiled BETWEEN '$from_date' AND '$to_date' ".$status."  ORDER BY tbl_leaveGroup.leaveGroup_ID DESC";
        } else {
            $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID ".$status." ORDER BY tbl_leaveGroup.leaveGroup_ID ASC ";
        }
    }

    $stmt = $con->prepare($query);
    $stmt->execute();
    $result[] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
?>