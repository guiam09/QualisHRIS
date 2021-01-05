<?php
include_once ('../includes/configuration.php');

include ('../db/connection.php');
include ('../includes/fetchData.php');

// include login checker
$page_title="Admin";
$access_type ="Admin";

// include login checker
$require_login=true;
include_once "../includes/loginChecker.php";

include ('../includes/header.php');
include ('../includes/navbar.php');
$CURRENT_PAGE = "Employee Details";
include ('../includes/sidebar.php');


  
  if(isset($_GET['searchq'])){
        $getData = getEmployeeData($con, $_GET['searchq']);
  }else{
        $getData = getEmployeeData($con, $_SESSION['user_id']);
  }
?>
<style>
  /* Edit email buttons css */
.edit-button{
  margin-left: 5px !important; 
  visibility: visible;
}

.save-button{
  visibility: hidden; 
  margin-right: 5px !important; 
  margin-left: -90px !important;
}

.cancel-button{
  visibility: hidden;
}
</style>
<?php
    $userId = $_SESSION['user_id'];
    $query = "SELECT emailAddress FROM tbl_employees WHERE employeeCode = '$userId'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    // check if more than 0 record found
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentEmail = $row['emailAddress'];
      
    if (isset($_POST['enteredEmail'])) {
    
      try{
            if($currentEmail !== $_POST['enteredEmail'] && filter_var($_POST['enteredEmail'], FILTER_VALIDATE_EMAIL)){
                $queryUpdate = "UPDATE tbl_employees SET emailAddress=:enteredEmail WHERE employeeCode='$userId'";
                $stmt = $con->prepare($queryUpdate);

                // enter value parameters
                $enteredEmail = htmlspecialchars(strip_tags($_POST['enteredEmail']));
                // bind the parameters
                $stmt->bindParam(':enteredEmail', $enteredEmail);

                // Execute the query
                if($stmt->execute()){
                  echo "
                      <script>
                          window.open(window.location.href+'?update_email=success','_self');
                      </script>
                  ";
                }else {
                  echo "
                      <script>
                          window.open(window.location.href+'?update_email=failed','_self');
                      </script>
                  ";
                }
              }else if($currentEmail == $_POST['enteredEmail']){
                echo "
                      <script>
                          window.open(window.location.href+'?update_email=failed','_self');
                      </script>
                  ";
              }else if(!filter_var($_POST['enteredEmail'], FILTER_VALIDATE_EMAIL)){
                echo "
                      <script>
                          window.open(window.location.href+'?update_email=notValidEmail','_self');
                      </script>
                  ";
              }
            
          }catch(PDOException $exception){
              die('ERROR: ' . $exception->getMessage());
      }
    }
 ?>

    <!-- Page -->
    <div class="page">
      <div class="page-content container-fluid">
        <div class="row">
        <div class="col-md-12">
                  <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">Search Employee</h3>
            </div>
                 <div class="panel-body">
              <div class="form-group form-material row">
                      <label class="col-md-2 col-form-label">Employee Name: </label>
                      <div class="col-md-9">
                            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                         <select class="form-control" name="searchq" required>
                              <?php
                          // select all data
                          $query = "SELECT * FROM tbl_employees ORDER BY firstName ASC";
                          $stmt = $con->prepare($query);
                          $stmt->execute();
                          $num = $stmt->rowCount();
                          // check if more than 0 record found
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                            <option value="<?php echo $row['employeeCode'];?>"><?php echo $row['firstName'] . ' ' . $row['lastName'];?></option>
                            <!-- end of database -->
                           <?php
                           }
                          ?>

                         </select>
                         
                       
                        
                        
                      </div>
                     
                          <button class="btn btn-info" type="submit" name="search">Search</button>
                          </form>
                     
                    </div>
            </div>
          </div>
        </div>    
        </div>
    
              <?php
                                //ADD LEAVE
                                $message = isset($_GET['search_result']) ? $_GET['search_result'] : "";

                                if($message=='successful'){
                                    echo "<div class='alert alert-success'>Employee Details Successfully Updated!</div>";
                                }

                                else if($message=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to Update Employee Details.</div>";
                                }
                                
                                $email_message = isset($_GET['update_email']) ? $_GET['update_email'] : "";
                    
                                if($email_message=='success'){
                                  echo "<div class='alert alert-success'>Email changed successfully!
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span></button></div>";
                                }else if($email_message=='error'){
                                  echo "<div class='alert alert-danger'>Done unsuccessfully! Something wrong happen!
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span></button></div>";
                                }else if($email_message=='failed'){
                                  echo "<div class='alert alert-danger'>Done unsuccessfully! Same email being inputted!
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span></button></div>";
                                }else if($email_message=='notValidEmail'){
                                  echo "<div class='alert alert-danger'>Done unsuccessfully! Not a valid email being inputted!
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span></button></div>";
                                }
                             

                                ?>
   
        <div class="row">
          <div class="col-md-12">
              <div class="float-left mr-40">
                <div class="example">
                  <img class="img-rounded img-bordered-primary" width="150" height="140"
                   src="../images/<?php echo $getData['photo'] ?>" alt="...">
                </div>
              </div>
              <div class="float-left">
                <h2 class="person-name">
                  <a><?php echo $getData['firstName'].' '.$getData['lastName']; ?></a>
                </h2>
                  <!--<a class="blue-grey-400 font-size-20"><?php echo $getData['positionName'] ?></a>
                  <br> -->
                <div class="col-md-12">
                  <form method="POST" action="update_employee_details.php">
                    <div class="form-group row">
                      <input readonly type='text' name="enteredEmail" id='enteredEmail' class='col-sm-6 form-control border border-dark' value='<?php echo trim($getData['emailAddress'], ' ') ?>'>
                      <button type="button" id='editEmailBtn' onclick="change_email()" class="btn btn-info edit-button">Edit Email</button>
                      <button type="submit" id='saveEmailBtn' onclick="save_email()" class="btn btn-info save-button">Save</button>
                      <button type="button" id='cancelEmailBtn' onclick="cancel_email()" class="btn btn-info cancel-button">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="float-right">
                  <button type="button" data-target="#updateImage" data-toggle="modal" class="btn btn-block btn-info waves-effect waves-classic">Upload Picture</button>
                  <br>
                  <form method="post" id="resetPassword"action="resetPassword.php">
                     
                   <button type="button" onclick="resetpass()" name="resetPassword" id="resetPass" class="btn btn-block btn-info waves-effect waves-classic">Reset Password</button>
                    <input type="hidden" name="empCode" value="<?php echo $getData['employeeCode']; ?>">
                   </form> 
                   <!--<br>-->
                   <!--<button type="button" name="changeUsername" data-target="#changeUsername" data-toggle="modal" class="btn btn-block btn-info waves-effect waves-classic">Change Username</button>-->
            
              </div>
          </div>
        </div>
        <div class="row">
          <!-- Employment Details Panel -->
          <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                  <h3 class="panel-title">Employment Info</h3>
                  <div class="panel-actions">
                    <button type="button" data-target="#editEmployementDetails" data-toggle="modal" class="btn btn-block btn-info waves-effect waves-classic">Edit</button>
                  </div>
              </div>
              <table class="table">
                    <tbody>
                      <tr>
                        <td class="font-size-18">Date Hired</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['dateHired']; ?></b></td>
                      </tr>

                      <tr>
                        <td class="font-size-18">Department</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['departmentName']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Position</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['positionName']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Reports To</td>
                        <td colspan="3"class="text-right font-size-18"><b>  <?php
                        $reportingToName = $getData['reportingTo'];
                        $query2 = "SELECT * FROM tbl_employees WHERE employeeID = '$reportingToName'";
                        $stmt7 = $con->prepare($query2);
                        $stmt7->execute();
                        $rows = $stmt7->fetch(PDO::FETCH_ASSOC);
                        echo $rows['firstName'] . ' ' . $rows['lastName']
                         ?></b></td>
                      </tr>

                      <tr>
                        <td class="font-size-18">Address</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['address']; ?></b></td>
                      </tr>

                      <tr>
                        <td class="font-size-18">Tel. No.</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['contactInfo']; ?></b></td>
                      </tr>

                      <tr>
                        <td class="font-size-18">Civil Status</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['civilStatus']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Gender</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['gender']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Birth Date</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['birthdate']; ?></b></td>
                      </tr>

                    </tbody>
                </table>
            </div>
          </div>
          <!-- Identification Cards Panel -->
          <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                  <h3 class="panel-title">ID's</h3>
                  <div class="panel-actions">
                    <button type="button" data-target="#editID" data-toggle="modal" class="btn btn-block btn-info waves-effect waves-classic">Edit</button>
                  </div>
              </div>
              <table class="table">
                    <tbody>
                      <tr>
                        <td class="font-size-18">Employee ID</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['employeeCode']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">SSS ID</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['sssID']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">PhilHealth ID</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['philhealthID']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">PAGIBIG ID</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['pagibigID']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">TIN ID</td>
                          <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['tinID']; ?></b></td>
                      </tr>
                    </tbody>
                  </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                 <!-- <h3 class="panel-title">Joining Details</h3> -->
                  <div class="panel-actions">
                    <!-- <button type="button" data-target="#exampleFormModal" data-toggle="modal" class="btn btn-block btn-info waves-effect waves-classic">Edit</button> -->
                  </div>
              </div>
              <table class="table">
                   <!-- <tbody>
                      <tr>
                        <td class="font-size-18">Date Hired</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['dateHired']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Status</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['status']; ?></b></td>
                      </tr> 
                    </tbody> -->
                  </table>
            </div>
          </div> 
          <!-- <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                  <h3 class="panel-title">Status</h3>
                  <div class="panel-actions">

                  </div>
              </div>
              <table class="table">
                    <tbody>
                      <tr>
                        <td class="font-size-18">Date Hired</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['dateHired']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Status</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['status']; ?></b></td>
                      </tr>
                    </tbody>
                  </table>
            </div>
          </div> -->
        </div>
      </div>
    </div>
    <!-- End Page -->




        <div class="modal fade"  id="editEmployementDetails">
            <div class="modal-dialog modal-lg modal-center">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Update Employement Info</h4>
                  </div>
                    <div class="modal-body">
                      <form enctype = "multipart/form-data" method="post" action='process_edit_profile.php'  autocomplete="off">
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Department: </label>
                                    <div class="col-md-9">
                                      <select  name="department"  required data-plugin="select2" class="form-control" style="width: 100%;">
                                        <option selected value="<?php echo $getData['departmentID']; ?>"><?php echo $getData['departmentName']; ?></option>
                                        <?php
                                          // select all data
                                          $query = "SELECT * FROM tbl_department";
                                          $stmt = $con->prepare($query);
                                          $stmt->execute();
                                          $num = $stmt->rowCount();
                                          // check if more than 0 record found
                                          if($num>0){
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                              ?>
                                            <option value="<?php echo $row['departmentID'];?>"><?php echo $row['departmentName'];?></option>
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
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Position: </label>
                                    <div class="col-md-9">
                                        <select  name="position"  required data-plugin="select2" class="form-control" style="width: 100%;">
                                          <option selected value="<?php echo $getData['positionID']; ?>"><?php echo $getData['positionName']; ?></option>
                                          <?php
                                            // select all data
                                            $query = "SELECT * FROM tbl_position";
                                            $stmt = $con->prepare($query);
                                            $stmt->execute();
                                            $num = $stmt->rowCount();
                                            // check if more than 0 record found
                                            if($num>0){
                                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                              <option value="<?php echo $row['positionID'];?>"><?php echo $row['positionName'];?></option>
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
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Address: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="edit_contact" value="<?php echo $getData['address']; ?>" name="location">

                                    </div>
                                  </div>


                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Tel. No.: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="contactNumber" value="<?php echo $getData['contactInfo']; ?>" name="contactNumber">

                                    </div>
                                  </div>


                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Civil Status: </label>
                                    <div class="col-md-9">
                                      <!--  <input type="text" class="form-control" id="edit_contact" value="<?php echo $getData['civilStatus']; ?>" name="civilStatus"> -->
                                      
                              		<select class="form-control" style="width: 100%;" name="civilStatus" data-plugin="select2" required>
                                		<option selected value="<?php echo $getData['civilStatus']; ?>"><?php echo $getData['civilStatus']; ?></option>
                                		<option>Single</option>
                               		        <option>Married</option>
                                		<option>Divorced</option>
                                		<option>Widowed</option>
                             		 </select>

                                    </div>
                                  </div>


                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Reports To: </label>
                                    <div class="col-md-9">
                                      <select  name="reportingTo"  required data-plugin="select2" class="form-control" style="width: 100%;">
                                        <option value="<?php echo $getData['reportingTo']; ?>"><p class="text-muted"><?php echo $rows['firstName'] . ' ' . $rows['lastName'] ?></p></option>
                                        <?php
                                          // select all data
                                          $query = "SELECT * FROM tbl_employees";
                                          $stmt = $con->prepare($query);
                                          $stmt->execute();
                                          $num = $stmt->rowCount();
                                          // check if more than 0 record found
                                          if($num>0){
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                              ?>
                                            <option value="<?php echo $row['employeeID'];?>"><?php echo $row['firstName'] . ' ' . $row['lastName']?></option>
                                            <!-- end of database -->
                                           <?php
                                           }
                                           // if no records found
                                           }else{
                                             ?>
                                               <option>"><?php echo "No Records Found";?></option>
                                             <?php
                                             echo "no records found";
                                           }
                                          ?>
                                    </select>

                                    </div>
                                  </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                      <input type="hidden" name="editedUser" value="<?php echo $getData['employeeCode']; ?>"/>
                      <button type="submit" class="btn btn-info" name="edit_currentPosition"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade"  id="editID">
            <div class="modal-dialog modal-lg modal-center">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Update Identification Cards</h4>
                  </div>
                    <div class="modal-body">
                      <form enctype = "multipart/form-data" method="post" action='process_edit_profile.php'  autocomplete="off">
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Employee ID: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="edit_contact" readonly name="employeeID" value="<?php echo $getData['employeeCode']; ?>">

                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">SSS ID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="sssID" value="<?php echo $getData['sssID'];; ?>">-->
                                    <input type="text" class="form-control" id="sssID"
                                      placeholder="12-1234567-1" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[99]]-[[9999999]]-[[9]]" name="sssID" value="<?php echo $getData['sssID']; ?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">PhilHealthID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="philhealthID" value="<?php echo $getData['philhealthID']; ?>">-->
                                        <input type="text" class="form-control" id="inputBasicLastName" 
                                      placeholder="123-123-123-123"  autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[9999]]-[[9999]]-[[9999]]" name="philhealthID" value="<?php echo $getData['philhealthID']; ?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">PAGIBIG ID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="pagibigID" value="<?php echo $getData['pagibigID']; ?>">-->
                                    <input type="text" class="form-control" id="inputCredit" data-plugin="formatter"
                      data-pattern="[[9999]]-[[9999]]-[[9999]]" 
                                      placeholder="1234-1234-1234-1234" autocomplete="off" name="pagibigID" value="<?php echo $getData['pagibigID']; ?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">TIN ID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="tinID" value="<?php echo $getData['tinID']; ?>">-->
                                     <input type="text" class="form-control" id="inputBasicLastName" 
                                      placeholder="123-123-123-123" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[999]]-[[999]]-[[999]]-[[999]]" name="tinID" value="<?php echo $getData['tinID']; ?>">
                                    </div>
                                  </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                      <input type="hidden" name="editedUser" value="<?php echo $getData['employeeCode']; ?>"/>
                      <button type="submit" class="btn btn-info" name="editID"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade"  id="updateImage">
            <div class="modal-dialog modal-lg modal-center">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Update Picture</h4>
                  </div>
                    <div class="modal-body">
                      <form enctype = "multipart/form-data" method="post" action='process_edit_profile.php'  autocomplete="off">
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Update Picture: </label>
                                    <div class="col-md-9">
                                       <!-- <input required type = "file" name = "Image" /> -->
                                     <input type="file" id="input-file-now-custom-2" required name="Image" data-plugin="dropify" data-height="100">
                                    </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                       <input type="hidden" name="editedUser" value="<?php echo $getData['employeeCode']; ?>"/>
                      <button type="submit" class="btn btn-info" name="updateImage"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        </div>        
        <div class="modal fade"  id="changeUsername">
            <div class="modal-dialog modal-lg modal-center">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Change Username</h4>
                  </div>
                    <div class="modal-body">
                      <form enctype = "multipart/form-data" method="post" action='process_edit_profile.php'  autocomplete="off">
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Address: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="edit_contact" value="<?php echo $getData['address']; ?>" name="location">

                                    </div>
                                  </div>
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                       <input type="hidden" name="editedUser" value="<?php echo $getData['employeeCode']; ?>"/>
                      <button type="submit" class="btn btn-info" name="updateImage"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        
    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
