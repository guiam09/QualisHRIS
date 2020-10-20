<?php
include_once ('../includes/configuration.php');

include ('../db/connection.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include login checker
$page_title="Admin";
$access_type ="Admin";

// include login checker
$require_login=true;
include_once "../includes/loginChecker.php";

include ('../includes/header.php');
include ('../includes/navbar.php');
$CURRENT_PAGE="Attendance";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');

// for showing user accessed tabs
$userCode = $_SESSION['employeeID'];
$query_hideTab = "SELECT DISTINCT accessedModules FROM tbl_accesslevels JOIN tbl_accessLevelEmp ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID JOIN tbl_accessLevelModules ON tbl_accessLevelModules.accessLevelID = tbl_accesslevels.accessLevelID WHERE tbl_accessLevelEmp.employeeID = '$userCode'";
$stmt_hideTab = $con->prepare($query_hideTab);
$stmt_hideTab->execute();
$num_hideTab = $stmt_hideTab->rowCount();

// continuation of showing user accessed tabs below
?>

    <!-- Page -->
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Attendance</h1>
        </div>
<!-- continuation of showing user accessed tabs -->
<?php
    if($num_hideTab>0){
        $tabPresent = 0;
        $attendanceManagementModulePresent = 0;
        $attendanceConfigurationModulePresent = 0;
        while ($row_hideTab = $stmt_hideTab->fetch(PDO::FETCH_ASSOC)){
            if($row_hideTab['accessedModules'] == 'attendanceManagementModule'){
                $attendanceManagementModulePresent = 1;
                $tabPresent+=1;
            }
            if($row_hideTab['accessedModules'] == 'attendanceConfigurationModule'){
                $attendanceConfigurationModulePresent = 1;
                $tabPresent+=1;
            }
        }
        
        if($tabPresent>0){
?>
        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item" style="margin-left:10px">
                <a class="nav-link active" id="custom-content-below-attendanceHistory-tab" data-toggle="pill" href="#custom-content-below-attendanceHistory" role="tab" aria-controls="custom-content-below-attendanceHistory" aria-selected="true">Attendance History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-below-attendanceManagement-tab" data-toggle="pill" href="#custom-content-below-attendanceManagement" role="tab" aria-controls="custom-content-below-attendanceManagement" aria-selected="false" <?php if($attendanceManagementModulePresent != 1){echo "hidden";}?>>Attendance Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-below-attendanceConfiguration-tab" data-toggle="pill" href="#custom-content-below-attendanceConfiguration" role="tab" aria-controls="custom-content-below-attendanceConfiguration" aria-selected="false" <?php if($attendanceConfigurationModulePresent != 1){echo "hidden";}?>>Attendance Configuration</a>
            </li>
        </ul>
<?php        
        } else {
            // leave blank to remove tab access
        }
        
    }
?>

        <!--<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">-->
        <!--    <li class="nav-item" style="margin-left:10px">-->
        <!--        <a class="nav-link active" id="custom-content-below-attendanceHistory-tab" data-toggle="pill" href="#custom-content-below-attendanceHistory" role="tab" aria-controls="custom-content-below-attendanceHistory" aria-selected="true">Attendance History</a>-->
        <!--    </li>-->
        <!--    <li class="nav-item">-->
        <!--        <a class="nav-link" id="custom-content-below-attendanceManagement-tab" data-toggle="pill" href="#custom-content-below-attendanceManagement" role="tab" aria-controls="custom-content-below-attendanceManagement" aria-selected="false" <?php if($access_type!="Admin" || $page_title!="Admin"){echo "hidden";}?>>Attendance Management</a>-->
        <!--    </li>-->
        <!--    <li class="nav-item">-->
        <!--        <a class="nav-link" id="custom-content-below-attendanceConfiguration-tab" data-toggle="pill" href="#custom-content-below-attendanceConfiguration" role="tab" aria-controls="custom-content-below-attendanceConfiguration" aria-selected="false" <?php if($access_type!="Admin" || $page_title!="Admin"){echo "hidden";}?>>Attendance Configuration</a>-->
        <!--    </li>-->
        <!--</ul>-->
<!--// end of showing user accessed tabs-->
        <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade active show" id="custom-content-below-attendanceHistory" role="tabpanel" aria-labelledby="custom-content-below-attendanceHistory-tab">
                <div class="page-content container-fluid">
            <div class="row">
                        <div class="col-md-4">
                          <!-- Panel Floating Labels -->
                          <div class="panel">
                            <div class="panel-heading">
                              <h3 class="panel-title text-danger"></h3>
                            </div>
                            <div class="panel-body container-fluid">
                                <div class="row-md-6">
                                    <?php include ('../includes/clock/clock.php');?>
                                </div>
                                <div class="row-md-6">
                                    <h2 style="text-align:center" id="date"></h2>
                                    <h3 style="text-align:center" id="time"></h3>
                                    <br/>
                                </div>
                              <?php
                              $userCode = $_SESSION['user_id'];
                              $date_now = date('Y-m-d');
                              
                                $query = "SELECT * FROM tbl_employees WHERE employeeCode =:user";
                                $stmt = $con->prepare($query);
                                $stmt->bindParam(':user', $userCode);
                                $stmt->execute();
                                $num = $stmt->rowCount();
                                if($num>0){
    
                                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                  $employeeID = $row['employeeID'];
                                  
                                  date_default_timezone_set("Asia/Manila");
                                  $lognow = date("H:i:s");

                                  $sched = $row['coreTimeID'];
    
                                      if (isset($_POST['timeIn'])){
                                          $query_coretime = "SELECT * FROM tbl_coretime WHERE coreTimeID = '$sched'";
                                          $stmt_coretime = $con->prepare($query_coretime);
                                          $stmt_coretime->execute();
                                          $row_coretime = $stmt_coretime->fetch(PDO::FETCH_ASSOC);
    
                                          $coreTimeEnd = $row_coretime['timeOut'];
                                          $coreTimeEnd = new DateTime($coreTimeEnd);

                                          $timeNow = new DateTime($lognow);
    // edited 08/01/2019 changed $timeNow > $coreTimeEnd to $timeNow == $coreTimeEnd for testing purposes only
                                          if($timeNow == $coreTimeEnd){
                                              echo "<div class='alert alert-danger'>You cannot time-in past your core time.</div>";
                                          } else {
    
                                             // $query_overtime = "";
    
                                              $query_checktimein = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendanceDate = '$date_now' AND attendance_timeIn IS NOT NULL AND ISNULL(attendance_timeOut)";
                                              $stmt_checktimein = $con->prepare($query_checktimein);
                                              $stmt_checktimein->execute();
                                              $num_checktimein = $stmt_checktimein->rowCount();
    
                                              if($num_checktimein > 0){
                                                  echo "<div class='alert alert-danger'>You have already timed-in.</div>";
                                              } else {
    
                                                  $query_log = "SELECT * from tbl_employees WHERE employeeID = '$employeeID'";
                                                  $stmt_log = $con->prepare($query_log);
                                                  $stmt_log->execute();
                                                  $row_log = $stmt_log->fetch(PDO::FETCH_ASSOC);
                                                  $maxlogs = $row_log['employee_attendanceLimit'];
    
                                                  $query_logCounter = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendanceDate = '$date_now' AND attendance_timeIn IS NOT NULL AND attendance_timeOut IS NOT NULL AND hourWorked > 0";
                                                  $stmt_logCounter = $con->prepare($query_logCounter);
                                                  $stmt_logCounter->execute();
                                                  $num_logCounter = $stmt_logCounter->rowCount();
                                                  if($num_logCounter > 0) {
                                                      while($row_logCounter = $stmt_logCounter->fetch(PDO::FETCH_ASSOC)) {
                                                          $currentLog = $row_logCounter['timeinLog'];
                                                      }
                                                      $log = 1 + $currentLog;
                                                      
                                                    //   if($log == $maxlogs){
                                                    //       echo "<div class='alert alert-warning'>You have logged-in for the max number of times today.</div>";
                                                    //   }
                                                      if($log > $maxlogs){
                                                          echo "<div class='alert alert-danger'>You have already exceeded your max time-in count.</div>";
                                                      } else {
                                                          $query = "INSERT INTO tbl_attendance
                                                                  SET employeeID=:employeeID, attendanceDate=:dateNow, attendance_timeIn=:timeNow, status=:status, attendance_location=:location, timeinLog=:log, attendance_timeOut = NULL
                                                                  ";
    
                                                          $stmt = $con->prepare($query);
                                                          $status = "Online";
                                                           
                                                          $stmt->bindParam(':employeeID', $employeeID);
                                                          $stmt->bindParam(':dateNow', $date_now);
                                                          $stmt->bindParam(':timeNow', $lognow);
                                                          $stmt->bindParam(':status', $status);
                                                          $stmt->bindParam(':log', $log);
                                                          $stmt->bindParam(':location', $loc);
    
                                                          if($stmt->execute()){
                                                            $query_getNewAttendanceId = "SELECT attendanceID FROM tbl_attendance WHERE employeeID=:employeeID AND status='Online'";
                                                            $stmt_getNewAttendanceId = $con->prepare($query_getNewAttendanceId);
                                                            $stmt_getNewAttendanceId->bindParam(':employeeID',$employeeID);
                                                              
                                                            if($stmt_getNewAttendanceId->execute()){
                                                                while($row_getNewAttendanceId = $stmt_getNewAttendanceId->fetch(PDO::FETCH_ASSOC)){
                                                                    $newAttendanceID = $row_getNewAttendanceId['attendanceID'];
                                                                }
                                                                //get updated by name
                                                                $query_getName = "SELECT * FROM tbl_employees WHERE employeeCode='$userCode'";
                                                                $stmt_getName = $con->prepare($query_getName);
                                                                $stmt_getName->execute();
                                                                while($row_getName = $stmt_getName->fetch(PDO::FETCH_ASSOC)){
                                                                    $updatedBy = $row_getName['firstName'] . " " . $row_getName['lastName'];
                                                                }
                                                                  
                                                                  
                                                                  $query_insertOriginalEntry = "INSERT INTO tbl_attendanceModification SET attendanceID=:attendanceID, attendanceModification_updatedBy=:updatedBy, attendanceModification_modificationDate=:modificationDate, attendanceModification_activity=:activity, attendanceModification_timesModified=0";
                                                                  $stmt_insertOriginalEntry = $con->prepare($query_insertOriginalEntry);
                                                                  
                                                                  $modificationDate = date('Y-m-d');
                                                                  $activity = 'Entry Added';
                                                                  
                                                                  $stmt_insertOriginalEntry->bindParam(':attendanceID', $newAttendanceID);
                                                                  $stmt_insertOriginalEntry->bindParam(':updatedBy', $updatedBy);
                                                                  $stmt_insertOriginalEntry->bindParam(':modificationDate', $modificationDate);
                                                                  $stmt_insertOriginalEntry->bindParam(':activity', $activity);

                                                                  if($stmt_insertOriginalEntry->execute()){
                                                                    echo "<div class='alert alert-success' align='center'>Time-In successful!</div>";
                                                                    if($log == $maxlogs){
                                                                        echo "<div class='alert alert-warning'>This is your last time entry for today. You would not be able to time-in today after you have time-out this time entry.</div>";
                                                                    }
                                                            }
                                                              }else{
                                                                  echo "<div class='alert alert-danger'>Time-In Failed!</div>";
                                                              }
                                                          }else{
                                                              echo "<div class='alert alert-danger'>Time-In Failed!</div>";
                                                          }
    
                                                      }
                                                  } 
                                                  
                                                //   WHAT IS THE ELSE CODE BELOW FOR? 08/06/2019 
                                                
                                                  else {
    
                                                      $log = 1;
    
                                                      $query = "INSERT INTO tbl_attendance
                                                                  SET employeeID=:employeeID, attendanceDate=:dateNow, attendance_timeIn=:timeNow, status=:status, timeinLog=:log, attendance_location=:location
                                                                  ";
                                                      $stmt = $con->prepare($query);
                                                      $status = "Online";
    
                                                      $stmt->bindParam(':employeeID', $employeeID);
                                                      $stmt->bindParam(':dateNow', $date_now);
                                                      $stmt->bindParam(':timeNow', $lognow);
                                                      $stmt->bindParam(':status', $status);
                                                      $stmt->bindParam(':log', $log);
                                                      $stmt->bindParam(':location', $loc);
    
                                                      if($stmt->execute()){
                                                          echo "<div class='alert alert-success' align='center'>Time-In successful!</div>";
                                                      }else{
                                                          echo "<div class='alert alert-danger'>Time-In failed!</div>";
                                                      }
                                                  }
                                              }
                                          }
                                      } else if (isset($_POST['timeOut'])){
                                          $query_checktimeout = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendance_timeIn IS NOT NULL AND  ISNULL(attendance_timeOut)";
                                          $stmt_checktimeout = $con->prepare($query_checktimeout);
                                          $stmt_checktimeout->execute();
                                          $num_checktimeout = $stmt_checktimeout->rowCount();

                                          if($num_checktimeout < 1){
                                              echo "<div class='alert alert-danger'>No record found. Please time-in first.</div>";
                                          } else {
                                              
                                              $row_checktimeout = $stmt_checktimeout->fetch(PDO::FETCH_ASSOC);
                                              
                                              $query = "UPDATE tbl_attendance SET attendance_timeOut = '$lognow', attendance_timeOutDate = '$date_now', attendance_location = '$loc', status='Offline' WHERE attendanceID = '".$row_checktimeout['attendanceID']."'";
    
                                              $stmt = $con->prepare($query);
    
                                              if($stmt->execute()){
                                                  
    
                                                  $query5 = "SELECT * FROM tbl_attendance WHERE attendanceID = '".$row_checktimeout['attendanceID']."'";
                                                  $stmt5 = $con->prepare($query5);
                                                  $stmt5->execute();
                                                  $urow = $stmt5->fetch(PDO::FETCH_ASSOC);
    
                                                  $time_in = $urow['attendance_timeIn'];
                                                  $time_out = $urow['attendance_timeOut'];
                                                  $date_in = $urow['attendanceDate'];
                                                  $date_out = $urow['attendance_timeOutDate'];
    
                                                  $sql5 = "SELECT * FROM tbl_employees LEFT JOIN tbl_coretime ON tbl_coretime.coretimeID=tbl_employees.coretimeID WHERE tbl_employees.employeeID = '$employeeID'";
                                                  $stmt1 = $con->prepare($sql5);
                                                  $stmt1->execute();
                                                  $srow = $stmt1->fetch(PDO::FETCH_ASSOC);
    
                                                //   $time_in = new DateTime($time_in);
                                                //   $time_out = new DateTime($time_out);
                                                //   $date_in = new DateTime($date_in);
                                                //   $date_out = new DateTime($date_out);
                                                //   try
                                                    $dateTimeIn = $date_in . ' ' . $time_in;
                                                    // $dateTimeIn = strtotime($dateTimeIn);
                                                  $dateTimeIn = new DateTime($dateTimeIn);
                                                  $dateTimeOut = $date_out . ' ' . $time_out;
                                                //   $dateTimeOut = strtotime($dateTimeOut);
                                                  $dateTimeOut = new DateTime($dateTimeOut);
                                                  $totalTimeInterval = $dateTimeIn->diff($dateTimeOut);
                                                // end try
                                                //   $dateInterval = $date_in->diff($date_out);
                                                //   $timeInterval = $time_in->diff($time_out);
                                                  $days = $totalTimeInterval->format('%a');
                                                  $hrs = $totalTimeInterval->format('%h');
                                                  $mins = $totalTimeInterval->format('%i');
                                                  $mins = $mins/60;
                                                //   $days = $days*1440;
                                                //   $hrs = $hrs * 60;
                                                  $days = $days*24;
                                                  $int = $days + $hrs + $mins;
                                                  
                                                //   $int =  ($days + $hrs + $mins)/60;
                                                //   $int = $days;
                                                //   $int = $hrs;
                                                //   $int = $mins;
                                                //   if($int > 4){
                                                //     $int = $int - 1;
                                                //   }
                                                
                                                    
                                                
    // change query 08/02/2019
                                                  $query_hoursWorked = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendance_timeOutDate = '$date_now'";
                                                  $stmt_hoursWorked = $con->prepare($query_hoursWorked);
                                                  $stmt_hoursWorked->execute();
                                                  $num_hoursWorked = $stmt_hoursWorked->rowCount();
    
                                                //   if($num_hoursWorked>0) {
                                                //       $row_hoursWorked = $stmt_hoursWorked->fetch(PDO::FETCH_ASSOC);
                                                //       $completeHours = $row_hoursWorked['hourWorked'];
                                                //       $int = $int + $completeHours;
                                                //   }
    
                                                //   $int = round($int,1,PHP_ROUND_HALF_DOWN);

                                               // a  $decimalDigits = $int - floor($int);
                                               // a   if(($decimalDigits)<0.5){
                                               // a      $int = floor($int);
                                               // a   } else if(($decimalDigits)>=0.5){
                                               // a       $int = (floor($int))+0.5;
                                               // a   }

                                               // a    if($int<0.5){
                                               // a        echo "<div class='alert alert-warning'>Attendance record has been voided!</div>";
                                               // a        $query = "DELETE FROM tbl_attendance WHERE employeeID = '$employeeID' order by attendanceID DESC limit 1";
                                               // a        $stmt = $con->prepare($query);
                                               // a        $stmt->execute();
                                               // a    }else{
                                               // a         echo "<div class='alert alert-success' align='center'>Time-Out successful!</div>";
                                               // a         $query = "UPDATE tbl_attendance SET hourWorked = '$int', attendance_location='$loc' WHERE employeeID = '$employeeID' AND attendance_timeOutDate = '$date_now'";
                                               // a         $stmt = $con->prepare($query);
                                               // a         $stmt->execute();
                                               // a    }
                                                    // header('location: attendance.php');

                                                        $int = number_format($int, 2);

                                                        echo "<div class='alert alert-success' align='center'>Time-Out successful!</div>";
                                                        $query = "UPDATE tbl_attendance SET hourWorked = '$int', attendance_location='$loc' WHERE employeeID = '$employeeID' AND attendance_timeOutDate = '$date_now' AND attendanceID = '".$row_checktimeout['attendanceID']."' ";
                                                        $stmt = $con->prepare($query);
                                                        $stmt->execute();
                                                  }

                                                else{
                                                    echo "<div class='alert alert-danger'>Error 66.</div>";
                                                }
                                          }
                                      } 
                                }
    
                               ?>
                              <form id="attendanceLogs" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                  <div class="row">
                                      <input type = "hidden" name ="time" value = "<?php echo date('h:i'); ?>">
                                            <div class="col-md-2">
                                            <!--<div class="form-group">-->
                                            <!--    <button type="submit" name="timeIn" id="timeIn" class="btn btn-block btn-info">Time-In</button>-->
                                            <!--            </div>-->
                                            </div>
                                            <div class="col-md-8">
                                            <div class="form-group">
                                                <?php
                                                    // Please change this code later... Check and analyze employeeID and employeeCode then remove employeeID 
                                                    // Get employeeID (the 2 digit ID)
                                                    $query_getEmployeeID = "SELECT employeeID FROM tbl_employees WHERE employeeCode='$userCode'";
                                                    $stmt_getEmployeeID = $con->prepare($query_getEmployeeID);
                                                    $stmt_getEmployeeID->execute();
                                                    $num_getEmployeeID = $stmt_getEmployeeID->rowCount();
                                                    if($num_getEmployeeID > 0) {
                                                      while($row_getEmployeeID = $stmt_getEmployeeID->fetch(PDO::FETCH_ASSOC)) {
                                                          $temporaryID = $row_getEmployeeID['employeeID'];
                                                      }
                                                    }
                                                    // End Get employeeID
                                                
                                                    $query_countOnlineStatus = "SELECT * FROM tbl_attendance WHERE employeeID='$temporaryID' AND status='Online'";
                                                    $stmt_countOnlineStatus = $con->prepare($query_countOnlineStatus);
                                                    $stmt_countOnlineStatus->execute();
                                                    $num_countOnlineStatus = $stmt_countOnlineStatus->rowCount();
                                                    if($num_countOnlineStatus>0) {
                                                        echo '<button type="button" name="timeOut" id="timeOutButton" value="timeOut" class="btn btn-block btn-danger" onclick="voidAttendance()">Time-Out</button>';
                                                    } else {
                                                        echo '<button type="submit" name="timeIn" id="timeInButton" class="btn btn-block btn-info">Time-In</button>';
                                                    }
                                                ?>
                                            </div>
                                    </div>
                                  </div>
                              </form>
    
                            </div>
                          </div>
                          <!-- End Panel Floating Labels -->
                        </div>
                        
                        <!-- Feb 24, 2019 UPDATE START -->
                        <!-- Account Daily Attendance History Table -->
                        <div class="col-md-8">
                          <!-- Panel Floating Labels -->
                          <div class="panel">
                            <div class="panel-heading">
                              <h3 class="panel-title text-danger"></h3>
                            </div>
                            <div class="panel-body container-fluid">
                              <div>
                                <?php include ('get_week_range.php'); ?>
                                <h2>Attendance History for <?php echo date('F Y') ?></h2>
                                <br> 
                               
                                <?php
                                
                                $currentDay = date("Y-m-d",$today);
                                $userID = $_SESSION['user_id'];
                                $query = "SELECT * FROM tbl_attendance INNER JOIN tbl_employees ON tbl_attendance.employeeID = tbl_employees.employeeID
                                            WHERE employeeCode='$userID' AND MONTH(attendanceDate)=MONTH(CURRENT_DATE()) AND YEAR(attendanceDate)=YEAR(CURRENT_DATE())  AND !(attendance_voided <=> 'VOID') AND hourWorked > 0 ORDER BY attendanceDate DESC, attendance_timeIn DESC ";
                                $stmt = $con->prepare($query);
                                $stmt->execute();
                                
                                $num = $stmt->rowCount();
                            
                                if($num>0){
                                    
                                    echo " 
                                        <table class='table table-hover dataTable table-striped ' id='exampleTableTools2'>
                                            <thead>
                                                <tr>
                                                    <th width='15%'>Work Date</th>
                                                    <th width='5%'></th>
                                                    <th width='20%'>Time-In</th>
                                                    <th width='20%'>Time-Out</th>
                                                    <th width='15%'>Location</th>
                                                    <th width='15%'>Hours Rendered</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    ";
                                    
                                    $holdDate="";
                                    $rowCounter= 1;
                                    $totalRenderedHours=0;
                                    $overAllTotalHours=0;
                                    
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        $currentDate = $row["attendanceDate"];
                                        $timeInTime=strtotime($row["attendance_timeIn"]);
                                        $formattedTimeIn=date('h:i:s A', $timeInTime);
                                        
                                        if(isset($row["attendance_timeOut"])){
                                            $timeOutTime=strtotime($row["attendance_timeOut"]);
                                            $formattedTimeOut=date('h:i:s A', $timeOutTime);
                                            
                                        } else {
                                            $formattedTimeOut = " - - - - - - - - - - ";
                                        }
                                        if($holdDate!=$currentDate && $rowCounter!=1){
                                            echo "
                                                <tr>
                                                    <td width='15%'></td>
                                                    <td width='5%'></td>
                                                    <td width='20%'></td>
                                                    <td width='20%'></td>
                                                    <strong><th width='15%'>TOTAL</th></strong>
                                                    <strong><th width='15%'>" . $totalRenderedHours . "</th></strong>
                                                </tr>
                                            ";
                                            $overAllTotalHours = $overAllTotalHours + $totalRenderedHours;
                                            $totalRenderedHours = 0;
                                            
                                        }
                                        
                                        echo "
                                            <tr>";
                                                if($holdDate!=$currentDate){
                                                    // $overAllTotalHours = $overAllTotalHours + $totalRenderedHours;
                                                    // $totalRenderedHours = 0;
                                                    echo "<td width='15%'>" . $row["attendanceDate"] . "</td>";
                                                }else{
                                                    echo "<td width='15%'></td>";
                                                }

                                                $formattedhourWorked = number_format($row["hourWorked"], 2, '.', ',');

                                                echo "
                                                
                                                <td width='5%'></td>
                                                <td  width='20%'>" . $formattedTimeIn . "</td>
                                                <td  width='20%'>" . $formattedTimeOut . "</td>
                                                <td  width='15%'>" . $row["attendance_location"] . "</td>";
                                                if($row["hourWorked"] == 0){
                                                    echo "<td  width='15%'> - - - - - - - - - - </td> ";
                                                }else{
                                                    echo "<td width='15%'>" . $formattedhourWorked . "</td>";    
                                                }
                                            echo "</tr>";
                                                
                                        $totalRenderedHours = $totalRenderedHours + $row["hourWorked"];
                                        

                                        if($rowCounter==$num){
                                            
                                            $overAllTotalHours = $overAllTotalHours + $totalRenderedHours;
                                            $totalRenderedHours = number_format($totalRenderedHours, 2, '.', ',');
                                            $overAllTotalHours = number_format($overAllTotalHours, 2, '.', ',');
                                            echo "
                                                <tr>
                                                    <td width='15%'></td>
                                                    <td width='5%'></td>
                                                    <td width='20%'></td>
                                                    <td width='20%'></td>
                                                    <strong><th width='15%'>TOTAL</th></strong>
                                                    <strong><th width='15%'>" . $totalRenderedHours . "</th></strong>
                                                </tr>
                                            ";
                                        }
                                        
                                        $holdDate=$currentDate;
                                        $rowCounter+=1;
                                    }
                                    
                                    echo "
                                        </tbody>
                                        <tfoot>
                                            <tr></tr>
                                            <tr>
                                                <td width='15%'></td>
                                                <td width='5%'></td>
                                                <td width='20%'></td>
                                                <td width='10%'></td>
                                                <h2><th width='20%'>MONTH TOTAL</th></h2>
                                                <h1><th width='15%'>". $overAllTotalHours ."</th></h1>
                                            </tr>
                                        </tfoot>
                                        </table>
                                    ";
                         
                                } else {
                                    
                                    echo "You have yet to time-in today.";
                                    
                                }
                                ?>
                              </div>
                            </div>
                          </div>
                         </div>
                        <!-- End Panel Floating Labels -->
                        <!-- FEB 24, 2019 UPDATE END-->
            </div>
          </div>
            </div>
            <!-- END of Attendance History Tab (See line 41 for start of Attendance history) -->
            <div class="tab-pane fade" id="custom-content-below-attendanceManagement" role="tabpanel" aria-labelledby="custom-content-below-attendanceManagement-tab">
                <div class="page-content container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Floating Panels -->
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-danger"></h3>
                                </div>
                                <div class="panel-body container-fluid">
                                    <div class="row-md-3">
                                        <div class="form-group form-material col-md-12">
                                            <label class="form-control-label">Employee</label>
                                            <select class="form-control col-md-6" data-plugin="select2"  id="employeeId" name="employeeId" data-placeholder="Select Employee">
                                            <?php include ('searchEmployeeList.php'); ?>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <!---->
                                    <div class="row-md-3">
                                        <div class="form-group form-material col-md-12">
                                            <label class="form-control-label">Range</label><br/>
                                            <select class="form-control col-md-12" data-plugin="selectpicker"  id="rangeLength" name="rangeLength" data-placeholder="Select Range" width="auto" onchange='showDateRange(this.value)' />
                                                <option selected>Select Range</option>
                                                <option value="currentWeek">Current Week</option>
                                                <option value="lastWeek">Last Week</option>
                                                <option value="lastTwoWeeks">Last 2 Weeks</option>
                                                <option value="lastMonth">Last Month</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <!---->
                                        <!-- Date Range -->
                                    <div class="row-md-3" id='fromDateGroup'>
                                        <div class="form-group form-material col-md-12">
                                            <label class="form-control-label">From</label>
                                            <div class="col-md-12 input-group input-daterange" id="fromdatepicker" data-plugin="datepicker">
                                                <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-md-3" id='toDateGroup'>
                                        <div class="form-group form-material col-md-12">
                                            <label class="form-control-label">To</label>
                                            <div class="col-md-12 input-group input-daterange" id="todatepicker" data-plugin="datepicker">
                                                <input type="text" name="to_date" id="to_date" class="form-control" onclick="checkValue()" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <!---->
                                    <div class="row-md-3">
                                        <div class="form-group form-material col-lg-2">
                                            <button type="submit" class="btn btn-primary" name="search" id="search"><i class="fas fa-search"></i> Search </button>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                        </div>
                        <!-- Attendance Filter Table -->
                        <div class="col-md-8">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-danger"></h3>
                                </div>
                                <div class="panel-body container-fluid">
                                    <div class="row-md-6">
                                        <!--<a href="#" style="float:right"><span class="fas fa-file-download"></span></a>-->
                                        <table class="table table-hover dataTable table-striped" id="empAttendanceReports">
                                            <!--<thead>-->
                                            <!--    <tr>-->
                                            <!--        <th width='10%'>Date</th>-->
                                            <!--        <th width='10%'></th>-->
                                            <!--        <th width='20%'>Time-In 123</th>-->
                                            <!--        <th width='20%'>Time-Out</th>-->
                                            <!--        <th width='15%'>Location</th>-->
                                            <!--        <th width='15%'>Hours Rendered</th>-->
                                            <!--    </tr>-->
                                            <!--</thead>-->
                                            <!--<tbody id="empAttendanceReports">-->
                                            <!--</tbody>-->
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END of Attendance Management Tab (See line 424 for start of Attendance Management) -->
            <div class="tab-pane fade" id="custom-content-below-attendanceConfiguration" role="tabpanel" aria-labelledby="custom-content-below-attendanceConfiguration-tab">
                <div class="page-content container-fluid">
                    <div class="row">
                        <div class="col-md-5">
                            <!-- Floating Panels -->
                            <div class="panel">
                                <div class="panel-heading">
                                </div>
                                <div class="panel-body container-fluid">
                                    <br />
                                    <!--<button type="button" data-target="#addAttendanceLimit" data-toggle="modal" class="btn btn-info waves-effect waves-classic">Configure Employee Attendance Limit</button>-->
                                    <!--<br/><br/>-->
                                    <table class="table table-hover dataTable table-striped w-full">
                                        <thead>
                                            <tr>
                                                <th width="90%">Max Time-in Count</th>
                                                <!--<th width="60%">Employees</th>-->
                                                <th width="10%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $query_getPossibleAttendanceCount = "SELECT * FROM tbl_attendancedetails";
                                                $stmt_getPossibleAttendanceCount = $con->prepare($query_getPossibleAttendanceCount);
                                                $stmt_getPossibleAttendanceCount->execute();
                                                
                                                while($row_getPossibleAttendanceCount = $stmt_getPossibleAttendanceCount->fetch(PDO::FETCH_ASSOC)){
                                                    echo "<td width='80%'>". $row_getPossibleAttendanceCount['attendanceDetails_maxtimein'] ."</td>
                                                    <td>";
                                                    echo"</td>
                                                    <td width='20%'><button data-toggle='modal' data-target='#editTimeInLimit' type='button' class='btn btn-default' name='edit' id='edit'><i class='fas fa-edit'></i>  </button></td>
                                                    ";
                                                    
                                                ?>
                                                
<!--MODAL-->
    <div class="modal fade" id="editTimeInLimit">
        <div class="modal-dialog modal-sm">
            <form method="post" action="process_timeInLimit.php" id='processEditTimeInLimit'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="card-title">Update Time-in Limit</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                    </div>
                    <div class="modal-body ">
                        <div class='row'>
                            <div class='col-md-12'>
                                <h5>Update Max Time-in</h5>
                                <div class='alert alert-danger' id="error-update-limit" style="display:none">Input must be greater than 1</div>
                                <input class='form-control' autocomplete='off' type='number' min="1" id='updatedLimit'name='updatedLimit' value='<?php echo $row_getPossibleAttendanceCount['attendanceDetails_maxtimein']; ?>' />
                            </div>
                        </div>                                                
                    </div>
                    <div class="modal-footer">
                        <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="button" class="btn btn-primary"  name="updateLimit" onclick="updateMaxTimeIn()"> <i class="fa fa-check-square-o" ></i> Update Time-in Limit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- END MODAL -->
                                                
                                            <?php
                                                }
                                            ?>
                                                <!--<td width="50%"></td>-->
                                                <!--<td width="60%"></td>-->
                                                <!--<td width="50%"></td>-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Page -->
    

    <!-- Footer -->
    

