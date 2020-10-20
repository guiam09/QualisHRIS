<?php
include_once ('../includes/configuration.php');

include ('../db/connection.php');



// include login checker
$page_title="Admin";
$access_type ="Admin";

// include login checker
$require_login=true;
include_once "../includes/loginChecker.php";

include ('../includes/header.php');
include ('../includes/navbar.php');
$CURRENT_PAGE="Config Access Levels";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');




?>


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Setting Access Levels</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <!--<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"-->
                              <!--    aria-controls="cardTab1" role="tab" aria-expanded="true">Access Level</a></li>-->
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
                                //ADD LEAVE
                                $message = isset($_GET['add_access_level']) ? $_GET['add_access_level'] : "";

                                if($message=='success'){
                                    echo "<div class='alert alert-success'>Access Level Successfully Added!</div>";
                                }

                                else if($message=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to add Access Level.</div>";
                                }

                                //EDIT Leave

                                $message2 = isset($_GET['edit_access_level']) ? $_GET['edit_access_level'] : "";

                                if($message2=='success'){
                                    echo "<div class='alert alert-success'>Access Level Successfully Updated!</div>";
                                }

                                else if($message2=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to update Access Level.</div>";
                                }


                                //DELETE LEAVE
                                $message3 = isset($_GET['delete_access_level']) ? $_GET['delete_access_level'] : "";

                                if($message3=='success'){
                                    echo "<div class='alert alert-success'>Access Level Deleted!</div>";
                                }

                                else if($message3=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to Access Level.</div>";
                                }

                                ?>
                                <button type="button" data-target="#addAccessLevel" data-toggle="modal" class="btn btn-info waves-effect waves-classic">Add Access Level</button>
                                <br><br><br>
                                <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                  <thead>
                                    <tr>
                                      <th>Access Level</th>
                                      <th>Modules Accessed</th>
                                      <th>Employees</th>
                                      <th>Options</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                       $query = "SELECT * FROM tbl_accesslevels";
                                          $stmt = $con->prepare($query);
                                          $stmt->execute();
                                          $num = $stmt->rowCount();
                                          if($num>0){
                                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                              
                                      
                                              
                                          ?>       
                                          <tr>
                                                      <td><?php echo $row["accessLevelName"] ?></td>
                                                        <td><?php 
                                                        
                                                              $query2 = "SELECT DISTINCT accessedModules FROM tbl_accesslevels JOIN tbl_accessLevelEmp ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID 
                                                              JOIN tbl_accessLevelModules ON tbl_accessLevelModules.accessLevelID = tbl_accesslevels.accessLevelID WHERE tbl_accesslevels.accessLevelID ='".$row['accessLevelID']."' ORDER BY accessedModules ASC";
                                                              $stmt2 = $con->prepare($query2);
                                                              $stmt2->execute();
                                                              $num2 = $stmt2->rowCount();
                                                              if($num2>0){
                                                              while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                                                                  if($row2['accessedModules'] == 'aedEmployeeModule'){
                                                                      $module = "Add/Edit/Delete Employee Module";
                                                                  }elseif($row2['accessedModules'] == 'leaveManagementModule'){
                                                                       $module = "Leave Management Module";
                                                                  }elseif($row2['accessedModules'] == 'timesheetManagementModule'){
                                                                       $module = "Timesheet Management Module";
                                                                  }elseif($row2['accessedModules'] == 'projectMangementModule'){
                                                                       $module = "Project Management Module Module";
                                                                  }elseif($row2['accessedModules'] == 'reportsModule'){
                                                                       $module = "Reports Module";
                                                                  }elseif($row2['accessedModules'] == 'configurationsModule'){
                                                                      $module = "Configurations Module";
                                                                  }elseif($row2['accessedModules'] == 'attendanceConfigurationModule'){
                                                                      $module = "Attendance Configuration Module";
                                                                  }elseif($row2['accessedModules'] == 'attendanceManagementModule'){
                                                                      $module = "Attendance Management Module";
                                                                  }else{
                                                                      $module ="";
                                                                      
                                                                  }
                                                                  echo $module;
                                                                  echo "<br>";
                                                              }
                                                              
                                                              }
                                                        
                                                        
                                                        
                                                        ?></td>
                                                        
                                                        <td><?php 
                                                        
                                                              $query3 =       "SELECT DISTINCT tbl_accessLevelEmp.employeeID FROM tbl_accesslevels JOIN tbl_accessLevelEmp ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID 
                                                               JOIN tbl_employees ON tbl_accessLevelEmp.employeeID = tbl_employees.employeeID WHERE tbl_accesslevels.accessLevelID ='".$row['accessLevelID']."' ORDER BY firstName ASC";
                                                         
                                                              $stmt3 = $con->prepare($query3);
                                                              $stmt3->execute();
                                                              $num3 = $stmt3->rowCount();
                                                              if($num3>0){
                                                              while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
                                                                  
                                                                  
                                                              $query4 = "SELECT * FROM tbl_employees WHERE employeeID ='".$row3['employeeID']."' ORDER BY firstName ASC";
                                                              $stmt4 = $con->prepare($query4);
                                                              $stmt4->execute();
                                                              while ($row4 = $stmt4->fetch(PDO::FETCH_ASSOC)){    
                                                                  echo $row4['firstName'] . ' ' . $row4['lastName'];
                                                                      echo "<br>";
                                                                  
                                                              }
                                                                  
                                                                  
                                                                  
                                                                //   echo $row3['employeeID'];
                                                                 
                                                              }
                                                              
                                                              }

                                                        ?></td>
                                                     
                                                        <td class='actions'>
                                          <a href='#' class='btn btn-info'
                                             data-original-title='Edit' data-toggle='modal' data-target='#editButton<?php echo $row['accessLevelID'] ?>' data-id="<?php echo $row['accessLevelID'] ?>" ><i class='icon fa-edit' aria-hidden='true'></i>Edit</a>
                                             <?php if($row['accessLevelID'] == 28 OR $row['accessLevelID'] == 24){
                                                 
                                             }else{
                                                 
                                                 ?>
                                                  <a href='#' class='btn btn-danger'
                                             data-original-title='Remove' data-toggle='modal' data-target='#deleteButton<?php echo $row['accessLevelID'] ?>' data-id="<?php echo $row['accessLevelID'] ?>"><i class='icon fa-trash' aria-hidden='true'></i>Delete</a>
                                                 <?php
                                             }
                                             ?>
                                         
                                        </td>
                                                      </tr>

                                                      
                                                      <div class="modal fade" id="editButton<?php echo $row['accessLevelID'];?>">
                                                          <div class="modal-dialog modal-lg">
                                                              <form method="post" action="process_access_level.php" autocomplete="off">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Edit Access Level</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Access Level Name: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control"  maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="accessLevelName"
                                                                        value="<?php echo $row['accessLevelName'] ?>" required placeholder="<?php echo $row['accessLevelName'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                       <div class="form-group row">
                                                                        <label class="col-md-3 col-form-label">Employees: </label>
                                                                        <div class="col-md-9">
                                                                          <select class="form-control" data-plugin="select2"  multiple name="employees[]" data-placeholder="Select Here">
                                                                            <option></option>
                                                                             <?php 
                                                                              $kwery = "SELECT * FROM tbl_employees ORDER BY firstName ASC";
                                                                              $statement = $con->prepare($kwery);
                                                                              $statement->execute();
                                                                              while ($row1 = $statement->fetch(PDO::FETCH_ASSOC)){ 
                                                                              $select = "";
                                                                              $kwery2 = "SELECT DISTINCT employeeID FROM tbl_accesslevels JOIN tbl_accessLevelEmp 
                                                                              ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID JOIN tbl_accessLevelModules ON tbl_accessLevelModules.accessLevelID = tbl_accesslevels.accessLevelID 
                                                                              WHERE tbl_accesslevels.accessLevelID ='".$row['accessLevelID']."' ";
                                                                              $statement2 = $con->prepare($kwery2);
                                                                              $statement2->execute();
                                                                              while ($row2 = $statement2->fetch(PDO::FETCH_ASSOC)){
                                                                                  if($row2['employeeID'] == $row1['employeeID']){
                                                                                      $select = "selected";
                                                                                      break;
                                                                                  }
                                                                                
                                                                              }
                                                                                   
                                                                              
                                                                              ?>
                                                                               <option value="<?php echo $row1['employeeID'] ?>"   
                                                                               <?php echo $select; ?> >
                                                                               <?php echo $row1['firstName'] . ' ' . $row1['lastName'] ?></option>

                                                                              
                                                                              <?php
                                                                              
                                                                              }
                                                                          ?>
                                                                          </select>
                                                                        </div>
                                                                      </div>
                                                                   
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                      <input type="hidden" name="accessLevelID" value="<?php echo $row['accessLevelID'] ?>">
                                                                      <input type="hidden" name="formerName" value="<?php echo $row['accessLevelName'] ?>">
                                                                    <button type="submit" class="btn btn-primary"  name="editAccessLevel"><i class="fa fa-check-square-o"></i> Update Access Level</button>
                                                                  </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="modal fade" id="deleteButton<?php echo $row['accessLevelID'];?>">
                                                          <div class="modal-dialog">
                                                              <form method="post" action="process_access_level.php">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Delete Access Level</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <p>Do you want to delete <b class = "text-primary"><?php echo $row['accessLevelName'] ?></b>?</p>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <input type="hidden" name="accessLevelID" value="<?php echo $row['accessLevelID'] ?>">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                     <input type="hidden" name="formerName" value="<?php echo $row['accessLevelName'] ?>">
                                                                    <button type="submit" class="btn btn-primary"  name="deleteAccessLevel"> <i class="fa fa-check-square-o" ></i> Delete Access Level</button>
                                                      </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <?php
                                          
                                          
                                                    }
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

