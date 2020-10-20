<!-- line 301 apply leave modal -->
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
$CURRENT_PAGE="Leave Application";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
?>


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Filing & Monitoring</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <!--<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"-->
                              <!--    aria-controls="cardTab1" role="tab" aria-expanded="true">Leave Application</a></li>-->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Overtime Application</a></li> -->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <?php


                                // to prevent undefined index notice
                                $message = isset($_GET['request']) ? $_GET['request'] : "";

                                if($message=='success'){
                                    echo "<div class='alert alert-success'>Leave Successfully Added!</div>";
                                }

                                else if($message=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to file leave.</div>";
                                }elseif ($message=='used_all_leave') {
                                  echo "<div class='alert alert-danger'>Unable to file leave. You have used up all of your available leave. </div>";
                                }elseif ($message=='exceeded') {
                                  echo "<div class='alert alert-danger'>Unable to file leave. Your leave duration has exceeded your leave limit</div>";
                                }elseif ($message=='cancelled') {
                                  echo "<div class='alert alert-info'>Leave Cancelled</div>";
                                }elseif ($message=='cancelled_failed') {
                                  echo "<div class='alert alert-danger'>Cancelling leave failed.</div>";
                                }elseif ($message=='overtime_success') {
                                  echo "<div class='alert alert-success'>Overtime Successfully Added.</div>";
                                }elseif ($message=='overtime_failed') {
                                  echo "<div class='alert alert-danger'>Overtime Request Failed.</div>";
                                }elseif ($message=='overtime_cancelled_failed') {
                                    echo "<div class='alert alert-danger'>Cancelling Overtime Failed.</div>";
                                }elseif ($message=='overtime_cancelled_success') {

                                      echo "<div class='alert alert-info'>Overtime Request Cancelled.</div>";
                                }

                             ?>
                             <div class="row">
                             <div class="col-md-2">
                                 <button type="button"  data-toggle="modal" data-target="#applyLeave" class="btn btn-block btn-info ">File Leave</button>
                             </div>
                             <div class="col-md-2">
                               <button type="button"  data-toggle="modal" data-target="#leaveViewCount" class="btn btn-block btn-info ">View Leave Count</button>
                           </div>
                           </div>
                                <br>
                                <table  class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                  <thead>
                                      <tr>
                                          <th>Date Filed</th>
                                          <th>Leave Type</th>

                                          <th>Duration</th>
                                          <th>From</th>
                                          <th>To</th>
                                            <th>Reason</th>
                                          <th>Status</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <?php
                                  // select all data
                                  $session = $_SESSION['employeeID'];
                                  $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leave ON tbl_leavedetails.leaveID = tbl_leave.leaveID WHERE employeeID = '$session' ";
                                  $stmt = $con->prepare($query);
                                  $stmt->execute();
                                  $num = $stmt->rowCount();
                                  $output ="";
                                    $onLeave = "";
                                    $dateNow = date('Y-m-d');
                                  if($num>0){

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                      $leaveFrom = $row['leaveFrom'];
                                      // $row['leaveTo'];
                                      $leaveStatus  = $row['leaveStatus'];
                                      $leaveTo = $row['leaveTo'];
                                      $start = strtotime($leaveTo);
                                      $stop = strtotime($leaveFrom);
                                      for ($seconds=$start; $seconds<=$stop; $seconds+=86400){
                                          $date = date("Y-m-d", $seconds);
                                           $date;
                                          if ($date == $dateNow){
                                           $onleave ="Yes";
                                          }else {
                                          $onLeave = "No";
                                          }
                                         }
                                         $onLeave;

                                      if ($leaveStatus == "Cancelled" || $leaveStatus == "Declined"  || $dateNow > $leaveFrom){
                                          $output = "<button type='button'  disabled data-toggle='modal' data-target='#cancelButton".$row['leaveDetailsID']."' class='btn btn-block btn-danger waves-effect waves-classic' data-id=" .  $row['employeeID'] . "><i class='fa fa-times-circle'></i> Cancel</button>";
                                      }else{
                                          $output = "<button type='button'   data-toggle='modal' data-target='#cancelButton".$row['leaveDetailsID']."' class='btn btn-block btn-danger waves-effect waves-classic' data-id=" .  $row['employeeID'] . "><i class='fa fa-times-circle'></i> Cancel</button>";
                                      }



                                      echo "        <tr>

                                                      <td>" . date('F d, Y',strtotime($row['dateFiled'])) . "</td>
                                                        <td>" . $row['leaveName'] . "</td>
                                                          <td>" . $row['duration'] . ' Days' . "</td>
                                                      <td>" . date('F d, Y',strtotime($row["leaveFrom"])) . "</td>
                                                      <td>" . date('F d, Y',strtotime($row["leaveTo"]))  . "</td>

                                                      <td>" . $row['reason'] . "</td>
                                                      <td>" . $row["leaveStatus"] . "</td>
                                                      <td>
                                                     $output
                                                      </td>
                                                    </tr>";
                                                    ?>
<!-- <button type='button'  data-toggle='modal' data-target='#editButton".$row['leaveDetailsID']."' class='btn btn-success btn-sm edit btn-flat' data-id=" .  $row['employeeID'] . "><i class='fa fa-check-square-o'></i> Update</button> -->
                                                    <div class="modal fade" id="editButton<?php echo $row['leaveDetailsID'];?>">
                                                        <div class="modal-dialog">
                                                            <form method="post" action="process_request_leave.php">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                  <h3 class="card-title">Update Leave</h3>
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                      <span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                  <p>Do you want to approve the leave? <?php echo $row['leaveDetailsID'] ?></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                  <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                  <input type="hidden" name="leaveType" value="<?php echo $row['leaveID'] ?>">
                                                                    <input type="hidden" name="employeeID" value="<?php echo $row['employeeID'] ?>">
                                                                      <input type="hidden" name="leaveDetailsID" value="<?php echo $row['leaveDetailsID'] ?>">
                                                                  <button type="submit" class="btn btn-success btn-flat" name="approve"><i class="fa fa-check-square-o"></i> Approve</button>
                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="cancelButton<?php echo $row['leaveDetailsID'];?>">
                                                        <div class="modal-dialog">
                                                            <form method="post" action="process_request_leave.php">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                  <h3 class="card-title">Cancel Leave</h3>
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                      <span aria-hidden="true">&times;</span></button>
                                                                  <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                  <p>Do you want to cancel the leave?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                  <input type="hidden" name="leaveType" value="<?php echo $row['leaveID'] ?>">
                                                                    <input type="hidden" name="employeeID" value="<?php echo $row['employeeID'] ?>">
                                                                      <input type="hidden" name="leaveDetailsID" value="<?php echo $row['leaveDetailsID'] ?>">
                                                                  <button type="submit" class="btn btn-danger" name="cancelLeave"><i class="fa fa-check-square-o"></i> Cancel Leave</button>
                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php

                                          }
                                          // echo "</table>";
                                      } else {

                                         
                                          // echo "</table>";
                                      }
                                ?>
                                </table>
                              </div>
                              <div class="tab-pane" id="cardTab2" role="tabpanel">

                              </div>
                              <div class="tab-pane" id="cardTab3" role="tabpanel">
                                <h4>Incurrunt latinam</h4>
                                Incurrunt latinam, faciendi dedecora evertitur delicatissimi, afficit noctesque
                                detracta illustriora epicurum contenta rogatiuncula dolores
                                perspecta indocti, eveniunt confirmatur tractat consuevit durissimis
                                iuvaret coercendi familiarem. Dolere prima fortunae intellegamus
                                vix porro huic errorem molestum, graecos deinde effugiendorum
                                aliter appetendum afferrent eosdem.
                              </div>
                            </div>
                          </div>
                        </div>

                      <!-- End Panel Floating Labels -->
                    </div>
        </div>
      </div>
    </div>
    <!-- End Page -->

    <div class="modal fade" id="leaveViewCount">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="card-title">My Leave Count</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                </div>
                <div class="modal-body">
                  <div class="card-body table-responsive p-0"><br/>
            <table id='example3' class='table table-hover table-striped'>
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Leave Credits</th>
                                <th>Used</th>
                                <th>Leave Balance</th>
                            
                            </tr>
                        </thead>
                          <?php
                          // select all data
                          $user = $_SESSION['employeeID'];
                          $query = "SELECT * FROM tbl_leaveinfo INNER JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID  WHERE employeeID = '$user' ";
                          $stmt = $con->prepare($query);
                          $stmt->execute();
                          $num = $stmt->rowCount();
                          if($num>0){

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                              echo "        <tr>
                                              <td>" . $row['leaveName'] . "</td>
                                              <td>" . $row["leaveCount"] .  "</td>
                                              <td>" . $row["leaveUsed"] . "</td>
                                                <td>" . $row["leaveRemaining"] . "</td>
                                               
                                            </tr>";

                                  }
                                  echo "</table>";
                              } else {

                                  echo "<div class='alert alert-error'>No Results Found!</div>";
                                  echo "</table>";
                              }
                        ?>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>

                </div>
            </div>
        </div>
    </div>
    <!--cancel leave modal -->

    <!-- apply leave modal -->

    <div class="modal fade"  id="applyLeave">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Apply Leave</h4>
              </div>
                <div class="modal-body">
                  <form enctype = "multipart/form-data" method="post" action='process_request_leave.php'  autocomplete="off">
                              <div class="form-group row">
                                <label class="col-md-3 col-form-label">Leave Type: </label>
                                <div class="col-md-9">
                                  <select name="leaveType" required data-plugin="select2" class="form-control" style="width: 100%;">

                                    <?php
                                      // select all data
                                      $user = $_SESSION['employeeID'];
                                      $zero = 0;
                                      $query = "SELECT * FROM tbl_leaveinfo INNER JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID WHERE employeeID='$user' AND leaveRemaining > '$zero'";
                                      $stmt = $con->prepare($query);
                                      $stmt->execute();

                                      $query2 = "SELECT * FROM tbl_leaveinfo WHERE employeeID='$user'";
                                      $stmt2 = $con->prepare($query2);
                                      $stmt2->execute();
                                      $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                                      $remaining = $row2['leaveRemaining'];

                                      $num = $stmt->rowCount();
                                      // check if more than 0 record found
                                      if($num>0){
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                          ?>
                                        <option value="<?php echo $row['leaveID'];?>"><?php echo $row['leaveName'];?></option>
                                        <!-- end of database -->
                                       <?php
                                       }
                                       // if no records found
                                       }else{
                                           ?>
                                         <option value="<?php echo 3 ?>"><?php echo "Leave Without Pay"?></option>
                                         <?php
                                       }
                                      ?>
                                  </select>

                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-md-3 col-form-label">Date: </label>
                                <div class="col-md-9">
                                    <?php 
                                    $dateToday = date('m/d/Y');
                                    ?>
                                  <div class="input-daterange"  data-date-start-date="<?php echo $dateToday?>"  data-plugin="datepicker">
                                    <div class="input-group">
                                      <span class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                      </span>
                                      <input type="text" class="form-control" required id="from" name="startDate" />
                                    </div>
                                    <div class="input-group">
                                      <span class="input-group-addon">to</span>
                                      <input type="text" class="form-control" required id="to"name="endDate" />
                                    </div>
                                  </div>

                                </div>
                              </div>
                               <div class="form-group row">
                                <label class="col-md-3 col-form-label">Leave Hours: </label>
                                <div class="col-md-9">
                                <input type="number"  min="0.5" max="1" step="0.5"class="form-control" name="hours"value="1" >
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-md-3 col-form-label">Reason: </label>
                                <div class="col-md-9">
                                    <textarea autocomplete="off" name="reason" required class="form-control" rows="5" id="comment"></textarea>

                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-md-3 col-form-label">Approver: </label>
                                <div class="col-md-9">
                                  <?php
                                    $user = $_SESSION['employeeID'];
                                    $query1 = "SELECT * FROM tbl_employees WHERE employeeID = '$user'";
                                    $stmt1 = $con->prepare($query1);
                                    $stmt1->execute();
                                    $rows = $stmt1->fetch(PDO::FETCH_ASSOC);

                                    $supervisor = $rows['reportingTo'];
                                    // select all data
                                    $query = "SELECT * FROM tbl_employees JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID WHERE employeeID = '$supervisor'";
                                    $stmt = $con->prepare($query);
                                    $stmt->execute();
                                    $num = $stmt->rowCount();
                                    // check if more than 0 record found
                                    if($num>0){
                                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        ?>
                                        <input type="text"  readonly class="form-control" id="inputDisabled" value=" <?php echo $row['firstName'] . ' ' . $row['lastName'] . ' --- ' . $row['positionName'];?>" >
                                        <input type="hidden" name="search" value="<?php echo $row['employeeID'];?>">

                                     <?php
                                     }
                                     // if no records found
                                     }else{
                                       echo "no records found";
                                     }
                                    ?>

                                </div>
                              </div>
                </div>
                <div class="modal-footer">
                  <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                  <button type="submit" class="btn btn-primary" name="requestLeave"> <i class="fa fa-check-square-o" ></i> Request Leave</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="applyOvertime">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="card-title">Overtime Request</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" action="requestOvertime.php">
                  <div class='table-responsive'>
                  <table class='table'>
                  <tr>
                    <th>Hours:</th>

                    <td><select name="hours" class="form-control select2" style="width: 25%;" required >
                    <option selected readonly disabled value=""> Hours</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>

                    </select>
                    <select name="minutes" class="form-control select2" style="width: 25%;" required >
                    <option selected readonly disabled value=""> Minutes</option>
                    <option value="00">00</option>
                    <option value="30">30</option>
                    </select></td>
                    <td></td>
                  </tr>
                  <tr>
                    <th>Date:</th>
                      <td>    <input required class="form-control float-right clsDatePicker datepicker" type="text" id="overtimeDate" name="overtimeDate" placeholder="Select Date" autocomplete="off" onkeypress="return restrictCharacters(this, event, dateTry);"  /></td>
                  </tr>
                  <tr>
                    <th>Time From:</th>
                        <td>    <div class="bootstrap-timepicker">
                              <div class="form-group">


                                <div class="input-group">
                                  <input type="text" name="timeFrom" required onkeypress="return restrictCharacters(this, event, dateOnly);" class="form-control timepicker">

                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                  </div>
                                </div>
                                <!-- /.input group -->
                              </div>
                              <!-- /.form group -->
                            </div></td>
                  </tr>
                  <tr>
                    <th>Reason:</th>
                      <td><textarea class="form-control" required autocomplete="off" maxlength="100" name="reason" id="overtimeReason"></textarea></td>
                  </tr>

                 </table>
               </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                  <button type="submit" class="btn btn-success btn-flat" name="requestOvertime"> <i class="fa fa-check-square-o" ></i> Request Overtime</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
