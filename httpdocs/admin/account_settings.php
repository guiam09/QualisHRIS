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
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
include ('../includes/user_validation.php');
$getData = getUserInfo($con, $_SESSION['user_id']);
$user = $_SESSION['user_id'];
$fullname = $getData['firstName'] .' '. $getData['lastName'];
$userEmail = $getData['emailAddress'];
$adminAccess = []; 
?>
<!-- For password field -->
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<style>
  /* Edit email buttons css */
.edit-button{
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
<!-- end plugin for password field -->

    <!-- Page -->
    <div class="page" ng-app="hris" ng-controller="AccountSettingsController">
      <div class="page-header">
          <h1 class="page-title">Account Settings</h1>
      </div>
      <div class="page-content container-fluid">
        <?php
                    $password_message = isset($_GET['update_password']) ? $_GET['update_password'] : "";

                    if($password_message=='success'){
                      echo "<div class='alert alert-success'>Password Changed Successfully!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span></button></div>";
                    }else if($password_message=='failed'){
                      echo "<div class='alert alert-danger'>Error in changing your password!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span></button></div>";
                    }else if($password_message=='usernameChanged'){
                      echo "<div class='alert alert-success'>Username Changed Successfully!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span></button></div>";
                    }else if($password_message=='failedToUpdateUsername'){
                      echo "<div class='alert alert-danger'>Error in changing your username!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span></button></div>";
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
          <!-- Panel Floating Labels -->
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title text-info">Account Settings</h3>
            </div>
            <div class="panel-body container-fluid">
              <div class="row row-lg">
                
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
                <div class="col-md-12">
                    <div <?php 
                    if(in_array("aedEmployeeModule", $adminAccess)){ echo "style='display: block;'"; }
                    else echo "style='display: none;'"; ?>>
                      <form method="POST" action="process_email_update.php" >
                        <div class="form-group row">
                          <label for="staticEmail" class="col-sm-2 col-form-label">Email Address</label>
                          <input readonly type='text' name="enteredEmail" id='enteredEmail' class='col-sm-4 form-control border border-dark' value='<?php echo trim($userEmail, ' ') ?>'>"
                          <button type="button" id='editEmailBtn' ng-click="change_email()" class="btn btn-info edit-button">Edit Email</button>
                          <button type="submit" id='saveEmailBtn' ng-click="save_email()" class="btn btn-info save-button">Save</button>
                          <button type="button" id='cancelEmailBtn' ng-click="cancel_email()" class="btn btn-info cancel-button">Cancel</button>
                        </div>
                      </form>
                      </div>
                    <div class="card-text" <?php 
                    if(in_array("aedEmployeeModule", $adminAccess)){ echo "style='display: none;'"; }
                    else echo "style='display: block;'"; ?>>
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email Address</label>
                        <a class="blue-grey-400 font-size-16"><i><?php echo $getData['emailAddress'] ?></i></a>
                      </div>
                    </div>
             
                  <form name="frmChange" method="post" action="process_edit_details.php" ng-submit="validatePassword($event)">
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Employee Number</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" style="outline:none" value="<?php echo $user ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Full Name</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" style="outline:none" value="<?php echo $fullname ?>">
                      </div>
                    </div>
                    <!-- July 12, 2019 UPDATE: Remove Access Level -->
           <!--         <div class="form-group row">-->
           <!--           <label for="inputPassword" class="col-sm-2 col-form-label">Access Level</label>-->
           <!--           <div class="col-sm-10">-->
                             <?php 
        //                     $stmt = getEmployee_AccessLevel($con, $getData['employeeID']);
        //   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //       $accessLevel = $row['accessLevelName'];   
        //   }
                          
                          ?>
           <!--             <input type="text" readonly class="form-control-plaintext"   style="outline:none" value="<?php echo $accessLevel ?>">-->
           <!--           </div>-->
           <!--         </div>-->
                    <!-- End July 12, 2019 UPDATE: Remove Access Level -->

                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Current Password</label>
                      
                      <!-- July 12, 2019 UPDATE: Eye password mask -->
                      
                      <!--<div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial">-->
                      <!--   <input type="password" class="form-control" id="currentPassword" name="currentPassword" required placeholder="Current Password" onBlur="checkAvailability()" />-->

                        <!-- July 31,2019 UPDATE: New Password eye icon mask -->
           
                          <div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial">
                            <div class="input-group">
                              <input type="password" class="form-control" id="currentPassword" name="currentPassword" required placeholder="Current Password" on Blur="checkAvailability()" ng-model="currentPassword"/>
                              <span class="input-group-btn">
                                <button class="btn btn-default revealCurrentPassword" type="button"> <i class="fa fa-fw fa-eye"></i></button>
                              </span>
                            </div>
                            <p class='text-danger' style='margin-bottom: 0px;' ng-if="!currentPassword">Required</p>
                        </div>
                        <span id="user-availability-status"></span>
                    </div>
                    
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">New Password</label>
                      <div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial">
                        <div class="input-group">
                          <input type="password" maxlength="15" id="newPassword" name="newPassword" placeholder="New Password"class="form-control" ng-model="newPassword"/>
                          <span class="input-group-btn">
                            <button class="btn btn-default revealNewPassword" type="button"> <i class="fa fa-fw fa-eye"></i></button>
                          </span>
                        </div>
                        <p class='text-danger' style='margin-bottom: 0px;' ng-if="!newPassword">Required</p>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Confirm Password</label>
                      <div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial">
                        <div class="input-group">
                          <input type="password" maxlength="15" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm Password"class="form-control" ng-model="confirmNewPassword" required/>
                          <span class="input-group-btn">
                            <button class="btn btn-default revealConfirmNewPassword" type="button"> <i class="fa fa-fw fa-eye"></i></button>
                          </span> 
                        </div>
                        <p class='text-danger' style='margin-bottom: 0px;' ng-if="!confirmNewPassword">Required</p>
                        <p class='text-danger' style='margin-bottom: 0px;' ng-if="newPassword !== confirmNewPassword">Password do not matched</p>
                      </div>
                    </div>
                    
                    <!-- End new password eye mask -->
                    
                        <!---->
                         <!--ORIGINAL -->
                         
                       <!--<span class="input-group-addon">-->
                       <!--  <span class="checkbox-custom checkbox-default">-->
                       <!--     <input type="checkbox" onclick="myFunction()">-->
                       <!--    <label for="inputCheckbox"></label>-->
                       <!--  </span>-->
                       <!--</span>-->
                       
                        <!--END ORIGINAL -->
                      
                    <!--<div class="form-group row">-->
                    <!--  <label for="staticEmail" class="col-sm-2 col-form-label">New Password</label>-->
                    <!--  <div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial">-->
                    <!--     <input type="password" maxlength="15"name="newPassword" id="newPass" placeholder="New Password"class="form-control"/>-->
                       <!--<span class="input-group-addon">-->
                       <!--  <span class="checkbox-custom checkbox-default">-->
                       <!--     <input type="checkbox" onclick="myFunction2()">-->
                       <!--    <label for="inputCheckbox"></label>-->
                       <!--  </span>-->
                       <!--</span>-->

                  <!--    </div>-->
                  <!--<span id="newPassword" class="required"></span>-->
                  <!--  </div>-->
                  <!--  <div class="form-group row">-->
                  <!--    <label for="staticEmail" class="col-sm-2 col-form-label">Confirm Password</label>-->
                  <!--    <div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial" >-->
                  <!--      <input type="password"  maxlength="15"name="confirmPassword" placeholder="Confirm Password"id="confirmPass" class="form-control"/>-->
                       <!--<span class="input-group-addon">-->
                       <!--  <span class="checkbox-custom checkbox-default">-->
                       <!--     <input type="checkbox" onclick="myFunction3()">-->
                       <!--    <label for="inputCheckbox"></label>-->
                       <!--  </span>-->
                       <!--</span>-->

                  <!--    </div>-->
                  <!--<span id="confirmPassword" class="required"></span>-->
                  <!--  </div>-->
                    <button type="submit" id="real_submit_button" style="display:none" name="updatePassword" class="btn btn-info">Change Password</button>
                    <button type="button" ng-click="change_password_confirm()" class="btn btn-info">Change Password</button>
                  </form>
                  </div>
                        </div>

                        </div>
                      </div>
                      <!-- End Panel Floating Labels -->
                    </div>
        </div>
             <div class="row">
       
        <!-- July 12, 2019 UPDATE: Remove change username -->
        <!--<div class="col-md-12">-->
          <!-- Panel Floating Labels -->
        <!--  <div class="panel">-->
        <!--    <div class="panel-heading">-->
        <!--      <h3 class="panel-title text-info">Change Username</h3>-->
        <!--    </div>-->
        <!--    <div class="panel-body container-fluid">-->
        <!--      <div class="row row-lg">-->
        <!--        <div class="col-md-12">-->
        <!--           <form method="post" action="process_edit_details.php" autocomplete="off">-->
        <!--            <div class="form-group row">-->
        <!--              <label for="staticEmail" class="col-sm-2 col-form-label">Username</label>-->
        <!--              <div class="col-sm-10">-->
        <!--                <input type="text" readonly class="form-control-plaintext"  style="outline:none" value="
        <?php  //echo $getData['username'] ?>
        ">-->
        <!--              </div>-->
        <!--            </div>-->
        <!--            <div class="form-group row">-->
        <!--              <label for="staticEmail" class="col-sm-2 col-form-label">New Username</label>-->
        <!--              <div class="col-sm-4">-->
        <!--                <input type="text"  class="form-control" maxlength="50" name="username">-->
        <!--              </div>-->
        <!--            </div>-->
        <!--            <div class="form-group row">-->
        <!--              <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>-->
        <!--              <div class="col-sm-4">-->
        <!--                <input type="password" maxlength="50" class="form-control" onBlur="checkAvailability2()" id="currentPassword2" name="currentPassword2">-->
        <!--              </div>-->
        <!--               <span id="user-availability-status2"></span>-->
        <!--            </div>-->

        <!--            <button type="submit" name="editUsername" class="btn btn-info">Change Username</button>-->
        <!--          </form>-->

        <!--            </div>-->
        <!--                  </div>-->

        <!--                </div>-->
        <!--              </div>-->
                      <!-- End Panel Floating Labels -->
        <!--            </div>-->
        <!-- End July 12, 2019 UPDATE: Remove change username -->
        </div>
      </div>
    </div>
    <!-- End Page -->


    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>

  <script type="text/javascript" src="../node_modules/angular/angular.min.js"></script>
  <script type="text/javascript" src="../node_modules/angularjs-datatables/src/angular-datatables.js"></script>
  <script type="text/javascript" src="AccountSettings/accountSettingsCtrl.js"></script>
  </body>
</html>
