<?php
include ('../includes/header.php');
 ?>

<?php

include ('../includes/navbar.php');
include ('../includes/sidebar.php');
?>
    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Attendance List</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">
                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">Attendance List</a></li>
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Leave Entitlement</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <?php
                                include '../db/connection.php';
                                // select all data
                                $query = "SELECT * FROM tbl_attendance INNER JOIN tbl_employees ON tbl_attendance.employeeID = tbl_employees.employeeID
                                INNER JOIN tbl_coretime ON tbl_employees.coreTimeID = tbl_coretime.coreTimeID ";
                                $stmt = $con->prepare($query);
                                $stmt->execute();

//                                $query2 = "SELECT * FROM tbl_attendance";
//                                $stmt2 = $con->prepare($query2);
//                                $stmt2->execute();
//                                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
//                                echo $timeIn = date("h:i A", strtotime($row2['attendance_timeIn']));
//                                echo $timeOut = date("h:i A", strtotime($row2['attendance_timeOut']));

                                $num = $stmt->rowCount();
                                if($num>0){
                                  echo "    <table class='table table-hover dataTable table-striped w-full' id='exampleTableTools'>
                                      <thead>
                                          <tr>
                                              <th>Employee Code</th>
                                                <th>Employee Name</th>
                                              <th>Attendance Date</th>
                                              <th>Time In</th>
                                              <th>Time Out</th>
                                              <th>Hours Worked (PERIOD)</th>
                                              <th>Hours Worked (DAY)</th>
                                              <th>Core Time</th>
                                          </tr>
                                      </thead>";
                                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    $timeIn = date("h:i A", strtotime($row['attendance_timeIn']));
                                    $timeOut = date("h:i A", strtotime($row['attendance_timeOut']));
                                    $coreStart = date("h:i A", strtotime($row['timeIn']));
                                    $core_start = new DateTime($coreStart);
                                    $time_in = new DateTime($timeIn);
                                    $time_out = new DateTime($timeOut);
                                    $interval = $time_in->diff($time_out);
                                    $hrs = $interval->format('%h');
                                    $mins = $interval->format('%i');
                                    $mins = $mins/60;
                                    $int = $hrs + $mins;
                                    if($int > 4){
                                      $int = $int - 1;
                                    }

                                    $status = ($time_in < $core_start)? "<span class='badge badge-round badge-success'>Ontime</span>": "<span class='badge badge-round badge-danger'>Late</span>";

                                    echo "        <tr>
                                                    <td>" . $row["employeeCode"] . "</td>
                                                      <td>" . $row["firstName"] .' '. $row['lastName'] ."</td>
                                                    <td>" . $row["attendanceDate"] . "</td>
                                                    <td>" . $timeIn . "  $status </td>
                                                    <td>" . $timeOut . "</td>
                                                    <td>" . round($int,2) . "</td>
                                                    <td>" . $row["hourWorked"] . "</td>
                                                    <td>" . $row["timeIn"] . ' - ' . $row['timeOut'] . "</td>

                                                  </tr>";

                                        }
                                        echo "</table>";
                                    } else {

                                        echo "<div class='alert alert-error'>No Results Found!</div>";
                                        echo "</table>";

                                    }

                                ?>

                              </div>
                              <div class="tab-pane" id="cardTab2" role="tabpanel">
                                <h4>Protervi dissensio</h4>
                                Protervi dissensio consuetudine equos publicam ingenia. Voluptatibus legendus initia
                                confirmare sententiam. Desistunt possint habeatur dediti dubio,
                                triarium is offendimur reprehenderit exercitus laudabilis motus
                                celeritas, utrum dissentio renovata, habet partus natus. Iustius
                                disserunt, quantum ennii admodum divinum mortem elaborare primum
                                autem.
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


<!-- ADD MODAL -->
<div class="modal fade" id="exampleFormModal" aria-hidden="false" aria-labelledby="exampleFormModalLabel"
  role="dialog" tabindex="-1">
  <div class="modal-dialog modal-simple">
    <form class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="exampleFormModalLabel">Add Designation</h4>
      </div>
      <div class="modal-body">
        <form>
                   <div class="form-group form-material row">
                     <label class="col-md-4 col-form-label">Designation Name: </label>
                     <div class="col-md-8">
                       <input type="text" class="form-control" name="name" placeholder="Designation Name" autocomplete="off"
                       />
                     </div>
                   </div>
                   <div class="form-group form-material row">
                     <label class="col-md-4 col-form-label">Designation Description: </label>
                     <div class="col-md-8">
                       <textarea class="form-control" placeholder="Briefly Describe"></textarea>
                     </div>
                   </div>
                    <div class="form-group form-material row">
                       <label class="col-md-4 col-form-label" for="inputBasicEmail">Select Employees</label>
                       <div class="col-md-8">
                         <select class="form-control" required name="gender" multiple data-plugin="select2" data-placeholder="Select Here">
                           <option></option>
                             <option value="AK">Male</option>
                             <option value="HI">Female</option>
                         </select>
                       </div>
                 </div>
                 <br>
                   <div class="form-group form-material row">
                     <div class="col-md-9">
                       <button type="button" class="btn btn-primary">Submit </button>
                     </div>
                   </div>
                 </form>

      </div>
    </form>
  </div>
</div>
<!-- END ADD MODAL-->

    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>


  </body>
</html>
