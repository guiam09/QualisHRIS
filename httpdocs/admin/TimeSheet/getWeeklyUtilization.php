<?php
    include_once ('../../includes/configuration.php');
    include ('../../db/connection.php');

    $userID = $_SESSION['user_id'];
    $weekStart = $_GET['startDate'];
    $weekEnd = $_GET['endDate'];
    $query = "SELECT * FROM tbl_weeklyutilization
        INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID
        INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID
        WHERE employeeCode = '$userID' AND weekly_startDate = '$weekStart' AND weekly_endDate = '$weekEnd';";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    $weekly_status = '';
    $weekly_approval = '';

    // $saturdayTotal = $sundayTotal = $mondayTotal = $tuesdayTotal = $wednesdayTotal = $thursdayTotal = $fridayTotal = $subtotal = $total = 0;
    
    // $weekly_status = $row['weekly_status'];
    // $weekly_approval = $row['weekly_approval'];

    // $saturdayTotal += $row['weekly_saturday'];
    // $sundayTotal += $row['weekly_sunday'];
    // $mondayTotal += $row['weekly_monday'];
    // $tuesdayTotal += $row['weekly_tuesday'];
    // $wednesdayTotal += $row['weekly_wednesday'];
    // $thursdayTotal += $row['weekly_thursday'];
    // $fridayTotal += $row['weekly_friday'];

    // $subtotal = ($saturdayTotal + $sundayTotal + $mondayTotal + $tuesdayTotal + $wednesdayTotal + $thursdayTotal + $fridayTotal);
    // $total += $subtotal;

    if($num>0){
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    echo json_encode($result);
?>