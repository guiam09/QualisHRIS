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
$CURRENT_PAGE="Project Management";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
?>


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Project Management</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <!--<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"-->
                              <!--    aria-controls="cardTab1" role="tab" aria-expanded="true">Project Management</a></li>-->
                              <!--<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"-->
                              <!--    role="tab">Leave Entitlement</a></li>-->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                  <?php
            $message = isset($_GET['add_project']) ? $_GET['add_project'] : "";

            if($message=='success'){
                echo "<div class='alert alert-success'>Project Successfully Added!</div>";
            }

            else if($message=='failed'){
              echo "<div class='alert alert-danger'>Unable to add a new project.</div>";
            }


            $message3 = isset($_GET['edit_project']) ? $_GET['edit_project'] : "";

            if($message3=='success'){
                echo "<div class='alert alert-success'>Project Successfully Updated!</div>";
            }

            else if($message3=='failed'){
              echo "<div class='alert alert-danger'>Unable to update project.</div>";
            }

            $message4 = isset($_GET['delete_project']) ? $_GET['delete_project'] : "";

            if($message4=='success'){
                echo "<div class='alert alert-success'>Project Successfully Deleted!</div>";
            }

            else if($message4=='failed'){
              echo "<div class='alert alert-danger'>Unable to delete project.</div>";
            }






            $position = isset($_GET['add_position']) ? $_GET['add_position'] : "";

            if($position=='success'){
                echo "<div class='alert alert-success'>Position Successfully Added!</div>";
            }

            else if($position=='failed'){
              echo "<div class='alert alert-danger'>Unable to add a new Position.</div>";
            }

            $position3 = isset($_GET['edit_position']) ? $_GET['edit_position'] : "";

            if($position3=='success'){
                echo "<div class='alert alert-success'>Position Successfully Updated!</div>";
            }

            else if($position3=='failed'){
              echo "<div class='alert alert-danger'>Unable to update Position.</div>";
            }

            $position4 = isset($_GET['delete_position']) ? $_GET['delete_position'] : "";

            if($position4=='success'){
                echo "<div class='alert alert-success'>Position Successfully Deleted!</div>";
            }

            else if($position4=='failed'){
              echo "<div class='alert alert-danger'>Unable to delete Position.</div>";
            }
            ?>

                                <button type="button" data-target="#addProject" data-toggle="modal" class="btn btn-default waves-effect waves-classic">Add a Project</button>
                                <br><br><br>
                                <table  class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                  <thead>
                                    <tr>
                                      <th>Project Name</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                     <?php
                                            // select all data
                                            $query = "SELECT * FROM tbl_project";
                                            $stmt = $con->prepare($query);
                                            $stmt->execute();
                                            $num = $stmt->rowCount();
                                            $output ="";
                                            if($num>0){

                                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo "        <tr>

                                                                <td>" . $row['project_name'] . "</td>
                                                               <td class='actions'>
                                          <a href='#' class='btn btn-default'
                                             data-original-title='Edit' data-toggle='modal' data-target='#editButton".$row['project_ID']."' data-id=" .  $row['project_ID'] . " ><i class='icon fa-edit' aria-hidden='true'></i></a>
                                          <a href='#' class='btn btn-default'
                                             data-original-title='Remove' data-toggle='modal' data-target='#deleteButton".$row['project_ID']."' data-id=" .  $row['project_ID'] . "><i class='icon fa fa-close' aria-hidden='true'></i></a>
                                        </td>
                                                              </tr>";
                                                              ?>
<!-- <button type='button'  data-toggle='modal' data-target='#editButton".$row['leaveDetailsID']."' class='btn btn-success btn-sm edit btn-flat' data-id=" .  $row['employeeCode'] . "><i class='fa fa-check-square-o'></i> Update</button> -->
                                                               <div class="modal fade" id="editButton<?php echo $row['project_ID'];?>">
                                                          <div class="modal-dialog modal-lg">
                                                              <form method="post" action="process_projectManagement.php" autocomplete="off">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Edit Project</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Project Name: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control"  maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="projectName"
                                                                        value="<?php echo $row['project_name'] ?>" required placeholder="<?php echo $row['project_name'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                 
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                      <input type="hidden" name="projectID" value="<?php echo $row['project_ID'] ?>">
                                                                      <input type="hidden" name="formerName" value="<?php echo $row['project_name'] ?>">
                                                                    <button type="submit" class="btn btn-primary"  name="editProject"><i class="fa fa-check-square-o"></i> Update Project</button>
                                                                  </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="modal fade" id="deleteButton<?php echo $row['project_ID'];?>">
                                                          <div class="modal-dialog">
                                                              <form method="post" action="process_projectManagement.php">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Delete Project</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <p>Do you want to delete <b class = "text-primary"><?php echo $row['project_name'] ?></b> Project?</p>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <input type="hidden" name="projectID" value="<?php echo $row['project_ID'] ?>">
                                                                      <input type="hidden" name="formerName" value="<?php echo $row['project_name'] ?>">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                    <button type="submit" class="btn btn-primary"  name="deleteProject"> <i class="fa fa-check-square-o" ></i> Delete Project</button>
                                                      </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                              <?php
                                                    }
                                                    echo "</table>";
                                                } else {
                                                    echo "<div class='alert alert-error'>No Results Found!</div>";
                                                    echo "</table>";
                                                }
                                          ?>

                                  </tbody>
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

<div class="modal fade" id="addProject">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Add Project</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='process_projectManagement.php'   autocomplete="off">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Project Name: </label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" required maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="projectName"
                              />
                            </div>
                          </div>
                         
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-default"  name="addProject"> <i class="fa fa-check-square-o" ></i> Add Project</button>
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
