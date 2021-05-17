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
$CURRENT_PAGE = "Add Employee";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');


?>


   
     
    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title">Add Employee</h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                      <!-- Panel Floating Labels -->
                      <div class="panel">
                        <div class="panel-heading">
                          <h3 class="panel-title text-danger font-size-14"><b>* Required fields</b></h3>
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
                                <label class="form-control-label" for="inputBasicFirstName">Last Name <a class="text-danger">*</a></label>
                                <input type="text" class="form-control focus" id="lastName" name="lastName" required
                                  placeholder="Last Name" autocomplete="off" />
                              </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicLastName">First Name <a class="text-danger">*</a></label>
                                <input type="text" class="form-control focus" id="firstName" name="firstName" required
                                  placeholder="First Name" autocomplete="off" />
                              </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicLastName">Middle Name</label>
                                <input type="text" class="form-control" id="middleName" name="middleName"
                                  placeholder="Middle Name" autocomplete="off" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group form-material col-md-4">
                                  <label class="form-control-label" for="inputBasicEmail">Gender <a class="text-danger">*</a></label>
                              <select class="form-control focus" title="Please Select Gender" required name="gender" required data-plugin="selectpicker">
                                <option></option>
                                  <option value="Male">Male</option>
                                  <option value="Female">Female</option>
                                  <option value="Others">Others</option>
                              </select>
                            </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicEmail">Contact Number <a class="text-danger">*</a></label>
                            
                                <input type="text" class="form-control focus" id="contactNumber" name="contactNumber" required 
                                  placeholder="Enter Contact Number" autocomplete="off" />
                              </div>
                              <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicPassword">Birthdate <a class="text-danger">*</a></label>
                                <input type="text" class="form-control focus" data-date-end-date="-15y" data-plugin="datepicker" id="birthdate" name="birthDate" required
                                  placeholder="Select a Date" autocomplete="off" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group form-material col-md-4">
                                  <label class="form-control-label" for="inputBasicEmail">Civil Status <a class="text-danger">*</a></label>
                              <select class="form-control focus" name="civilStatus" required data-plugin="selectpicker" title="Please Select Civil Status" required>
                                <option></option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Divorced</option>
                                <option>Widowed</option>
                              </select>
                            </div>
                            <div class="form-group form-material col-md-4">
                              <label class="form-control-label" required for="inputBasicEmail">Email Address <a class="text-danger">*</a></label>
                              <input type="email" class="form-control focus" id="emailAddress" name="emailAddress" required
                                placeholder="name@email.com" autocomplete="off" />
                            </div>
                            <div class="form-group form-material col-md-4">
                              <label class="form-control-label" required for="inputBasicEmail">Photo <a class="text-danger">*</a></label>
                              <br>
                              <!--<button><input type="file" required name="Image" />Choose Image</button>-->
                               <input type="file" id="input-file-now-custom-2" required name="Image" data-plugin="dropify" data-height="100">
                            </div>
                            </div>
                            <div class="row">
                                  <div class="form-group form-material col-md-4">
                                <label class="form-control-label" for="inputBasicPassword">Address <a class="text-danger">*</a></label>
                                <input type="textarea" class="form-control focus" placeholder="Street, Village, City" name="address" required>
                              </div>
                            </div>
                            <!--<div class="row">-->
                            <!--  <div class="form-group form-material col-md-4">-->
                            <!--    <label class="form-control-label" requierd for="inputBasicEmail">Address</label>-->
                            <!--  <textarea class="form-control" rows="3" placeholder="Enter Address Here"name="address"></textarea>-->
                            <!--</div>-->
                            <!--</div>-->
                            <!-- Dependents row -->
                            <!--<h3 class="panel-title text-primary">Dependents</h3>-->
                            <!--<br>-->
                            <!--  <div class="row">-->
                            <!--    <div class="form-group form-material col-md-4">-->
                            <!--      <label class="form-control-label" for="inputBasicFirstName">Dependent Name</label>-->
                            <!--      <input type="text" class="form-control" id="inputBasicFirstName" name="dependentName"-->
                            <!--        placeholder="First Name" autocomplete="off" />-->
                            <!--    </div>-->
                            <!--    <div class="form-group form-material col-md-4">-->
                            <!--        <label class="form-control-label" for="inputBasicEmail">Dependents</label>-->
                            <!--    <select class="form-control" data-plugin="select2" name="dependentRelation" data-placeholder="Select Here">-->
                            <!--      <option></option>-->
                            <!--        <option value="AK">Male</option>-->
                            <!--        <option value="HI">Female</option>-->
                            <!--    </select>-->
                            <!--  </div>-->
                            <!--  </div>-->

                              <!--<div class="form-group form-material">-->
                              <!--  <button type="button" class="btn btn-primary">Add Another</button>-->
                              <!--</div>-->
                              <!-- Identification Cards row -->
                              <h3 class="panel-title text-primary">Government ID's</h3>
                              <br>
                                <div class="row">
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">PAGIBIG ID</label>
                                    <input type="text" class="form-control" id="inputCredit" data-plugin="formatter"
                      data-pattern="[[9999]]-[[9999]]-[[9999]]" name="pagibigID"
                                      placeholder="1234-1234-1234-1234" autocomplete="off" />
                                        <p class="text-help">1234-1234-1234</p>
                                  </div>
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">SSS ID</label>
                                    <input type="text" class="form-control" id="sssID" name="sssID"
                                      placeholder="12-1234567-1" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[99]]-[[9999999]]-[[9]]" />
                          <p class="text-help">12-1234567-1</p>
                                  </div>
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">TIN ID</label>
                                    <input type="text" class="form-control" id="inputBasicLastName" name="tinID"
                                      placeholder="123-123-123-123" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[999]]-[[999]]-[[999]]-[[999]]" />
                          <p class="text-help">123-123-123-123</p>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="form-group form-material col-md-4">
                                    <label class="form-control-label" for="inputBasicLastName">PhilHealth ID</label>
                                    <input type="text" class="form-control" id="inputBasicLastName" name="philhealthID"
                                      placeholder="123-123-123-123"  autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[9999]]-[[9999]]-[[9999]]" />
                            <p class="text-help">1234-1234-1234</p>
                                  </div>
                                </div>
                                <!-- Employee Details Cards row -->
                                <h3 class="panel-title text-primary">Employment Information</h3>
                                <br>
                                  <div class="row">
                                    <div class="form-group form-material col-md-4">
                                        <label class="form-control-label" for="inputBasicEmail">Access Level <a class="text-danger">*</a></label>
                                    <select class="form-control focus" data-plugin="select2" name="accessLevel" data-placeholder="Select Access Level">
                                      <option></option>
                                        <?php
                                        // select all data
                                        $query = "SELECT * FROM tbl_accesslevels  ORDER BY accessLevelName ASC";
                                        $stmt = $con->prepare($query);
                                        $stmt->execute();
                                        $num = $stmt->rowCount();
                                        // check if more than 0 record found
                                        if($num>0){
                                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                                        echo  '<option value="'.$row['accessLevelID'].'">'.$row['accessLevelName'].'</option>';


                                         }
                                         // if no records found
                                         }else{
                                           echo "no records found";
                                         }
                                        ?>

                                    </select>
                                  </div>

                                 <!--  <div class="form-group form-material col-md-4">
                                 <!--    <label class="form-control-label" for="inputBasicEmail">Core Time</label>
                                 <!-- <select class="form-control focus" data-plugin="select2"  name="coreTime" data-placeholder="Select Core Time"> 
                                   <option></option> 
                                    <?php
                                      //// select all data
                                      //$query = "SELECT * FROM tbl_coretime";
                                      //$stmt = $con->prepare($query);
                                      //$stmt->execute();
                                      //$num = $stmt->rowCount();
                                      //// check if more than 0 record found
                                      //if($num>0){
                                      //  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                      //    $timeIn = $row['timeIn'];
                                      //    $timeOut = $row['timeOut'];

                                      //  $timeIn  = date("g:i a", strtotime("$timeIn"));
                                      //  $timeOut  = date("g:i a", strtotime("$timeOut"));
                                          ?> 

                                <!--       <option value="<?php echo $row['coreTimeID'];?>"><?php echo $timeIn . ' - ' . $timeOut?></option> -->
                                        <!-- end of database --> 
                                       <?php
                                   //    }
                                   //     if no records found
                                   //    }else{
                                   //      echo "no records found";
                                   //    }
                                      ?>
                                  </select> 
                                <!-- </div> -->

                                <div class="form-group form-material col-md-4">
                                  <label class="form-control-label" for="inputBasicPassword">Date Hired <a class="text-danger">*</a></label>
                                  <input type="text" class="form-control focus" data-plugin="datepicker" id="dateHired" name="dateHired" required
                                    placeholder="Select a Date" autocomplete="off" />
                                </div>
                                  </div>
                                  <div class="row">
                                    <div class="form-group form-material col-md-4">
                                        <label class="form-control-label" for="inputBasicEmail">Department <a class="text-danger">*</a></label>
                                    <select class="form-control " id="department" data-plugin="select2"  name="department" data-placeholder="Select Department">
                                      <option></option>
                                      <?php
                                        // select all data
                                        $query = "SELECT * FROM tbl_department ORDER BY departmentName ASC";
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
                                      <label class="form-control-label" for="inputBasicEmail">Position <a class="text-danger">*</a></label>
                                  <select class="form-control" data-plugin="select2"  id="position" name="position" data-placeholder="Select Position">
                                    <option></option>

                                  </select>
                                </div>
                                <div class="form-group form-material col-md-4">
                                    <label class="form-control-label focus" for="inputBasicEmail">Reporting To <a class="text-danger">*</a></label>
                                <select class="form-control" data-plugin="select2"  id="reportingTo" name="reportingTo" data-placeholder="Select Supervisor">
                                  <option></option>

                                </select>
                              </div>
                                  </div>
                                  <br>
                                  <div class="offset-md-12 form-group form-material">
                                    <button type="button"  name="add" class="btn btn-info" onclick="verify()" id="validateButton1">Submit</button>
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

     document.documentElement.scrollTop = 0;

     $('#department').on('change', function() {
       var department = $('#department').val();
       $.ajax({
         url: 'getPosition.php',
         type: 'POST',
         data: 'departmentID='+department,
       
         success:function(department_result) {
           var department_data = $.trim(department_result);
           $('#position').html("");
          
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
 
<script>
function verify() {
   
    var form = $(this).parents('form');
    var firstName =  $("#firstName").val();
    var lastName =  $("#lastName").val();
    var gender =  $("#gender").val();
    var contactNumber =  $("#contactNumber").val();
    var birthdate =  $("#birthdate").val();
    var civilStatus =  $("#civilStatus").val();
    var address =  $("#address").val();
    var photo =  $("#Image").val();
    var emailAddress =  $("#emailAddress").val();
    var accessLevel =  $("#accessLevel").val();
    var coreTime =  $("#coreTime").val();
    var dateHired =  $("#dateHired").val();
    var department =  $("#department").val();
    var position =  $("#position").val();
    var reportingTo =  $("#reportingTo").val();
    var form = document.getElementById("exampleFullForm");

    var requiredFields = [];
    requiredFields.push({ Name: "Last Name", Value: lastName });
    requiredFields.push({ Name: "First Name", Value: firstName });
    requiredFields.push({ Name: "Gender", Value: gender });
    requiredFields.push({ Name: "Contact Number", Value: contactNumber });
    requiredFields.push({ Name: "Birthdate", Value: birthdate });
    requiredFields.push({ Name: "Civil Status", Value: civilStatus });
    requiredFields.push({ Name: "Email Address", Value: emailAddress });
    requiredFields.push({ Name: "Photo", Value: photo });
    requiredFields.push({ Name: "Address", Value: address });
    requiredFields.push({ Name: "Access Level", Value: accessLevel });
    requiredFields.push({ Name: "Date Hired", Value: dateHired });
    requiredFields.push({ Name: "Core Time", Value: coreTime });s
    requiredFields.push({ Name: "Department", Value: department });
    requiredFields.push({ Name: "Position", Value: position });
    requiredFields.push({ Name: "Reporting To", Value: reportingTo });
    
    var fieldList = "";
    for (var i = 0; i < requiredFields.length; i++) {
      var field = requiredFields[i];
      if (!field.Value) {
        fieldList += "<br/>" +  field.Name;
      }
    }

    var incompleteData = fieldList !== "";
    if (incompleteData) {
        Swal.fire({
          type: 'error',
          title: 'INCOMPLETE DATA',
          html: 'Need to input all required fields.' + fieldList
        })
    }else{

           $.ajax({
             url: "checkAvailability.php",
             method: "post",
             data: {firstName:firstName, lastName:lastName},
             dataType: "text",
             success:function(availability_result) {
               var availability_data = $.trim(availability_result);
               if(availability_data == "yes"){
                   
                   var text = "There is already an employee named " + firstName + " " + lastName + " on the database. Do you still want to continue?";
                    Swal.fire({
              title: 'Existing Employee',
              text: text,
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Continue'
            }).then((result) => {
               
              if (result.value) {
                    
                Swal.fire(
                  'Employee Added!',
                  'Uploading Employees Data. . .',
                  'success'
                )
                 form.submit();
                 
              }
            })
            
                
            
                   
               }else{
                   
          Swal.fire({
              title: 'Add Employee?',
              text: "Do you want to continue?",
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Continue'
            }).then((result) => {
              if (result.value) {
                  
                     
                Swal.fire(
                  'Employee Added!',
                  'Uploading Employees Data. . .',
                  'success'
                )
                 form.submit();
                 
              }
            })
            
            
               }
             }
           });

   
        
                    
            
        
    }
    
    
          
}

        

</script>

  <script>
  $(document).ready(function(){


    $("#birthdate").datepicker({
      viewMode: "years",
      format: 'yyyy/mm/dd',
      // endDate : new Date()


    });



  });


  </script>
 
 
 
  </body>
</html>
