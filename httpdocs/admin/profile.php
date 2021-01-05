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
$CURRENT_PAGE="Profile";
include ('../includes/sidebar.php');

$getData = getEmployeeData($con, $_SESSION['user_id']);

$userId = $_SESSION['user_id'];
$query = "SELECT emailAddress FROM tbl_employees WHERE employeeCode = '$userId'";
$stmt = $con->prepare($query);
$stmt->execute();
$num = $stmt->rowCount();
// check if more than 0 record found
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$currentEmail = $row['emailAddress'];
$adminAccess = [];

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
                      window.open('?update_email=success','_self');
                  </script>
              ";
            }else {
              echo "
                  <script>
                      window.open('?update_email=error','_self');
                  </script>
              ";
            }
          }else if($currentEmail == $_POST['enteredEmail']){
            echo "
                  <script>
                      window.open('?update_email=failed','_self');
                  </script>
              ";
          }else if(!filter_var($_POST['enteredEmail'], FILTER_VALIDATE_EMAIL)){
            echo "
                  <script>
                      window.open('?update_email=notValidEmail','_self');
                  </script>
              ";
          }
        
      }catch(PDOException $exception){
          die('ERROR: ' . $exception->getMessage());

}

}
?>
  
<script>
    function change_email() {
      // // if (event.target == modal) {
      //   modal.style.display = "none";
      // //}
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
</script>
<style>
  /* Edit email buttons css */
.edit-button{
  margin-left: 5px;
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
    $searchq = $_SESSION['employeeID'];
    $query = "SELECT DISTINCT accessedModules FROM tbl_accesslevels JOIN tbl_accessLevelEmp 
            ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID JOIN tbl_accessLevelModules 
            ON tbl_accessLevelModules.accessLevelID = tbl_accesslevels.accessLevelID WHERE tbl_accessLevelEmp.employeeID = '$searchq'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      array_push($adminAccess, $row['accessedModules']);
    }

?>
    <!-- Page -->
    <div class="page">
      <div class="page-content container-fluid">
          <?php
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
          <div class="col-lg-12">
            <div class="float-left mr-40">
              <div class="example">
                <img class="img-rounded img-bordered-primary" width="150" height="140"
                  src="../images/<?php
                    // echo 'female_avatar.jpg'
                    if(file_exists("../images/".$getData['photo'])){ 
                      echo $getData['photo'];
                    }else {
                        if($getData['gender']== "Female"){
                          echo "female_avatar.jpg";
                        }else{
                          echo "male_avatar.jpg";
                        }
                    }  

                  ?>" alt="...">
              </div>
            </div>
            <h2 class="person-name">
              <a><?php echo $getData['firstName'].' '.$getData['lastName']; ?></a>
            </h2>
            <p class="card-text">
              <div <?php if(in_array("aedEmployeeModule", $adminAccess)){ echo "style='display: block;'"; }
                    else echo "style='display: none;'"; ?>>
                  <form method="post" action="">
                    <div class="form-group row">
                      <input readonly type='text' name="enteredEmail" id='enteredEmail' class='col-sm-3 form-control border border-dark' value='<?php echo trim($getData['emailAddress'], ' ') ?>'>
                      <button type="button" id='editEmailBtn' onclick="change_email()" class="btn btn-info edit-button">Edit Email</button>
                      <button type="submit" id='saveEmailBtn' onclick="save_email()" class="btn btn-info save-button">Save</button>
                      <button type="button" id='cancelEmailBtn' onclick="cancel_email()" class="btn btn-info cancel-button">Cancel</button>
                    </div>
                  </form>
              </div>
            </p>
            <div class="card-text" <?php 
              if(in_array("aedEmployeeModule", $adminAccess)){ echo "style='display: none;'"; }
              else echo "style='display: block;'"; ?>>
                <div class="form-group row">
                  <a class="blue-grey-400 font-size-16"><i><?php echo $getData['emailAddress'] ?></i></a>
                </div>
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
                    <!--<button type="button" data-target="#editEmployementDetails" data-toggle="modal" class="btn btn-block btn-primary waves-effect waves-classic">Edit</button>-->
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
                        <td class="font-size-18">Address</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['address']; ?></b></td>
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
                    <!--<button type="button" data-target="#editID" data-toggle="modal" class="btn btn-block btn-primary waves-effect waves-classic">Edit</button>-->
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
                  <!--<h3 class="panel-title">Joining Details</h3> -->
                  <div class="panel-actions">
                    <!-- <button type="button" data-target="#exampleFormModal" data-toggle="modal" class="btn btn-block btn-primary waves-effect waves-classic">Edit</button> -->
                  </div>
              </div>
              <table class="table">
                    <!--<tbody>
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
    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>




        <div class="modal fade"  id="editEmployementDetails">
            <div class="modal-dialog modal-lg">
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
                                        <option selected value="<?php echo $getData['departmentName']; ?>"><?php echo $getData['departmentName']; ?></option>
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
                                          <option selected value="<?php echo $getData['positionName']; ?>"><?php echo $getData['positionName']; ?></option>
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
                                    <label class="col-md-3 col-form-label">Location: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="edit_contact" value="<?php echo $getData['address']; ?>" name="location">

                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Reports To: </label>
                                    <div class="col-md-9">
                                      <select  name="reportingTo"  required data-plugin="select2" class="form-control" style="width: 100%;">
                                        <option value="<?php echo $getData['reportingTo']; ?>"><p class="text-muted"><?php echo $getData['firstName'] . ' ' . $getData['lastName'] ?></p></option>
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
                      <button type="submit" class="btn btn-primary" name="edit_currentPosition"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade"  id="editID">
            <div class="modal-dialog modal-lg">
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
                        data-pattern="[[99]]-[[9999999]]-[[9]]" name="sssID" value="<?php echo $getData['sssID'];; ?>">
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
                      <button type="submit" class="btn btn-primary" name="editID"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade"  id="updateImage">
            <div class="modal-dialog modal-lg">
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
                                        <input required type = "file" name = "Image" />

                                    </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                      <button type="submit" class="btn btn-primary" name="updateImage"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>


  </body>
</html>

<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');

function updateEmail(){
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
                          window.open('?update_email=success','_self');
                      </script>
                  ";
                }else {
                  echo "
                      <script>
                          window.open('?update_email=failed','_self');
                      </script>
                  ";
                }
              }else if($currentEmail == $_POST['enteredEmail']){
                echo "
                      <script>
                          window.open('p?update_email=failed','_self');
                      </script>
                  ";
              }else if(!filter_var($_POST['enteredEmail'], FILTER_VALIDATE_EMAIL)){
                echo "
                      <script>
                          window.open('?update_email=notValidEmail','_self');
                      </script>
                  ";
              }
            
          }catch(PDOException $exception){
              die('ERROR: ' . $exception->getMessage());

      }
    }
}

 ?>

