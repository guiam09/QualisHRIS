<?php
//$query="SELECT * FROM tbl_weeklyutilization WHERE weekly_startDate ='$startDate' ";
//$stmt = $con->prepare($query);
//$stmt->execute();

//if(date("D")==6){
    $day = "Monday";
    //important
    
    $weekStart  = strtotime("last $day");
    $weekEnd    = strtotime("this $day");

    if (!empty($_POST['date'])) {
        $today = strtotime($_POST['date']);
        $weekStart = $today;
        $weekEnd = strtotime("+7 day", $weekStart);
    } elseif (!empty($_GET['date'])) {
        $today = strtotime($_GET['date']);
        $weekStart = $today;
        $weekEnd = strtotime("+7 day", $weekStart);
    } else {
        $today = strtotime("today");
    }

    if($weekEnd == $today){
        $weekStart = strtotime("this $day");
        $weekEnd = strtotime("next $day");
    }
    
    //important
    $startDay = date("Y-m-d",$weekStart);
    $endDay = date("Y-m-d",$weekEnd);

    //important for database purpose
    $trueEnd = strtotime("-1 day",$weekEnd);
    $weekEnd = date("Y-m-d",$trueEnd);

    //not important
    $displayWeekStart = date("M d, Y",$weekStart);
    $displayWeekEnd = date("M d, Y",$trueEnd);

    //important
    $weekStart=date("Y-m-d",strtotime(date("Y-m-d",strtotime($startDay))));
    $lastDay=date("Y-m-d",strtotime(date("Y-m-d",strtotime($endDay))));

    function getperiod( $start, $end ){
    return new DatePeriod(
        new DateTime( $start ),
        new DateInterval('P1D'),
        new DateTime( $end ));
}

$period=getperiod( $weekStart , $lastDay );


$query = "SELECT * FROM 
                        tbl_weeklyutilization 
                    WHERE employeeCode = '".htmlspecialchars(strip_tags($_SESSION['user_id']))."' AND 
                        weekly_startDate = '".$weekStart."' AND 
                        weekly_endDate = '".$weekEnd."'";           
$stmt = $con->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();

$saturdayTotal = $sundayTotal = $mondayTotal = $tuesdayTotal = $wednesdayTotal = $thursdayTotal = $fridayTotal = $subtotal = $total = 0;

$timesheetStatus = "Not Submitted";
if ($num>0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $timesheetStatus = $row['weekly_status'];
    }
}


$disabled = '';

//get the last day of the month of the given date.
$lastDay = date("Y-m-t",strtotime($weekEnd));

//add 5 days to the last day
$limitDate = date("Y-m-d",strtotime($lastDay. " +5 day"));

if (strtotime(date('Y-m-d')) >= strtotime($limitDate)) {
    $disabled = 'disabled="disabled"';
}

?>
