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
$CURRENT_PAGE="Assign Leave";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
?>

<style>
    thead input {
        width: 80%;
    }
</style>
    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Leave Credit/Balance</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                     <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <!--<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab3"-->
                              <!--    aria-controls="cardTab1" role="tab" aria-expanded="true">Per Employee</a></li>-->
                              <!--<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab4" aria-controls="cardTab2"-->
                              <!--    role="tab">Per Position</a></li>-->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab3" role="tabpanel">
                                    <?php
                                //ADD LEAVE
                                $message = isset($_GET['assign_leave']) ? $_GET['assign_leave'] : "";

                                if($message=='reset'){
                                    echo "<div class='alert alert-success'>Leave Successfully Reset!</div>";
                                }

                                else if($message=='updated'){
                                     echo "<div class='alert alert-success'>Leave Successfully Updated!</div>";
                                }   else if($message=='deleted'){
                                  echo "<div class='alert alert-success'>Leave Successfully Deleted!</div>";
                                }else if($message=='added'){
                                  echo "<div class='alert alert-success'>Leave Successfully Assigned!</div>";
                                }else if($message=='resetAll'){
                                  echo "<div class='alert alert-success'>All Assigned Leave Successfully Reset!</div>";
                                }

                            

                                ?>
                                <button type="button" data-target="#assignLeave" data-toggle="modal" class="btn btn-default waves-effect waves-classic"><i class='icon fa-pencil' aria-hidden='true'></i>Assign Leave</button>
                                
                                <button type="button" data-target="#resetAllLeave" data-toggle="modal" class="btn btn-default waves-effect waves-classic"><i class='icon fa-refresh' aria-hidden='true'></i>Reset all Employee's Leaves</button>
                                <br><br><br>
                                <div class="row">
                                 <div class="col-md-5">
                                    <label class="col-form-label col-md-4">Search Employee</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <select type="text" class="form-control" name="employeeSearch" id="employeeSearch" placeholder="Search...">
                                                <option></option>
                                                <?php
                                                $query = "SELECT * FROM tbl_employees ORDER BY firstName ASC";
                                                $stmt = $con->prepare($query);
                                                $stmt->execute();
                                                while ($row = $stmt->FETCH(PDO::FETCH_ASSOC)){
                                                echo ' <option value='.$row['employeeID'].'>'.$row['firstName'].' '.$row['lastName'].'</option>';                                            
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary"><i class="icon wb-search" aria-hidden="true"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> 
                                 <div class="col-md-5">
                                    <label class="col-form-label col-md-4">Leave Type</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <select type="text" class="form-control" name="leaveSearch" id="leaveSearch" placeholder="Search...">
                                                <option></option>
                                                <?php
                                                $query = "SELECT * FROM tbl_leave ORDER BY leaveName ASC";
                                                $stmt = $con->prepare($query);
                                                $stmt->execute();
                                                while ($row = $stmt->FETCH(PDO::FETCH_ASSOC)){
                                                echo ' <option value='.$row['leaveID'].'>'.$row['leaveName'].'</option>';                                            
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary"><i class="icon wb-search" aria-hidden="true"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> 
                                    <div class="col-md-2">
                                    <label class="col-form-label col-md-12">    </label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <button type="button" width="5" class="btn btn-default" id="print"><i class="fas fa-download"></i></button>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                        
                                <br>
                                
                             
                                <table  class="table table-hover table-striped w-full" id="datatable">
                                   <thead>
                                    <tr>
                                      <th>Employee Code</th>
                                      <th>Employee Name</th>
                                      <th>Position</th>
                                      <th>Leave Type</th>
                                      <th>Leave Credits</th>
                                      <th>Used</th>
                                      <th>Leave Balance</th>
                                      <th></th>
                                     
                                    </tr>
                                  </thead>
                                  <tbody id="tableReports">
                                    <?php
                                     $query = "SELECT * FROM tbl_leaveinfo JOIN tbl_employees ON tbl_leaveinfo.employeeID = tbl_employees.employeeID 
                                     JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID
                                     JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID
                                     ORDER BY firstName ASC";
                                      $stmt = $con->prepare($query);
                                      $stmt->execute();
                                      $num = $stmt->rowCount();
                                      // check if more than 0 record found
                                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        
                                        echo "        <tr>

                                                        <td>" . $row["employeeCode"] . "</td>
                                                        <td>" . $row["firstName"] . ' ' . $row['lastName'] . "</td>
                                                        <td>" . $row['positionName']. "</td>
                                                        <td>" . $row['leaveName']. "</td>
                                                        <td>" . $row['allowedLeave']. "</td>
                                                        <td>" . $row['leaveUsed']. "</td>
                                                        <td>" . $row['leaveRemaining']. "</td>
                                                        <td class='actions'>   <a href='#' class='btn btn-default'
                                             data-original-title='Edit' data-toggle='modal' data-target='#editButton".$row['leaveInfoID']."' data-id=" .  $row['leaveInfoID'] . " ><i class='icon fa-edit' aria-hidden='true'></i></a>
                                             <a href='#' class='btn btn-default'
                                             data-original-title='Edit' data-toggle='modal' data-target='#deleteButton".$row['leaveInfoID']."' data-id=" .  $row['leaveInfoID'] . " ><i class='icon fa fa-close' aria-hidden='true'></i></a></td>
                                                    
                                                      </tr>";

                                                      ?>
                                                      <div class="modal fade" id="editButton<?php echo $row['leaveInfoID'];?>">
                                                          <div class="modal-dialog modal-lg">
                                                              <form method="post" action="process_leave_configuration.php" autocomplete="off">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title"><?php echo $row['leaveName'];?></h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Employee Name: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control-plaintext" style="outline:none"  maxlength="50" readonly
                                                                        value="<?php echo $row['firstName'] . ' ' . $row['lastName']; ?>"  placeholder="<?php echo $row['firstName'] . ' ' . $row['lastName']; ?>"/>
                                                                      </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Name: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control-plaintext" style="outline:none"  maxlength="50" readonly
                                                                        value="<?php echo $row['leaveName'] ?>"  placeholder="<?php echo $row['leaveName'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Credits: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="number" class="form-control"  maxlength="50" name="leaveCredits"
                                                                        value="<?php echo $row['allowedLeave'] ?>"/>
                                                                      </div>
                                                                    </div> <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Used: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="number" class="form-control"  maxlength="50" name="leaveUsed"
                                                                        value="<?php echo $row['leaveUsed'] ?>"/>
                                                                      </div>
                                                                    </div> <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Balance: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="number" class="form-control"  maxlength="50" name="leaveBalance"
                                                                        value="<?php echo $row['leaveRemaining'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                      <input type="hidden" name="id" value="<?php echo $row['leaveInfoID'] ?>">
                                                                      <input type="hidden" name="emp" value="<?php echo $row['employeeID'] ?>">
                                                                      <input type="hidden" name="formerName" value="<?php echo $row['leaveName'] ?>">
                                                                    <button type="submit" class="btn btn-warning"  name="resetAssignedLeave"><i class="fa fa-refresh"></i> Reset Leaves</button>  
                                                                    <button type="submit" class="btn btn-primary"  name="updateAssignedLeave"><i class="fa fa-check-square-o"></i> Update Assigned Leaves</button>
                                                                  </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="modal fade" id="deleteButton<?php echo $row['leaveInfoID'];?>">
                                                          <div class="modal-dialog">
                                                              <form method="post" action="process_leave_configuration.php">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Delete Leave</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <p>Do you want to remove <b class = "text-primary"><?php echo $row['leaveName'] ?></b> From <b class = "text-primary"><?php echo $row['firstName'] . ' ' . $row['lastName'] ?></b>'s list of leaves?</p>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <input type="hidden" name="leaveID" value="<?php echo $row['leaveID'] ?>">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                      <input type="hidden" name="formerName" value="<?php echo $row['leaveName'] ?>">
                                                                      <input type="hidden" name="id" value="<?php echo $row['leaveInfoID'] ?>">
                                                                    <button type="submit" class="btn btn-primary"  name="deleteAssignedLeave"> <i class="fa fa-check-square-o" ></i> Delete Assigned Leave</button>
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
                              <div class="tab-pane" id="cardTab4" role="tabpanel">
                                <table  class="table table-hover dataTable table-striped w-full"  data-plugin="dataTable">
                                  <thead>
                                    <tr>
                                      <th>Position Name</th>
                                      <th>Under Department</th>
                                      <th>Leave Types</th>
                                     
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                     $query = "SELECT * FROM tbl_position INNER JOIN tbl_department ON tbl_position.departmentID = tbl_department.departmentID
                                      ORDER BY positionName ASC";
                                      $stmt = $con->prepare($query);
                                      $stmt->execute();
                                      $num = $stmt->rowCount();
                                      // check if more than 0 record found
                                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        
                                        echo "        <tr>

                                                        <td>" . $row["positionName"] . "</td>
                                                        <td>" . $row['departmentName']. "</td>
                                                        <td class='actions'>  <a href='assign_leave.php?id=".$row['positionID']."' class='btn btn-info'
                                             data-original-title='Edit'><i class='icon fa-eye' aria-hidden='true'></i> View</a></td>
                                                    
                                                      </tr>";

                                                      ?>
                                                      <?php
                                                    }
                                     ?>

                                  </tbody>
                                </table>

                              </div>
                              <div class="tab-pane" id="cardTab3" role="tabpanel">
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

<div class="modal fade" id="assignLeave">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Assign Leave</h4>
          </div>
          <form method="post" action="process_leave_configuration.php">
                <div class="modal-body">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Employee Name: </label>
                  <div class="col-md-9">
                         <select class="form-control" name="employee" id="employee"required data-plugin="select2" data-placeholder="Select Here">
                                   <option></option>
                                 <?php
                                   // select all data
                                   $query = "SELECT * FROM tbl_employees ORDER BY firstName ASC";
                                   $stmt = $con->prepare($query);
                                   $stmt->execute();
                                   $num = $stmt->rowCount();
                                   // check if more than 0 record found
                                   if($num>0){
                                     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                                   echo  '<option value="'.$row['employeeID'].'">'.$row['firstName']. ' ' . $row['lastName'] .'</option>';


                                    }
                                    // if no records found
                                    }else{
                                      echo "no records found";
                                    }
                                   ?>

                               </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Leave Name: </label>
                  <div class="col-md-9">
                      <select class="form-control" name="leaveName" id="leaveName" required data-plugin="select2" data-placeholder="Select Here">
                                   <option></option>
                           

                               </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Leave Credits: </label>
                  <div class="col-md-9" id="leaveCredits">
                     
                    <input type="number" class="form-control"  require maxlength="50"  
                     >
                  </div>
                </div> <div class="form-group row" style="display :none">
                  <label class="col-md-3 col-form-label">Leave Used: </label>
                  <div class="col-md-9">
                    <input type="number" class="form-control"  required maxlength="50" name="leaveUsed" value="0"/>
                  </div>
                </div> <div class="form-group row" style="display :none">
                  <label class="col-md-3 col-form-label">Leave Balance: </label>
                  <div class="col-md-9" id="leaveBalance">
                    <input type="number" class="form-control"  required maxlength="50"
                    >
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-primary"  name="assignLeave"><i class="fa fa-check-square-o"></i> Assign Leave</button>
              </form>
              </div>
        </div>
    </div>