<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
 <style>
    .swal-overlay {
  background-color: rgba(112, 101, 101, 0.45);
}
</style>
  <script src="../global/vendor/moment/moment.js"></script>
  <script type="text/javascript">
         
  $(document).ready(function() {
      
    $('.js-example-basic-single').select2({
        placeholder: 'Select an option'
    });

    $(function() {
        var interval = setInterval(function() {
            var momentNow = moment();
            $('#date').html(momentNow.format('dddd') + ' - ' + momentNow.format('MMMM DD, YYYY'));
            $('#time').html(momentNow.format('hh:mm:ss A'));
        }, 100);
    });
    
    // show/hide from to fields
    $(function showhidefromtofields(){
        var rangeValue = $('#rangeLength').val();
        
        if(rangeValue == 'custom'){
            $('#fromDateGroup').show();
            $('#toDateGroup').show();
        } else {
            $('#fromDateGroup').hide();
            $('#toDateGroup').hide();
        }
 
    });
    
//   ajax for table
    $('#search').on('click',function(){
        
         var employeeID = $('#employeeId').val();
         var range = $('#rangeLength').val();
         var from_date = document.getElementById('from_date').value;
         var to_date = document.getElementById('to_date').value;
            // alert(from_date);
        //  var from_date $('#from_date').val();
        //  var from_date = $('#from_date').toISOString();
        //  var to_date = $('#to_date').val();
         
        //  alert(to_date);
         
         if(employeeID){
            $.ajax({
                 url: "getAttendanceHistory.php",
                 method: "post",
                 data: {employeeID:employeeID, range:range, from_date:from_date, to_date:to_date},
                 dataType: "text",
                 success:function(search_result) {
                   var search_data = $.trim(search_result);
                   $('#empAttendanceReports').html(search_data);
              }
            });
         }else{
         }
     }); 
  });
  
