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
        <?php

        $message = isset($_GET['add_employee']) ? $_GET['add_employee'] : "";

        if($message=='success'){
        echo '<div class="alert dark alert-icon alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              <i class="icon md-check" aria-hidden="true"></i> Employee Successfully Added!
              </div>';
        }

        else if($message=='failed'){
          echo '<div class="alert dark alert-icon alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon md-close" aria-hidden="true"></i> Unable to add Employee
                </div>';
        }

         ?>

          <h1 class="page-title">Add Employee</h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                      <!-- Panel Floating Labels -->
                      <div class="panel">
                        <div class="panel-heading">
                          <h3 class="panel-title text-danger">* Required Field</h3>
                        </div>
                        <div class="panel-body container-fluid">
                          <h3 class="panel-title text-primary">Personal Information</h3>
                          <br>
                            <form enctype = "multipart/form-data" method="post" action="process_add_employee.php" id="exampleFullForm" autocomplete="off">
                              <div class="row">
                                <div class="form-group form-material col-md-4">
                                  <?php include ('../includes/generateEmployeeID.php'); ?>
                                  <label class="form-control-label" for="inputBasicFirstName">Employee ID</label>
                                  <input type="text" class="form-control" value="<?php echo generateEmpID();?> " name="employeeCode" readonly
                                    placeholder="" autocomplete="off" />
                                </div>
                              </div>
                            <div class="row">
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicFirstName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required
                                  placeholder="Last Name" autocomplete="off" />
                              </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicLastName">First Name</label>
                                <input type="text" class="form-control" id="inputBasicLastName" name="firstName" required
                                  placeholder="Last Name" autocomplete="off" />
                              </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicLastName">Middle Name</label>
                                <input type="text" class="form-control" id="middleName" name="middleName"
                                  placeholder="Middle Name" autocomplete="off" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group form-material col-md-4">
                                  <label class="form-control-label" for="inputBasicEmail">Gender</label>
                              <select class="form-control" required name="gender" data-plugin="select2" data-placeholder="Select Here">
                                <option></option>
                                  <option value="Male">Male</option>
                                  <option value="Female">Female</option>
                              </select>
                            </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicEmail">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" name="contactNumber" required
                                  placeholder="Enter number here" autocomplete="off" />
                              </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicPassword">Birthdate</label>
                                <input type="text" class="form-control" data-plugin="datepicker" id="birthdate" name="birthDate" required
                                  placeholder="Select a Date" autocomplete="off" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group form-material col-md-4">
                                  <label class="form-control-label" for="inputBasicEmail">Civil Status</label>
                              <select class="form-control" name="civilStatus" data-plugin="select2" data-placeholder="Select Here" required>
                                <option></option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Divorced</option>
                                <option>Separated</option>
                                <option>Widowed</option>
                              </select>
                            </div>
                            <div class="form-group form-material col-md-4">
                              <label class="form-control-label" required for="inputBasicEmail">Email Address</label>
                              <input type="email" class="form-control" id="emailAddress" name="emailAddress"
                                placeholder="Email Address" autocomplete="off" />
                            </div>
                            <div class="form-group form-material col-md-4">
                              <label class="form-control-label" required for="inputBasicEmail">Photo</label>
                              <br>
                              <button><input type="file" required name="Image" />Choose Image</button>
                            </div>
                            </div>
                            <div class="row">
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" requierd for="inputBasicEmail">Address</label>
                              <textarea class="form-control" rows="3" placeholder="Enter Address Here"name="address"></textarea>
                            </div>
                            </div>
                            <!-- Dependents row -->
                            <h3 class="panel-title text-primary">Dependents</h3>
                            <br>
                              <div class="row">
                                <div class="form-group form-material col-md-4">
                                  <label class="form-control-label" for="inputBasicFirstName">Dependent Name</label>
                                  <input type="text" class="form-control" id="inputBasicFirstName" name="dependentName"
                                    placeholder="First Name" autocomplete="off" />
                                </div>
                                <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicEmail">Dependents</label>
                                <select class="form-control" data-plugin="select2" name="dependentRelation" data-placeholder="Select Here">
                                  <option></option>
                                    <option value="AK">Male</option>
                                    <option value="HI">Female</option>
                                </select>
                              </div>
                              </div>

                              <div class="form-group form-material">
                                <button type="button" class="btn btn-primary">Add Another</button>
                              </div>
                              <!-- Identification Cards row -->
                              <h3 class="panel-title text-primary">Identification Cards</h3>
                              <br>
                                <div class="row">
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">PAGIBIG ID</label>
                                    <input type="text" class="form-control" id="inputCredit" data-plugin="formatter"
                      data-pattern="[[9999]]-[[9999]]-[[9999]]" name="pagibigID"
                                      placeholder="PAGIBIG ID" autocomplete="off" />
                                        <p class="text-help">1234-1234-1234</p>
                                  </div>
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">SSS ID</label>
                                    <input type="text" class="form-control" id="sssID" name="sssID"
                                      placeholder="SSS ID" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[99]]-[[9999999]]-[[9]]" />
                          <p class="text-help">12-1234567-1</p>
                                  </div>
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">TIN ID</label>
                                    <input type="text" class="form-control" id="inputBasicLastName" name="tinID"
                                      placeholder="TIN ID" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[999]]-[[999]]-[[999]]-[[999]]" />
                          <p class="text-help">123-123-123-123</p>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">PhilHealth ID</label>
                                    <input type="text" class="form-control" id="inputBasicLastName" name="philhealthID"
                                      placeholder="PhilHealth ID"  autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[9999]]-[[9999]]-[[9999]]" />
                            <p class="text-help">1234-1234-1234</p>
                                  </div>
                                </div>
                                <!-- Employee Details Cards row -->
                                <h3 class="panel-title text-primary">Employee Details</h3>
                                <br>
                                  <div class="row">
                                    <div class="form-group form-material col-md-4">
                                        <label class="form-control-label" for="inputBasicEmail">Access Level</label>
                                    <select class="form-control" data-plugin="select2" name="accessLevel" data-placeholder="Select Here">
                                      <option></option>
                                      <option value="2">Employee</option>
                                      <option value="1">Admin</option>
                                    </select>
                                  </div>
                                  <div class="form-group form-material col-md-4">
                                      <label class="form-control-label" for="inputBasicEmail">Core Time</label>
                                  <select class="form-control" data-plugin="select2"  name="coreTime" data-placeholder="Select Here">
                                    <option></option>
                                    <?php
                                      // select all data
                                      $query = "SELECT * FROM tbl_coretime";
                                      $stmt = $con->prepare($query);
                                      $stmt->execute();
                                      $num = $stmt->rowCount();
                                      // check if more than 0 record found
                                      if($num>0){
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                          $timeIn = $row['timeIn'];
                                          $timeOut = $row['timeOut'];

                                        $timeIn  = date("g:i a", strtotime("$timeIn"));
                                        $timeOut  = date("g:i a", strtotime("$timeOut"));
                                          ?>

                                        <option value="<?php echo $row['coreTimeID'];?>"><?php echo $timeIn . ' - ' . $timeOut?></option>
                                        <!-- end of database -->
                                       <?php
                                       }
                                       // if no records found
                                       }else{
                                         echo "no records found";
                                       }
                                      ?>
                                  </select>
                                </div>
                                <div class="form-group form-material col-md-4">
                                  <label class="form-control-label" for="inputBasicPassword">Date Hired</label>
                                  <input type="text" class="form-control" data-plugin="datepicker" id="dateHired" name="dateHired"
                                    placeholder="Select a Date" autocomplete="off" />
                                </div>
                                  </div>
                                  <div class="row">
                                    <div class="form-group form-material col-md-4">
                                        <label class="form-control-label" for="inputBasicEmail">Department</label>
                                    <select class="form-control" id="department" data-plugin="select2"  name="department" data-placeholder="Select Here">
                                      <option></option>
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
                                  <div class="form-group form-material col-md-4">
                                      <label class="form-control-label" for="inputBasicEmail">Position</label>
                                  <select class="form-control" data-plugin="select2"  id="position" name="position" data-placeholder="Select Here">
                                    <option></option>

                                  </select>
                                </div>
                                <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicEmail">Reporting To</label>
                                <select class="form-control" data-plugin="select2"  id="reportingTo" name="reportingTo" data-placeholder="Select Here">
                                  <option></option>

                                </select>
                              </div>
                                  </div>
                                  <br>
                                  <div class="offset-md-11 form-group form-material">
                                    <button type="submit" class="btn btn-primary" name="add" id="validateButton1">Submit</button>
                                  </div>
                                  </form>

                        </div>
                      </div>
                      <!-- End Panel Floating Labels -->
                    </div>
        </div>
      </div>
    </div>
    <!-- End Page -->


    <!-- Footer -->


<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
 <script>
   $(document).ready(function() {
     $('#department').on('change', function() {
       var department = $('#department').val();
       $.ajax({
         url: "getPosition.php",
         method: "post",
         data: {department:department},
         dataType: "text",
         success:function(department_result) {
           var department_data = $.trim(department_result);
           $('#position').html("");
           // $('#reportingTo').html("");
           $('#position').html(department_data);
         }
       });
     });


   });
 </script>
 <script>

 $('#department').on('change',function(){
     var departmentID = $(this).val();
     if(departmentID){
         $.ajax({
             type:'POST',
             url:'getSupervisor.php',
             data:'departmentID='+departmentID,
             success:function(html){
                 $('#reportingTo').html(html);

             }
         });
     }else{
         $('#reportingTo').html('<option value="">Select department first</option>');

     }
 });

 </script>
  </body>
</html>
