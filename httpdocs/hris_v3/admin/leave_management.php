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
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
?>


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Leaves Management</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">Leave Approval</a></li>
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Leave Entitlement</a></li> -->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <?php


                                if (isset($_GET['leave_result'])){

                                    if($_GET['leave_result'] == "approved") {
                                        echo "<div class='alert alert-success'>Leave Approved!</div>";
                                    }
                                    elseif ($_GET['leave_result'] == "failed") {
                                        echo "<div class='alert alert-error'>Approval Failed!</div>";
                                    }elseif ($_GET['leave_result'] == "used_all_leave") {
                                        echo "<div class='alert alert-error'>This Employee has already used up all of his allocated leave.!</div>";
                                    }elseif ($_GET['leave_result'] == "exceeded") {
                                        echo "<div class='alert alert-error'>Duration has exceeded his allocated leave.!</div>";
                                    }elseif ($_GET['leave_result'] == "declined") {
                                            echo "<div class='alert alert-info'>Leave Declined!</div>";
                                    }elseif ($_GET['leave_result'] == "decline_failed") {
                                            echo "<div class='alert alert-error'>Leave Declining Failed</div>";
                                    }
                }

                                 ?>
                                <table  class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                  <thead>
                                      <tr>
                                      <!-- <th style="display:none;">leaveDetailsID</th> -->
                                      <th>Date Filed</th>
                                      <th>Name</th>
                                      <th>Leave Type</th>
                                      <th>Duration</th>
                                      <th>From</th>
                                      <th>To</th>
                                      <th>Status</th>
                                      <th>Comment</th>
                                      <th>Action</th>
                                      </tr>
                                  </thead>

                              <?php
                              // select all data

                              $session = $_SESSION['user_id'];
                              $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leave ON tbl_leavedetails.leaveID = tbl_leave.leaveID INNER JOIN tbl_employees ON tbl_leavedetails.employeeCode = tbl_employees.employeeCode WHERE approval = '$session' AND leaveStatus = 'Pending'";
                              $stmt = $con->prepare($query);
                              $stmt->execute();
                              $num = $stmt->rowCount();
                              if($num>0){

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                  // <td><button type='button'  data-toggle='modal' data-target='#approveButton".$row['leaveDetailsID']."' class='btn btn-success ' data-id=" .  $row['leaveDetailsID'] . "><i class='fa fa-check-square-o'></i></button> <button class='btn btn-danger edit' data-toggle='modal' data-target='#declineButton".$row['leaveDetailsID']."' data-id=" .  $row['leaveDetailsID'] . "><i class='fa fa-times-circle'></i></button>
                                  // </td>

                                  echo "        <tr>

                                                    <td>" .date('F d, Y',strtotime($row['dateFiled'])) . "</td>
                                                  <td>" . $row["firstName"] . ' ' . $row['lastName']  . "</td>
                                                  <td>" . $row['leaveName'] . "</td>
                                                  <td>" . $row['duration'] . '  Days' . "</td>
                                                  <td>" . date('F d, Y',strtotime($row['leaveFrom'])) . "</td>
                                                    <td>" .date('F d, Y',strtotime($row['leaveTo'])) . "</td>

                                                  <td>" . $row['leaveStatus'] . "</td>
                                                  <td>" . $row['reason'] . "</td>

                                                  <td class='actions'>
                                    <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
                                       data-original-title='Edit' data-toggle='modal' data-target='#approveButton".$row['leaveDetailsID']."' data-id=" .  $row['leaveDetailsID'] . " ><i class='icon md-edit' aria-hidden='true'></i></a>
                                    <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
                                       data-original-title='Remove' data-toggle='modal' data-target='#declineButton".$row['leaveDetailsID']."' data-id=" .  $row['leaveDetailsID'] . "><i class='icon md-delete' aria-hidden='true'></i></a>
                                  </td>

                                                </tr>";

  ?>
                                                <!-- //------------------------------------------- -->
                                                <div class="modal fade" id="approveButton<?php echo $row['leaveDetailsID'];?>">
                                                    <div class="modal-dialog">
                                                        <form method="post" action="process_leave_approval.php">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                              <h3 class="card-title">Approve Leave</h3>
                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span></button>
                                                              <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                            </div>
                                                            <div class="modal-body">
                                                              <p>Do you want to approve the leave?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                              <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                              <input type="hidden" name="leaveType" value="<?php echo $row['leaveID'] ?>">
                                                                <input type="hidden" name="employeeCode" value="<?php echo $row['employeeCode'] ?>">
                                                                  <input type="hidden" name="leaveDetailsID" value="<?php echo $row['leaveDetailsID'] ?>">
                                                              <button type="submit" class="btn btn-success " name="approve"><i class="fa fa-check-square-o"></i> Approve</button>
                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="declineButton<?php echo $row['leaveDetailsID'];?>">
                                                    <form method="post" action="process_leave_approval.php">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                              <h3 class="card-title">Decline Leave</h3>
                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span></button>
                                                              <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                            </div>
                                                            <div class="modal-body">
                                                              <p>Do you want to decline the leave?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                              <button type="button" class="btn btn-default  pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                              <input type="hidden" name="leaveType" value="<?php echo $row['leaveID'] ?>">
                                                                <input type="hidden" name="employeeCode" value="<?php echo $row['employeeCode'] ?>">
                                                                  <input type="hidden" name="leaveDetailsID" value="<?php echo $row['leaveDetailsID'] ?>">
                                                              <button type="submit" class="btn btn-danger" name="decline"><i class='fa fa-times-circle'></i> Decline</button>
                                                              </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>

  <?php

  ?>
                                                <!-- //------------------------------------------- -->
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




<!-- END ADD MODAL-->

    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
  </body>
</html>