//   void attendance
function voidAttendance()   {
    
    Swal.fire({
        title:'Do you wish to continue?',
     //  text:'You are about to time-out. If time rendered did not meet the minimum required 0.5 hrs. Continuing with time-out will void this attendance entry.',
        text:'You are about to time-out.',
        type:'warning',
        icon:'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if(result.value){
            
            
            swal(
                "Processing . . . ",{
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: false
                }
            )
            
            document.getElementById('timeOutButton').removeAttribute('type');
            document.getElementById('timeOutButton').setAttribute('type','submit');
            document.getElementById('timeOutButton').click();
        } else {
            
        }
    });
}

function showDateRange(value){
    if(value=="custom"){
        document.getElementById('fromDateGroup').style.display='block';
        document.getElementById('toDateGroup').style.display='block';
    }else{
        document.getElementById('fromDateGroup').style.display='none';
        document.getElementById('toDateGroup').style.display='none';
    }
}

function updateMaxTimeIn()   {
    $('#editTimeInLimit').modal('hide');
    Swal.fire({
        title:'Do you wish to continue?',
        text:'You are about to change the maximum time-in count.',
        type:'warning',
        icon:'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if(result.value && $('#updatedLimit').val() >= 1){
            var updatedLimit = $('#updatedLimit').val();
            $.ajax({
                method: 'POST',
                url: 'process_timeInLimit.php',
                data: {updatedLimit:updatedLimit},
                dataType:'text',
                success: function(response) {
                    $('#displayResult').html(response);
                    document.getElementById('processEditTimeInLimit').submit();
                    document.getElementById('custom-content-below-attendanceConfiguration-tab').click();
                }
            })
        
        } else {
            $('#error-update-limit').show()
            $('#editTimeInLimit').modal('show');
        }
    });
}


  </script>
  </body>
</html>
