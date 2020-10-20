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
$CURRENT_PAGE="Config Leaves";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
?>


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Leaves Configuration</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <!--<div class="panel-heading p-30 pb-0 pt-10">-->
                          <!--  <ul class="nav nav-pills" role="tablist">-->
                          <!--    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"-->
                          <!--        aria-controls="cardTab1" role="tab" aria-expanded="true">Leave Types</a></li>-->
                              <!--<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"-->
                              <!--    role="tab">Assign Leave Hours</a></li>-->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                          <!--        role="tab">tab2</a></li> -->
                          <!--  </ul>-->
                          <!--</div>-->
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <?php
                                //ADD LEAVE
                                $message = isset($_GET['add_leave']) ? $_GET['add_leave'] : "";

                                if($message=='success'){
                                    echo "<div class='alert alert-success'>Leave Successfully Added!</div>";
                                }

                                else if($message=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to add leave.</div>";
                                }

                                //EDIT Leave

                                $message2 = isset($_GET['edit_leaveType']) ? $_GET['edit_leaveType'] : "";

                                if($message2=='success'){
                                    echo "<div class='alert alert-success'>Leave Type Successfully Updated!</div>";
                                }

                                else if($message2=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to update leave type.</div>";
                                }


                                //DELETE LEAVE
                                $message3 = isset($_GET['delete_leaveType']) ? $_GET['delete_leaveType'] : "";

                                if($message3=='success'){
                                    echo "<div class='alert alert-success'>Leave Type Deleted!</div>";
                                }

                                else if($message3=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to Delete Leave Type.</div>";
                                }

                                ?>
                                <button type="button" data-target="#addLeave" data-toggle="modal" class="btn btn-default waves-effect waves-classic">Add Leave Type</button>
                                <br><br><br>
                                <table  class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                  <thead>
                                    <tr>
                                      <th>Leave Name</th>
                                      <th>Leave Count</th>
                                      <th>Required</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $stmt = getLeaveTypes($con);
                                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        if ($row['required'] == 1){
                                          $output = "Yes";
                                        }else{
                                          $output = "No";
                                        }
                                        echo "        <tr>

                                                        <td>" . $row["leaveName"] . "</td>
                                                        <td>" . $row["leaveCount"] . "</td>
                                                        <td>" . $output. "</td>
                                                        <td class='actions'>
                                          <a href='#' class='btn btn-default'
                                             data-original-title='Edit' data-toggle='modal' data-target='#editButton".$row['leaveID']."' data-id=" .  $row['leaveID'] . " ><i class='icon fa-edit' aria-hidden='true'></i></a>
                                          <a href='#' class='btn btn-default'
                                             data-original-title='Remove' data-toggle='modal' data-target='#deleteButton".$row['leaveID']."' data-id=" .  $row['leaveID'] . "><i class='icon fa fa-close' aria-hidden='true'></i></a>
                                        </td>
                                                      </tr>";

                                                      ?>
                                                      <div class="modal fade" id="editButton<?php echo $row['leaveID'];?>">
                                                          <div class="modal-dialog modal-lg">
                                                              <form method="post" action="process_leave_configuration.php" autocomplete="off">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Edit Leave Type</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Name: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control"  maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="leaveName"
                                                                        value="<?php echo $row['leaveName'] ?>" required placeholder="<?php echo $row['leaveName'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                      <legend class="col-md-3 col-form-legend">Required: </legend>
                                                                      <div class="col-md-9">
                                                                        <div class="radio-custom radio-default radio-inline">
                                                                          <input type="radio" id="inputHorizontalMale" name="required" value="1" <?php echo ($row['required'] =='1')? 'checked':'' ?>/>
                                                                          <label for="inputHorizontalMale">Yes</label>
                                                                        </div>
                                                                        <div class="radio-custom radio-default radio-inline">
                                                                          <input type="radio" id="inputHorizontalFemale" name="required" value="0" <?php echo ($row['required'] =='0')? 'checked':'' ?>
                                                                          />
                                                                          <label for="inputHorizontalFemale">No</label>
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Count: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control" required  maxlength="10" onkeypress="return restrictCharacters(this, event, digitsOnly);" autocomplete="off"name="leaveCount"
                                                                          value="<?php echo $row['leaveCount'] ?>" required placeholder="<?php echo $row['leaveCount'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                      <input type="hidden" name="leaveID" value="<?php echo $row['leaveID'] ?>">
                                                                      <input type="hidden" name="formerName" value="<?php echo $row['leaveName'] ?>">
                                                                    <button type="submit" class="btn btn-primary"  name="editLeave"><i class="fa fa-check-square-o"></i> Update Leave Type</button>
                                                                  </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="modal fade" id="deleteButton<?php echo $row['leaveID'];?>">
                                                          <div class="modal-dialog">
                                                              <form method="post" action="process_leave_configuration.php">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Delete Leave Type</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <p>Do you want to delete <b class = "text-primary"><?php echo $row['leaveName'] ?></b> Leave?</p>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <input type="hidden" name="leaveID" value="<?php echo $row['leaveID'] ?>">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                      <input type="hidden" name="formerName" value="<?php echo $row['leaveName'] ?>">
                                                                    <button type="submit" class="btn btn-primary"  name="deleteLeave"> <i class="fa fa-check-square-o" ></i> Delete Leave Type</button>
                                                      </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <?php
                                                    }
                                     ?>

                                  </tbody>
                                </table>
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

<div class="modal fade" id="addLeave">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Add Leave Type</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='process_leave_configuration.php' name="submitLeave"  autocomplete="off">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Leave Name: </label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" required maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="leaveName"
                              />
                            </div>
                          </div>
                          <div class="form-group row">
                            <legend class="col-md-3 col-form-legend">Required: </legend>
                            <div class="col-md-9">
                              <div class="radio-custom radio-default radio-inline">
                                <input type="radio" id="inputHorizontalMale" name="required" value="1" />
                                <label for="inputHorizontalMale">Yes</label>
                              </div>
                              <div class="radio-custom radio-default radio-inline">
                                <input type="radio" id="inputHorizontalFemale" name="required" value="0"
                                />
                                <label for="inputHorizontalFemale">No</label>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Leave Count: </label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" required  maxlength="10" onkeypress="return restrictCharacters(this, event, digitsOnly);" autocomplete="off"name="leaveCount"
                              />
                            </div>
                          </div>
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary"  name="addLeave"> <i class="fa fa-check-square-o" ></i> Add Leave Type</button>
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
  </body>
</html>