<!-- END ADD MODAL-->

    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
 <script>
//  $(document).ready(function(){
//   $('.input-daterange input').each(function() {
//      $(this).daterangepicker({
//     minDate:0,
//   });
// });
//  });

    
// });
 </script>
 <script>
 $(document).ready(function(){
     $("#from").datepicker({
       minDate: 0,
         format: 'yyyy/mm/dd',
         maxDate: "+365D",
         numberOfMonths: 1,

         onSelect: function(selected) {
           $("#to").datepicker("option","minDate", selected)
         }

     });
     $("#to").datepicker({
       minDate: 0,
         format: 'yyyy/mm/dd',
         maxDate:"+365D",
         numberOfMonths: 1,
         onSelect: function(selected) {
            $("#from").datepicker("option", selected)
         }
     });

     $("#overtimeDate").datepicker({
         minDate: 0,
         format: 'yyyy/mm/dd',
         maxDate: "+365D",
         numberOfMonths: 1,


     });

 });

 $(function() {
     $("body").delegate("#datepicker, #from", "focusin", function(){
         $(this).datepicker();
     });
 });
 </script>

 <script>
 $(function() {
   $("body").delegate("#from", "focusin", function(){
       $(this).datepicker();
   });
 });
 $(function() {
   $("body").delegate("#to", "focusin", function(){
       $(this).datepicker();
   });
 });
 </script>
  </body>
</html>
