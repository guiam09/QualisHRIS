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

?>

    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title">Attendance</h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-4">
                      <!-- Panel Floating Labels -->
                      <div class="panel">
                        <div class="panel-heading">
                          <h3 class="panel-title text-danger"></h3>
                        </div>
                        <div class="panel-body container-fluid">
                          <div>
                            <h1 style="text-align:center" id="date"></h1>
                            <h1 style="text-align:center" id="time"></h1>
                            <br> 
                          </div>
                          <?php
                          $userCode = $_SESSION['user_id'];


                            $query = "SELECT * FROM tbl_employees WHERE employeeCode =:user";
                            $stmt = $con->prepare($query);
                            $stmt->bindParam(':user', $userCode);
                            $stmt->execute();
                            $num = $stmt->rowCount();
                            if($num>0){

                              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                              $employeeID = $row['employeeID'];
                              $date_now = date('Y-m-d');
                              $lognow = date('H:i:s');
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

                                          $query_checktimein = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendanceDate = '$date_now' AND attendance_timeIn IS NOT NULL AND attendance_timeOut = '00:00:00' ";
                                          $stmt_checktimein = $con->prepare($query_checktimein);
                                          $stmt_checktimein->execute();
                                          $num_checktimein = $stmt_checktimein->rowCount();

                                          if($num_checktimein > 0){
                                              echo "<div class='alert alert-danger'>You have already timed-in.</div>";
                                          } else {

                                              $query_log = "SELECT * from tbl_attendancedetails WHERE employeeID = '$employeeID'";
                                              $stmt_log = $con->prepare($query_log);
                                              $stmt_log->execute();
                                              $row_log = $stmt_log->fetch(PDO::FETCH_ASSOC);
                                              $maxlogs = $row_log['attendanceDetails_maxtimein'];

                                              $query_logCounter = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendanceDate = '$date_now' AND attendance_timeIn IS NOT NULL AND attendance_timeOut IS NOT NULL";
                                              $stmt_logCounter = $con->prepare($query_logCounter);
                                              $stmt_logCounter->execute();
                                              $num_logCounter = $stmt_logCounter->rowCount();
                                              if($num_logCounter > 0) {
                                                  while($row_logCounter = $stmt_logCounter->fetch(PDO::FETCH_ASSOC)) {
                                                      $currentLog = $row_logCounter['timeinLog'];
                                                  }
                                                  $log = 1 + $currentLog;

                                                  if($log > 10){
                                                      echo "<div class='alert alert-danger'>You have already exceeded your max time-in count.</div>";
                                                  } else {
                                                      $query = "INSERT INTO tbl_attendance
                                                              SET employeeID=:employeeID, attendanceDate=:dateNow, attendance_timeIn=:timeNow, status=:status, attendance_location=:location, timeinLog=:log
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
                                                          echo "<div class='alert alert-success'>Time In Success!</div>";
                                                      }else{
                                                          echo "<div class='alert alert-danger'>Time In Failed!</div>";
                                                      }

                                                  }
                                              } else {

                                                  $log = 1;

                                                  $query = "INSERT INTO tbl_attendance
                                                              SET employeeID=:employeeID, attendanceDate=:dateNow, attendance_timeIn=:timeNow, status=:status, timeinLog=:log
                                                              ";
                                                  $stmt = $con->prepare($query);
                                                  $status = "Online";

                                                  $stmt->bindParam(':employeeID', $employeeID);
                                                  $stmt->bindParam(':dateNow', $date_now);
                                                  $stmt->bindParam(':timeNow', $lognow);
                                                  $stmt->bindParam(':status', $status);
                                                  $stmt->bindParam(':log', $log);

                                                  if($stmt->execute()){
                                                      echo "<div class='alert alert-success'>Time In Success!</div>";
                                                  }else{
                                                      echo "<div class='alert alert-danger'>Time In Failed!</div>";
                                                  }
                                              }
                                          }
                                      }
                                  } else if (isset($_POST['timeOut'])){
                                      $query_checktimeout = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendanceDate = '$date_now' AND attendance_timeIn IS NOT NULL AND attendance_timeOut = '00:00:00'";
                                      $stmt_checktimeout = $con->prepare($query_checktimeout);
                                      $stmt_checktimeout->execute();
                                      $num_checktimeout = $stmt_checktimeout->rowCount();

                                      if($num_checktimeout < 1){
                                          echo "<div class='alert alert-danger'>No time in found. Please Time-In first.</div>";
                                      } else {
                                          $row_checktimeout = $stmt_checktimeout->fetch(PDO::FETCH_ASSOC);

                                          $query = "UPDATE tbl_attendance SET attendance_timeOut = '$lognow', attendance_location = '$loc', status='Offline' WHERE attendanceID = '".$row_checktimeout['attendanceID']."'";

                                          $stmt = $con->prepare($query);

                                          if($stmt->execute()){
                                              echo "<div class='alert alert-success'>Time Out Success!</div>";

                                              $query5 = "SELECT * FROM tbl_attendance WHERE attendanceID = '".$row_checktimeout['attendanceID']."'";
                                              $stmt5 = $con->prepare($query5);
                                              $stmt5->execute();
                                              $urow = $stmt5->fetch(PDO::FETCH_ASSOC);

                                              $time_in = $urow['attendance_timeIn'];
                                              $time_out = $urow['attendance_timeOut'];

                                              $sql5 = "SELECT * FROM tbl_employees LEFT JOIN tbl_coretime ON tbl_coretime.coretimeID=tbl_employees.coretimeID WHERE tbl_employees.employeeID = '$employeeID'";
                                              $stmt1 = $con->prepare($sql5);
                                              $stmt1->execute();
                                              $srow = $stmt1->fetch(PDO::FETCH_ASSOC);

                                              // if($srow['timeIn'] > $urow['timeIn']){
                                              //   $time_in = $srow['timeIn'];
                                              // }
                                              //
                                              // if($srow['timeOut'] < $urow['timeIn']){
                                              //   $time_out = $srow['timeOut'];
                                              // }
                                              $time_in = new DateTime($time_in);
                                              $time_out = new DateTime($time_out);
                                              $interval = $time_in->diff($time_out);
                                              $hrs = $interval->format('%h');
                                              $mins = $interval->format('%i');
                                              $mins = $mins/60;
                                              $int = $hrs + $mins;
                                              if($int > 4){
                                                $int = $int - 1;
                                              }

                                              $query_hoursWorked = "SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' AND attendanceDate = '$date_now'";
                                              $stmt_hoursWorked = $con->prepare($query_hoursWorked);
                                              $stmt_hoursWorked->execute();
                                              $num_hoursWorked = $stmt_hoursWorked->rowCount();

                                              if($num_hoursWorked>0) {
                                                  $row_hoursWorked = $stmt_hoursWorked->fetch(PDO::FETCH_ASSOC);
                                                  $completeHours = $row_hoursWorked['hourWorked'];
                                                  $int = $int + $completeHours;
                                              }

                                              $int = round($int,2);

                                              $query = "UPDATE tbl_attendance SET hourWorked = '$int' WHERE employeeID = '$employeeID' AND attendanceDate = '$date_now'";
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
                          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                              <div class="row">
                                  <input type = "hidden" name ="time" value = "<?php echo date("His"); ?>">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="submit" name="timeIn" class="btn btn-block btn-info">Time-In</button>
                                                    </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="submit" name="timeOut" class="btn btn-block btn-info">Time-Out</button>
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
                            <h1>Attendance History</h1>
                            <br> 
                           
                            <?php
                            include ('get_week_range.php');
                            $currentDay = date("Y-m-d",$today);
                            $userID = $_SESSION['user_id'];
                            $query = "SELECT * FROM tbl_attendance INNER JOIN tbl_employees ON tbl_attendance.employeeID = tbl_employees.employeeID
                                        INNER JOIN tbl_coretime ON tbl_employees.coreTimeID = tbl_coretime.coreTimeID 
                                        WHERE employeeCode='$userID' AND attendanceDate='$currentDay' ";
                            $stmt = $con->prepare($query);
                            $stmt->execute();
                            
                            $num = $stmt->rowCount();
                        
                            if($num>0){
                                
                                echo " 
                                    <table class='table table-hover dataTable table-striped ' id='exampleTableTools2'>
                                      <thead>
                                        <tr>
                                            <th width='15%'>Date</th>
                                            <th width='5%'></th>
                                            <th width='20%'>Time-In</th>
                                            <th width='20%'>Time-Out</th>
                                            <th width='15%'>Location</th>
                                            <th width='15%'>Hours Rendered</th>
                                        </tr>
                                      </thead>
                                
                                ";
                                
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                  
                                    $timeInTime=strtotime($row["attendance_timeIn"]);
                                    $formattedTimeIn=date("h:i:s A", $timeInTime);
                                    $timeOutTime=strtotime($row["attendance_timeOut"]);
                                    echo $formattedTimeOut=date("h:i:s A", $timeOutTime);
                                    if($formattedTimeOut == "12:00:00 AM" && $formattedTimeIn != "12:00:00 AM"){
                                        $formattedTimeOut == " ";
                                    }
                                    echo "
                                        <tr>
                                            <td width='15%'>" . $row["attendanceDate"] . "</td>
                                            <td width='5%'></td>
                                            <td width='20%'>" . $formattedTimeIn . "</td>
                                            <td width='20%'>" . $formattedTimeOut . "</td>
                                            <td width='15%'>" . $row["attendance_location"] . "</td>
                                            <td width='15%'>" . $row["hourWorked"] . "</td>
                                        </tr>
                                    
                                    ";
                                    $totalRenderedHours = $row["hourWorked"];
                                }
                                echo "
                                <tfoot>
                                <tr>
                                    <td width='15%'></td>
                                    <td width='5%'></td>
                                    <td width='20%'></td>
                                    <td width='20%'></td>
                                    <td width='15%'>TOTAL</td>
                                    <th width='15%'>" . $totalRenderedHours . "</th>
                                </tr>
                                </tfoot>
                                </table>
                                ";
                            } else {
                                
                                echo "You have yet to time in today.";
                                
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
    <!-- End Page -->


    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
  <script src="../global/vendor/moment/moment.js"></script>
  <script type="text/javascript">
  $(function() {
    var interval = setInterval(function() {
      var momentNow = moment();
      $('#date').html(momentNow.format('dddd').substring(0,3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
      $('#time').html(momentNow.format('hh:mm:ss A'));
    }, 100);


  });
  </script>
  </body>
</html>
