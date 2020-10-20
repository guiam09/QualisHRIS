<?php
include_once ('../includes/configuration.php');
include ('../db/connection.php');

$employeeID = $_POST['employeeID'];
$range = $_POST['range'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

$query_fileName = "SELECT lastName, firstName FROM tbl_employees WHERE employeeID='$employeeID'";
$stmt_fileName = $con->prepare($query_fileName);
$stmt_fileName->execute();
while($row_fileName = $stmt_fileName->fetch(PDO::FETCH_ASSOC)){
    $firstName = $row_fileName['firstName'];
    $lastName = $row_fileName['lastName'];
    $fileName = "$firstName.$lastName.$range\.csv";
}

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Date', 'Time-In', 'Time-Out', 'Location', 'Hours Rendered'));

// fetch the data
// $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID=41 AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) AND WEEK(CURDATE()) ORDER BY attendanceDate DESC, attendance_timeIn DESC";

if($range == "currentWeek"){
    $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) AND WEEK(CURDATE()) AND (attendance_voided != 'VOID' OR attendance_voided is null OR attendance_voided = '') ORDER BY attendanceDate DESC, attendance_timeIn DESC";
}elseif($range == "lastWeek"){
    $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) - 1 AND WEEK(CURDATE()) AND (attendance_voided != 'VOID' OR attendance_voided is null OR attendance_voided = '')  ORDER BY attendanceDate DESC, attendance_timeIn DESC";
}elseif($range == "lastTwoWeeks"){
    $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) - 2 AND WEEK(CURDATE()) AND (attendance_voided != 'VOID' OR attendance_voided is null OR attendance_voided = '')  ORDER BY attendanceDate DESC, attendance_timeIn DESC";
}elseif($range == "lastMonth"){
    $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND MONTH (attendanceDate) BETWEEN MONTH( CURDATE() ) - 1 AND MONTH( CURDATE() ) AND (attendance_voided != 'VOID' OR attendance_voided is null OR attendance_voided = '')  ORDER BY attendanceDate DESC, attendance_timeIn DESC";
}elseif($range == "custom"){
    $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID='$employeeID' AND attendanceDate BETWEEN '$from_date' AND '$to_date' AND (attendance_voided != 'VOID' OR attendance_voided is null OR attendance_voided = '')  ORDER BY attendanceDate DESC, attendance_timeIn DESC";
}else{
    $query_range="SELECT attendanceDate, attendance_timeIn, attendance_timeOut, attendance_location, hourWorked FROM tbl_attendance WHERE employeeID = '$employeeID' AND (attendance_voided != 'VOID' OR attendance_voided is null OR attendance_voided = '')  ORDER BY attendanceDate DESC, attendance_timeIn DESC ";
}


$stmt_range = $con->prepare($query_range);
$stmt_range->execute();

// loop over the rows, outputting them
$total = 0;
while($row_range = $stmt_range->fetch(PDO::FETCH_ASSOC)){
    $total += $row_range['hourWorked'];
    fputcsv($output, $row_range);
}

fputcsv($output, ['','','total', $total]);
fclose($output);

?>