<script>
    function change_email() {
        document.getElementById("enteredEmail").removeAttribute("readonly");
        document.getElementById("editEmailBtn").style.visibility = "hidden";
        document.getElementById("cancelEmailBtn").style.visibility = "visible";
        document.getElementById("saveEmailBtn").style.visibility = "visible";
    }

    function save_email() {
        document.getElementById("enteredEmail").setAttribute("readonly", "_self");
        document.getElementById("editEmailBtn").style.visibility = "visible";
        document.getElementById("cancelEmailBtn").style.visibility = "hidden";
        document.getElementById("saveEmailBtn").style.visibility = "hidden";
    }

    function cancel_email() {
        document.getElementById("enteredEmail").setAttribute("readonly", "_self");
        document.getElementById("editEmailBtn").style.visibility = "visible";
        document.getElementById("cancelEmailBtn").style.visibility = "hidden";
        document.getElementById("saveEmailBtn").style.visibility = "hidden";
    }
    function resetpass(){
         var form = document.getElementById("resetPassword");
        Swal.fire({
              title: 'Reset Password',
              text: "Do you want to reset this user's password?",
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Continue'
            }).then((result) => {
              if (result.value) {
                  
                     
                Swal.fire(
                  'Password Reset!',
                  'Reloading. . .',
                  'success'
                )
                 form.submit();
              }
            })
    }
</script>
<?php
if($_GET['reset'] === 'success'){
    ?>
    <script>
        Swal.fire(
  'Success',
  'Password has been reset successfully',
  'success'
)
    </script>
    <?php
}

?>
  </body>
</html>