</div>
<!-- END ADD MODAL-->
<div class="modal fade" id="resetAllLeave">
<div class="modal-dialog modal-lg">
  <form method="post" action="process_leave_configuration.php">
  <div class="modal-content">
      <div class="modal-header">
        <h3 class="card-title">Reset All Assigned Leaves</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
      </div>
      <div class="modal-body">
        <p>You are trying to reset all employee's assigned leaves. Here is the current status (leave balance, leave used and leave remaining) of all assigned leaves for all employees.</p>
         <table class="table table-hover table-striped w-full" id="example">
                             <thead>
                               <tr>
                                   <th>Employee Code</th>
                                   <th>Employee Name</th>
                                   <th>Leave Type</th>
                                   <th>Leave Allowed</th>
                                   <th>Leave Used</th>
                                   <th>Leave Balance</th>
                               </tr>
                             </thead>
                             <?php
                             // select all data
                             $query = "SELECT tbl_leaveinfo.employeeID, employeeCode, leaveName, leaveRemaining, leaveUsed, allowedLeave, firstName, lastName FROM tbl_leaveinfo JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID JOIN tbl_employees ON tbl_leaveinfo.employeeID = tbl_employees.employeeID ORDER BY firstName ASC";
                             $stmt = $con->prepare($query);
                             $stmt->execute();
                             $num = $stmt->rowCount();
                             if($num>0){

                               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            
                                 echo "        <tr>

                                                   <td>" . $row['employeeCode'] . "</td>
                                                   <td>" . $row['firstName'] . ' ' .$row['lastName'] ."</td>
                                                   <td>" . $row['leaveName'] . "</td>
                                                   <td>" . $row['allowedLeave'] . "</td>
                                                   <td>" . $row['leaveUsed'] . "</td>
                                                   <td>" . $row['leaveRemaining'] . "</td>
                                               </tr>";
     

                                     }
                                     echo "</table>";
                                 } else {
                                     echo "</table>";
                                 }
                           ?>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="leaveID" value="<?php echo $row['leaveID'] ?>">
        <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        <button type="submit" id="real_reset_button" class="btn btn-primary"  name="resetAllLeaves"> <i class="fa fa-refresh" ></i> Reset All Assigned Leaves</button>
        <button type="button" onclick="reset_confirm()" class="btn btn-primary"> <i class="fa fa-refresh" ></i> Reset All Assigned Leaves</button>
