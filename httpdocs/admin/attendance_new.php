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
                            <div class="row-md-6">
                                <?php include ('../includes/clock/clock.php');?>
                            </div>
                            <div class="row-md-6">
                                <h1 style="text-align:center" id="date"></h1>
                                <h1 style="text-align:center" id="time"></h1>
                                <br>
                            </div>
                            
                            <?php 
                                $userCode = $_SESSION['user_id'];
                                $query = "SELECT * FROM tbl_employees WHERE employeeCode=:user";
                                $stmt = $con->prepare($query);
                                $stmt->bindParam(':user', $userCode);
                                $stmt->execute();
                                $num = $stmt->rowCount();
                                
                                if($num>0) {
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $employeeID = $row['employeeID'];
                                    $date_now = date('Y-m-d');
                                    $lognow = date('H:i:s');
                                    $sched = $row['coreTimeID'];
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
                </div>
                <!-- Weekly Attendance History Table -->
                <div class="col-md-8">
                    <!-- Panel Floating Labels -->
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title text-danger"></h3>
                        </div>
                        <div class="panel-body container-fluid">
                            <div>
                                <?php
                                    include ('get_week_range.php');
                                ?>
                                <h2>Attendance History: WE <?php echo $displayWeekEnd ?></h2>
                                <br> 
                           
                            <?php
                            
                            $currentDay = date("Y-m-d",$today);
                            $userID = $_SESSION['user_id'];
                            $query = "SELECT * FROM tbl_attendance INNER JOIN tbl_employees ON tbl_attendance.employeeID = tbl_employees.employeeID
                                        WHERE employeeCode='$userID' AND attendanceDate='$currentDay' ORDER BY attendanceDate, attendance_timeIn DESC";
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
                                
                                $holdDate=" ";
                                $rowCounter= 0;
                                $totalRenderedHours=0;
                                
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    $currentDate = $row["attendanceDate"];
                                    $timeInTime=strtotime($row["attendance_timeIn"]);
                                    $formattedTimeIn=date("h:i:s A", $timeInTime);
                                    $timeOutTime=strtotime($row["attendance_timeOut"]);
                                    $formattedTimeOut=date("h:i:s A", $timeOutTime);
                                   
                                    echo "<tr>";
                                            if($holdDate!=$currentDate || $holdDate==' '){
                                            echo 
                                                "<td width='15%'>" . $row["attendanceDate"] . "</td>";
                                            }else{
                                                echo "<td width='15%'></td>";
                                            }
                                            echo "
                                            
                                            <td width='5%'></td>
                                            <td width='20%'>" . $formattedTimeIn . "</td>
                                            <td width='20%'>" . $formattedTimeOut . "</td>
                                            <td width='15%'>" . $row["attendance_location"] . "</td>
                                            <td width='15%'>" . $row["hourWorked"] . "</td>
                                        </tr>
                                    
                                    ";
                                    $holdDate=$currentDate;
                                    $totalRenderedHours += $row["hourWorked"];
                                    $rowCounter+=1;
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
            </div>
        </div>
    </div>
    
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
