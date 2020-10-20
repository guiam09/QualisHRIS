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
$getData = getEmployeeData($con, $_SESSION['user_id']);
$user = $_SESSION['user_id'];

?>



    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title">Account Settings</h1>
      </div>
      <div class="page-content container-fluid">
        <?php
                    $message = isset($_GET['update_password']) ? $_GET['update_password'] : "";

                    if($message=='success'){
                        echo "<div class='alert alert-success'>Password Changed Successfully!</div>";
                    }

                    else if($message=='failed'){
                      echo "<div class='alert alert-danger'>Error in changing your password!</div>";
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
                <div class="col-md-12">
                   <form name="frmChange" method="post" action="process_edit_details.php" onSubmit="return validatePassword()">
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Employee Number</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $user ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Full Name</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext"  value="<?php echo $getData['firstName'].' '.$getData['lastName']; ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-sm-2 col-form-label">Access Level</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext"  value="<?php echo $getData['accessLevel'] ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Email Address</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $getData['emailAddress'] ?>">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Current Password</label>
                      <div class="input-group col-sm-2">
                         <input type="password" class="form-control" id="currentPassword" name="currentPassword" required placeholder="Current Password" onBlur="checkAvailability()">
                       <span class="input-group-addon">
                         <span class="checkbox-custom checkbox-default">
                            <input type="checkbox" onclick="myFunction()">
                           <label for="inputCheckbox"></label>
                         </span>
                       </span>
                      </div>
                    <span id="user-availability-status"></span>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">New Password</label>
                      <div class="input-group col-sm-2">
                         <input type="password" maxlength="15"name="newPassword" id="newPass" placeholder="New Password"class="form-control"/>
                       <span class="input-group-addon">
                         <span class="checkbox-custom checkbox-default">
                            <input type="checkbox" onclick="myFunction2()">
                           <label for="inputCheckbox"></label>
                         </span>
                       </span>

                      </div>
                  <span id="newPassword" class="required"></span>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Confirm Password</label>
                      <div class="input-group col-sm-2">
                        <input type="password"  maxlength="15"name="confirmPassword" placeholder="Confirm Password"id="confirmPass" class="form-control"/>
                       <span class="input-group-addon">
                         <span class="checkbox-custom checkbox-default">
                            <input type="checkbox" onclick="myFunction3()">
                           <label for="inputCheckbox"></label>
                         </span>
                       </span>

                      </div>
                  <span id="confirmPassword" class="required"></span>
                    </div>
                    <button type="submit" name="updatePassword" class="btn btn-primary">Change Password</button>
                  </form>

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


    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
 <script>
 function myFunction() {
     var x = document.getElementById("currentPassword");
     if (x.type === "password") {
         x.type = "text";
     } else {
         x.type = "password";
     }


 }

 function myFunction2() {
     var z = document.getElementById("newPass");

     if (z.type === "password") {
         z.type = "text";
     } else {
         z.type = "password";
     }
 }
 </script>

<script>
function myFunction3() {
  var newPass = document.getElementById("confirmPass");
  if (newPass.type === "password") {
      newPass.type = "text";
  } else {
      newPass.type = "password";
  }
}
</script>
<script>


function checkAvailability() {
jQuery.ajax({
url: "check_availability.php",
data:'currentPassword='+$("#currentPassword").val(),
type: "POST",
success:function(data){
$("#user-availability-status").html(data);

},
error:function (){}
});
}

</script>
<script>

   function validatePassword() {
   var currentPassword,newPassword,confirmPassword,output = true;

   currentPassword = document.frmChange.currentPassword;
   newPassword = document.frmChange.newPassword;
   confirmPassword = document.frmChange.confirmPassword;

   if(!currentPassword.value) {
   currentPassword.focus();
   document.getElementById("currentPassword").innerHTML = " <br><p class='text-danger'>Required</p>";
   output = false;
   }
   if(!newPassword.value) {
   newPassword.focus();
   document.getElementById("newPassword").innerHTML = "<br><p class='text-danger'>Required</p>";
   output = false;
   }
   else if(!confirmPassword.value) {
   confirmPassword.focus();
   document.getElementById("confirmPassword").innerHTML = " <br><p class='text-danger'>Required</p>";
   output = false;
   }
   if(newPassword.value != confirmPassword.value) {
   newPassword.value="";
   confirmPassword.value="";
   newPassword.focus();
   document.getElementById("confirmPassword").innerHTML = " <br><p class='text-danger'>Password Do not match</p>";
   output = false;
   }
   return output;
   }
   </script>


  </body>
</html>