</form>
      </div>
  </div>
</div>
</div>
    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
     <script src=""></script>
     <script src=""></script>
     <script src=""></script>
     <script src=""></script>
     <script src=""></script>
     <script src=""></script>
     <script src=""></script>
     <script src=""></script>
     <script src=""></script>
<script>https://code.jquery.com/jquery-3.3.1.js</script>
<script>https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js</script>
<script>https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js</script>
<script>https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js</script>
<script>https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js</script>

<script>
    $(document).ready(function() {
    $('#example').DataTable( {
        "order": [],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>
 <script>
   $(document).ready(function() {
       
              $('#employee').on('change', function() {
       var employeeID = $('#employee').val();
       $.ajax({
         url: "getLeaveExisting.php",
         method: "post",
         data: {employeeID:employeeID},
         dataType: "text",
         success:function(employee_result) {
           var employee_data = $.trim(employee_result);
           $('#leaveName').html("");
           $('#leaveName').html(employee_data); 
         }
       });
      
     });
     
     
     $('#leaveName').on('change', function() {
       var leaveID = $('#leaveName').val();
       $.ajax({
         url: "getLeaveDetails.php",
         method: "post",
         data: {leaveID:leaveID},
         dataType: "text",
         success:function(result) {
             
           var data = $.trim(result);
           $('#leaveCredits').html("");
           $('#leaveCredits').html(data); 
           $('#leaveBalance').html("");
           $('#leaveBalance').html(data);
         }
       });
        
     });

 
     
        $('#employeeSearch').on('change',function(){
            var employeeID = $('#employeeSearch').val();
            var leaveID = $('#leaveSearch').val();
            if(employeeID){
                $.ajax({
                    url: "getLeaveReports.php",
                    method: "post",
                    data: {employeeID:employeeID, leaveID:leaveID},
                    dataType: "text",
                        success:function(search_result) {
                        var search_data = $.trim(search_result);
                        $('#tableReports').html(search_data); 
                    }
                });
             }
        });

        $('#leaveSearch').on('change',function(){
            var employeeID = $('#employeeSearch').val();
            var leaveID = $('#leaveSearch').val();
            if(employeeID){
                $.ajax({
                    url: "getLeaveReports.php",
                    method: "post",
                    data: {employeeID:employeeID, leaveID:leaveID},
                    dataType: "text",
                        success:function(search_result) {
                        var search_data = $.trim(search_result);
                        $('#tableReports').html(search_data); 
                    }
                });
             }
        });

        $('#print').on('click',function(){
            var employeeID = $('#employeeSearch').val();
            var leaveID = $('#leaveSearch').val();
            
            window.location.href = "print_leave_report.php?employee_id="+employeeID+"leave_id="+leaveID;
        });
     
     
     
   });
 </script> 
 <script>
   function reset_confirm()   {
        $('#resetAllLeave').modal('hide');
        Swal.fire({
            title:'Do you wish to continue?',
            type:'warning',
            icon:'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $('#real_reset_button').click();
            } else {
                $('#resetAllLeave').modal('show');
            }
        });
    }
 </script>
 
 
  </body>
</html>
