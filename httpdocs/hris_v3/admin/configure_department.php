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
          <h1 class="page-title"><i>Designations Configuration</i></h1>

      </div>

      <div class="page-content container-fluid">
        <?php
        $message = isset($_GET['add_department']) ? $_GET['add_department'] : "";

        if($message=='success'){
            echo "<div class='alert alert-success'>Department Successfully Added!</div>";
        }

        else if($message=='failed'){
          echo "<div class='alert alert-danger'>Unable to add a new department.</div>";
        }


        $message3 = isset($_GET['edit_department']) ? $_GET['edit_department'] : "";

        if($message3=='success'){
            echo "<div class='alert alert-success'>Department Successfully Updated!</div>";
        }

        else if($message3=='failed'){
          echo "<div class='alert alert-danger'>Unable to update department.</div>";
        }

        $message4 = isset($_GET['delete_department']) ? $_GET['delete_department'] : "";

        if($message4=='success'){
            echo "<div class='alert alert-success'>Department Successfully Deleted!</div>";
        }

        else if($message4=='failed'){
          echo "<div class='alert alert-danger'>Unable to delete department.</div>";
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
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">Departments</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Positions</a></li>
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <button type="button" data-target="#addDepartment" data-toggle="modal" class="btn btn-primary waves-effect waves-classic">Add Department</button>

                                <div class="panel-body container-fluid">
                         <br>
                         <table  class="table dataTable table-striped w-full" data-plugin="dataTable">
                             <thead>
                                 <tr>
                                     <th>Department Name</th>
                                     <th>Action</th>
                                 </tr>
                             </thead>
                             <?php
                             // select all data
                             $query = "SELECT * FROM tbl_department";
                             $stmt = $con->prepare($query);
                             $stmt->execute();
                             $num = $stmt->rowCount();
                             $output ="";
                             if($num>0){

                               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                 echo "        <tr>

                                                 <td>" . $row['departmentName'] . "</td>
                                                 <td class='actions'>
                                   <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
                                      data-original-title='Edit' data-toggle='modal' data-target='#editButton".$row['departmentID']."' data-id=" .  $row['departmentID'] . " ><i class='icon md-edit' aria-hidden='true'></i></a>
                                   <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
                                      data-original-title='Remove' data-toggle='modal' data-target='#deleteButton".$row['departmentID']."' data-id=" .  $row['departmentID'] . "><i class='icon md-delete' aria-hidden='true'></i></a>
                                 </td>
                                               </tr>";
                                               ?>
                       <!-- <button type='button'  data-toggle='modal' data-target='#editButton".$row['leaveDetailsID']."' class='btn btn-success btn-sm edit btn-flat' data-id=" .  $row['employeeCode'] . "><i class='fa fa-check-square-o'></i> Update</button> -->
                                               <div class="modal fade" id="editButton<?php echo $row['departmentID'];?>">
                                                   <div class="modal-dialog modal-lg">
                                                       <form method="post" action="process_designations.php">
                                                       <div class="modal-content">
                                                           <div class="modal-header">
                                                             <h3 class="card-title">Edit Department</h3>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                 <span aria-hidden="true">&times;</span></button>
                                                             <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                           </div>
                                                           <div class="modal-body">
                                                             <div class="form-group row">
                                                               <label class="col-md-3 col-form-label">Department Name: </label>
                                                               <div class="col-md-9">
                                                                 <input type="text" maxlength="25"class="form-control" autocomplete="off" name="departmentName" value="<?php echo $row['departmentName'] ?>" required placeholder="<?php echo $row['departmentName'] ?>">
                                                               </div>
                                                             </div>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button  type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                               <input type="hidden" name="departmentID" value="<?php echo $row['departmentID'] ?>">
                                                             <button  type="submit" class="btn btn-primary"name="editDepartment"><i class="fa fa-check-square-o"></i> Update Department Name</button>
                                                           </form>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                               <div class="modal fade" id="deleteButton<?php echo $row['departmentID'];?>">
                                                   <div class="modal-dialog">
                                                       <form method="post" action="process_designations.php">
                                                       <div class="modal-content">
                                                           <div class="modal-header">
                                                             <h3 class="card-title">Delete Department</h3>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                 <span aria-hidden="true">&times;</span></button>
                                                             <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                           </div>
                                                           <div class="modal-body">
                                                             <p>Do you want to delete <b class = "text-primary"><?php echo $row['departmentName'] ?></b> Department?</p>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button  type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                 <input type="hidden" name="departmentID" value="<?php echo $row['departmentID'] ?>">
                                                             <button type="submit" class="btn btn-primary" name="deleteDepartment"><i class="fa fa-check-square-o"></i> Delete Department</button>
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
                       </div>
                              </div>
                              <div class="tab-pane" id="cardTab2" role="tabpanel">

                                  <button type="button" data-target="#addPosition" data-toggle="modal" class="btn btn-primary waves-effect waves-classic">Add Position</button>
                                  <div class="panel-body container-fluid">
                           <br>
                           <table  class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                             <thead>
                                 <tr>
                                     <th>Position Name</th>
                                     <th>Under Department</th>
                                     <th>Action</th>
                                 </tr>
                             </thead>
                             <?php
                             // select all data
                             $query = "SELECT * FROM tbl_position INNER JOIN tbl_department ON tbl_position.departmentID = tbl_department.departmentID";
                             $stmt = $con->prepare($query);
                             $stmt->execute();
                             $num = $stmt->rowCount();
                             $output ="";
                             if($num>0){

                               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                 echo "        <tr>

                                                 <td>" . $row['positionName'] . "</td>
                                                 <td>" . $row['departmentName'] . "</td>
                                                 <td class='actions'>
                                   <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
                                      data-original-title='Edit' data-toggle='modal' data-target='#positionEdit".$row['positionID']."' data-id=" .  $row['positionID'] . " ><i class='icon md-edit' aria-hidden='true'></i></a>
                                   <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
                                      data-original-title='Remove' data-toggle='modal' data-target='#positionDelete".$row['positionID']."' data-id=" .  $row['positionID'] . "><i class='icon md-delete' aria-hidden='true'></i></a>
                                 </td>
                                               </tr>";
                                               ?>
                                               <div class="modal fade" id="positionEdit<?php echo $row['positionID'];?>">
                                                   <div class="modal-dialog modal-lg">
                                                       <form method="post" action="process_designations.php">
                                                       <div class="modal-content">
                                                           <div class="modal-header">
                                                             <h3 class="card-title">Edit Position</h3>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                 <span aria-hidden="true">&times;</span></button>
                                                             <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                           </div>
                                                           <div class="modal-body">
                                                             <div class="form-group row">
                                                               <label class="col-md-3 col-form-label">Position Name: </label>
                                                               <div class="col-md-9">
                                                                 <input type="text" maxlength="25"class="form-control" autocomplete="off" name="positionName" value="<?php echo $row['positionName'] ?>" required placeholder="<?php echo $row['positionName'] ?>">
                                                               </div>
                                                             </div>
                                                             <div class="form-group row">
                                                               <label class="col-md-3 col-form-label">Under Department: </label>
                                                               <div class="col-md-9">
                                                                <select class="form-control" name="department" data-plugin="select2" data-placeholder="Select Here">
                                                                     <option selected value="<?php echo $row['departmentID'] ?>"><?php echo $row['departmentName'] ?></option>
                                                                     <?php
                                                                     // select all data
                                                                     $query2 = "SELECT * FROM tbl_department";
                                                                     $stmt2 = $con->prepare($query2);
                                                                     $stmt2->execute();
                                                                     $num2 = $stmt2->rowCount();
                                                                     // check if more than 0 record found
                                                                     if($num2>0){
                                                                       while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                                                                     echo  '<option value="'.$row2['departmentID'].'">'.$row2['departmentName'].'</option>';
                                                                      }
                                                                      // if no records found
                                                                      }else{
                                                                        echo "no records found";
                                                                      }
                                                                     ?>

                                                                 </select>
                                                               </div>
                                                             </div>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                               <input type="hidden" name="positionID" value="<?php echo $row['positionID'] ?>">
                                                             <button type="submit" class="btn btn-primary" name="editPosition"><i class="fa fa-check-square-o"></i> Update Position</button>
                                                           </form>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                               <div class="modal fade" id="positionDelete<?php echo $row['positionID'];?>">
                                                   <div class="modal-dialog">
                                                       <form method="post" action="process_designations.php">
                                                       <div class="modal-content">
                                                           <div class="modal-header">
                                                             <h3 class="card-title">Delete Position</h3>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                 <span aria-hidden="true">&times;</span></button>
                                                             <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                           </div>
                                                           <div class="modal-body">
                                                             <p>Do you want to delete <b class = "text-primary"><?php echo $row['positionName'] ?></b> Position?</p>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button  type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                 <input type="hidden" name="positionID" value="<?php echo $row['positionID'] ?>">
                                                             <button type="submit" class="btn btn-primary" name="deletePosition"><i class="fa fa-check-square-o"></i> Delete Position</button>
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
                         </div>
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
<div class="modal fade" id="addDepartment">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Add Department</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='process_designations.php'   autocomplete="off">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Department Name: </label>
                            <div class="col-md-9">
                              <input type="text" class="form-control"  required maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off"name="departmentName">

                            </div>
                          </div>
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary" name="addDepartment"> <i class="fa fa-check-square-o" ></i> Add Department</button>
              </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addPosition">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Add Position</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='process_designations.php'  autocomplete="off">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Position Name: </label>
                            <div class="col-md-9">
                            <input type="text" class="form-control"  required maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off"name="positionName">

                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Under Department: </label>
                            <div class="col-md-9">
                              <select class="form-control" name="departmentName" required data-plugin="select2" data-placeholder="Select Here">
                                   <option disabled selected value=""></option>
                                 <?php
                                   // select all data
                                   $query = "SELECT * FROM tbl_department";
                                   $stmt = $con->prepare($query);
                                   $stmt->execute();
                                   $num = $stmt->rowCount();
                                   // check if more than 0 record found
                                   if($num>0){
                                     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                                   echo  '<option value="'.$row['departmentID'].'">'.$row['departmentName'].'</option>';


                                    }
                                    // if no records found
                                    }else{
                                      echo "no records found";
                                    }
                                   ?>

                               </select>

                            </div>
                          </div>
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary" name="addPosition"> <i class="fa fa-check-square-o" ></i> Add Position</button>
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