<div class="modal fade" id="addAccessLevel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Add Access Level</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='process_access_level.php'  autocomplete="off">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Access Level Name: </label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" required maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="accessLevelName"
                              />
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Employees: </label>
                            <div class="col-md-9">
                              <select class="form-control" data-plugin="select2"   multiple name="employees[]" data-placeholder="Select Here">
                                <option></option>
                                 <?php 
                                  $query = "SELECT * FROM tbl_employees ORDER BY firstName ASC";
                                  $stmt = $con->prepare($query);
                                  $stmt->execute();
                                  $num = $stmt->rowCount();
                                  // check if more than 0 record found
                                  if($num>0){
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                      echo "  <option value='".$row['employeeID']."' >" . $row['firstName'] . " " . $row['lastName'] . "</option>";
                                    }
                                  }
                              ?>
                              </select>
                            </div>
                          </div>
                           <div class="form-group row">
                            <label class="col-md-3 col-form-label">Modules Accessed: </label>
                            <div class="col-md-9">
                              <select class="form-control" data-plugin="select2"   multiple name="modulesAccesed[]" data-placeholder="Select Here">
                                <option></option>
                                <option value="aedEmployeeModule">ADD/UPDATE Employee Module</option>
                                <option value="leaveManagementModule">Leave Management Module</option>
                                <option value="timesheetManagementModule">Timesheet Management Module</option>
                                <option value="projectMangementModule">Project Management Module</option>
                                <option value="reportsModule">Reports Module</option>
                                <option value="configurationsModule">Configurations Module</option>
                                <option value="attendanceManagementModule">Attendance Management Module</option>
                                <option value="attendanceConfigurationModule">Attendance Configuration Module</option>
                              </select>
                            </div>
                          </div>
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary"  name="addAccessLevel"> <i class="fa fa-check-square-o" ></i> Add Access Level</button>
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
