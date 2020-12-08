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

?>
<!-- For password field -->
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!-- end plugin for password field -->

    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title">Account Settings</h1>
      </div>
      <div class="page-content container-fluid">
        <?php
                    $password_message = isset($_GET['update_password']) ? $_GET['update_password'] : "";

                    if($password_message=='success'){
                        echo "<div class='alert alert-success'>Password Changed Successfully!</div>";
                    }

                    else if($password_message=='failed'){
                      echo "<div class='alert alert-danger'>Error in changing your password!</div>";
                    }  else if($password_message=='usernameChanged'){
                         echo "<div class='alert alert-success'>Username Changed Successfully!</div>";
                    }  else if($password_message=='failedToUpdateUsername'){
                      echo "<div class='alert alert-danger'>Error in changing your username!</div>";
                    }

                    $email_message = isset($_GET['update_email']) ? $_GET['update_email'] : "";
                    
                    if($email_message=='success'){
                      echo "<div class='alert alert-success'>Email Changed Successfully!</div>";
                    }else if($email_message=='failed'){
                      echo "<div class='alert alert-success'>Error in Changing your email!</div>";
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
                    <form method="post" action="process_email_update.php">
                      <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email Address</label>
                        <input readonly type='text' name="enteredEmail" id='enteredEmail' class='col-sm-4 form-control border border-dark' value='<?php echo trim($userEmail, ' ') ?>'>"
                        <button type="button" id='editEmailBtn' onclick="change_email()" style="visibility: visible;" class="btn btn-info" data-toggle="modal" data-target="#myModal">Edit Email</button>
                        <button type="submit" id='saveEmailBtn' onclick="save_email()" style="visibility: hidden;" style="margin-left: -100" class="btn btn-info">Save</button>
                        <button type="button" id='cancelEmailBtn' onclick="cancel_email()" style="visibility: hidden;" class="btn btn-info">Cancel</button>
                      </div>
                    </form>
                   <form name="frmChange" method="post" action="process_edit_details.php" onSubmit="return validatePassword()">
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
                              <input type="password" class="form-control" id="currentPassword" name="currentPassword" required placeholder="Current Password" on Blur="checkAvailability() /">
                              <span class="input-group-btn">
                                <button class="btn btn-default revealCurrentPassword" type="button"> <i class="fa fa-fw fa-eye"></i></button>
                              </span>          
                            </div>
                        </div>
                        <span id="user-availability-status"></span>
                    </div>
                    
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">New Password</label>
                      <div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial">
                        <div class="input-group">
                         <input type="password" maxlength="15" id="newPassword" name="newPassword" placeholder="New Password"class="form-control"/>
                        <span class="input-group-btn">
                                <button class="btn btn-default revealNewPassword" type="button"> <i class="fa fa-fw fa-eye"></i></button>
                        </span> 
                        </div>
                    </div>
                    <span id="newPassword" class="required"></span>
                    </div>
                    
                    <div class="form-group row">
                      <label for="staticEmail" class="col-sm-2 col-form-label">Confirm Password</label>
                      <div class="form-group col-sm-4 form-material floating" data-plugin="formMaterial">
                        <div class="input-group">
                         <input type="password" maxlength="15" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm Password"class="form-control"/>
                        <span class="input-group-btn">
                                <button class="btn btn-default revealConfirmNewPassword" type="button"> <i class="fa fa-fw fa-eye"></i></button>
                        </span> 
                        </div>
                    </div>
                    <span id="newPassword" class="required"></span>
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
                    <button type="button" onclick="change_password_confirm()" class="btn btn-info">Change Password</button>
                  </form>

                  <!-- The Modal -->
                    <!-- <div id="myModal" class="modal">

                      <div class="modal-content">
                        <div class="modal-header">
                          <span class="close">&times;</span>
                          <h2>Modal Header</h2>
                        </div>
                        <div class="modal-body">
                          <p>Some text in the Modal Body</p>
                          <p>Some other text...</p>
                        </div>
                        <div class="modal-footer">
                          <h3>Modal Footer</h3>
                        </div>
                      </div>

                    </div> -->
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                      Launch demo modal
                    </button>
                   
                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          ...
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div> -->
                    <!-- --- -->
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
 <script>
//  July31,2019 UPDATE: reveal script
 $(".revealCurrentPassword").on('click',function() {
    var $pwd = $("#currentPassword");
    if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
    } else {
        $pwd.attr('type', 'password');
    }
});

$(".revealNewPassword").on('click',function() {
    var $pwd = $("#newPassword");
    if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
    } else {
        $pwd.attr('type', 'password');
    }
});

$(".revealConfirmNewPassword").on('click',function() {
    var $pwd = $("#confirmNewPassword");
    if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
    } else {
        $pwd.attr('type', 'password');
    }
});
// end July 31,2019 UPDATE
 
 
 function myFunction() {
     var x = document.getElementById("currentPassword");
     if (x.type === "password") {
         x.type = "text";
     } else {
         x.type = "password";
     }


 }

 function myFunction2() {
     var z = document.getElementById("newPassword");

     if (z.type === "password") {
         z.type = "text";
     } else {
         z.type = "password";
     }
 }
 </script>

<script>
function myFunction3() {
  var newPass = document.getElementById("confirmNewPassword");
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

function checkAvailability2() {
jQuery.ajax({
url: "check_availability.php",
data:'currentPassword='+$("#currentPassword2").val(),
type: "POST",
success:function(data){
$("#user-availability-status2").html(data);

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
   document.getElementById("confirmNewPassword").innerHTML = " <br><p class='text-danger'>Required</p>";
   output = false;
   }
   if(newPassword.value != confirmPassword.value) {
   newPassword.value="";
   confirmPassword.value="";
   newPassword.focus();
   document.getElementById("confirmNewPassword").innerHTML = " <br><p class='text-danger'>Password Do not match</p>";
   output = false;
   }
   return output;
   }

    function change_password_confirm()   {
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
                $('#real_submit_button').click();
            }
        });
    }

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

    // var modal = document.getElementById("myModal");

    // var btn = document.getElementById("editEmailBtn");

    // var span = document.getElementsByClassName("close")[0];

    // // When the user clicks the button, open the modal 
    // btn.onclick = function() {
    //   modal.style.display = "block";
    // }

    // // When the user clicks on <span> (x), close the modal
    // span.onclick = function() {
    //   modal.style.display = "none";
    // }

    // When the user clicks anywhere outside of the modal, close it
   </script>


  </body>
</html>
