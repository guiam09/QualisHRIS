<?php
include_once ('../includes/configuration.php');
include ('../db/connection.php');
// include_once "../includes/loginChecker.php";

// include ('../includes/header.php');
include ('../includes/fetchData.php');
session_start();

    if(isset($_POST['exportAttendanceRecords'])){
        $employeeID = $_POST['employeeID'];
        $range = $_POST['range'];
        
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        
        header('Content-type: application/csv');
        
        header('Content-Disposition: attachment; filename=data.csv');
        
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        
        // output the column headings
        fputcsv($output, array('Date', 'Time-in', 'Time-out', 'Location', 'HoursRendered'));
        
            if($range == "currentWeek"){
                $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) AND WEEK(CURDATE()) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "lastWeek"){
                $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) - 1 AND WEEK(CURDATE()) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "lastTwoWeeks"){
                $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) - 2 AND WEEK(CURDATE()) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "lastMonth"){
                $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND MONTH (attendanceDate) BETWEEN MONTH( CURDATE() ) - 1 AND MONTH( CURDATE() ) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "custom"){
                $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND attendanceDate BETWEEN '$from_date' AND '$to_date'";
            }else{
                $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID = '$employeeID' ORDER BY attendanceDate DESC, attendance_timeIn DESC ";
            }

                $stmt_range = $con->prepare($query_range);
                $stmt_range->execute();
                // $num = $stmt_range->rowCount();
                while($row_range = $stmt_range->fetch(PDO::FETCH_ASSOC)){
                    fputcsv($output, $row_range);
                }
                fclose($output);
                
    }
header ("Location: attendance.php");
?>
