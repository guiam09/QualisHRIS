<?php
include_once ('includes/configuration.php');

// set page title
$page_title1 = "Login";
$require_login=false;
include_once "includes/login_loginChecker.php";

// default to false
$access_denied=false;
include ('db/connection.php');


// if the login form was submitted
$error = '';
if($_POST){
  $user=htmlspecialchars(strip_tags($_POST['username']));
  $pass=htmlspecialchars(strip_tags($_POST['password']));

  // select all data
  $query = "SELECT * FROM tbl_employees WHERE username='$user'";
  $stmt = $con->prepare($query);
  $stmt->execute();
  // this is how to get number of rows returned
  $num = $stmt->rowCount();
  // check if more than 0 record found
  if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $extractPassword = $row['password'];
    $extractaccessLevel = $row['accessLevel'];

    if ($pass == $extractPassword){
    $_SESSION['logged_in'] = true;
    $_SESSION['employeeID'] = $row['employeeID'];
    $_SESSION['user_id'] = $row['employeeCode'];
    $_SESSION['access_level'] = $row['accessLevel'];
    $_SESSION['firstname'] = $row['firstName'];
    $_SESSION['lastname'] = $row['lastName'];
    //   if ($extractaccessLevel == 1){
    //     header("Location: {$home_url}admin/index.php?action=login_success");
    //     echo $_SESSION['firstname'];
    //   }elseif ($extractaccessLevel == 2) {
    //       header("Location: {$home_url}admin/index.php?action=login_success");

    //   }elseif ($extractaccessLevel == 3) {
    //       header("Location: {$home_url}admin/index.php?action=login_success");
    //   }else{

    //   }
    header("Location: {$home_url}admin/index.php?action=login_success");
    }else{

        $error = "<div class='alert alert-danger'>Invalid Password.</div>";
    }
  }
  // if no records found
  }else{
      $error = "<div class='alert alert-danger'>No user found.</div>";
  }

// login validation will be here
}

include ('includes/login_header.php');
 ?>
  <body  class="animsition  layout-full" >
    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
      <div class="page-content vertical-align-middle">
        <div class="panel">
          <div class="panel-body">
            <div class="brand">
               <img class="brand-img" height="50" width="50" src="images/qualisQ.png" alt="..."> 
              <h2 class="brand-text font-size-18">Qualis Consulting, Inc.</h2>
              <?php
              // default to false
              $access_denied=false;
              // get 'action' value in url parameter to display corresponding prompt messages
        $action=isset($_GET['action']) ? $_GET['action'] : "";

        // tell the user he is not yet logged in
        if($action =='not_yet_logged_in'){
            echo "<div class='alert alert-danger margin-top-40' role='alert'>Please login.</div>";
        }

        // tell the user to login
        else if($action=='please_login'){
            echo "<div class='alert alert-info'>
                <strong>Please login to access that page.</strong>
            </div>";
        }
        // else if ($action=='already_logged_in' && $_SESSION['access_level']=="1"){
        //    header("Location: {$home_url}admin/index.php");
        // }

        // tell the user if access denied
        if($access_denied){
            echo "<div class='alert alert-danger margin-top-40' role='alert'>
                Access Denied.<br /><br />
                Your username or password maybe incorrect
            </div>";
        }

        echo $error;
        
         ?>

            </div>
            <form method="post" id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control" name="username" />
                <label class="floating-label">Employee ID</label>
              </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="password" class="form-control" name="password" />
                <label class="floating-label">Password</label>
              </div>
              <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg mt-40">Login</button>
              <br>
            </form>
        	<a style="color:black;">Forgot your Password?<button type="button" data-target="#addCoretime" data-toggle="modal" class="btn btn-link">Reset Password</button></a>
        	  
          </div>
        </div>


      </div>
    </div>
    <!-- End Page -->
<div class="modal fade" id="addCoretime">
    <div class="modal-dialog modal-sm modal-center">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Forgot Password</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='includes/resetPassword.php'   autocomplete="off">
                          <div class="form-group">
                            <label class="col-md-12 col-form-label"><b>Enter your Employee ID</b></label>
                            <div class="col-md-12">
                              <input required name="empCode" onkeypress="return restrictCharacters(this, event, alphanumeric);" type="text" class="form-control" >
                            </div>
                          </div>
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary" name="forgotPass"> <i class="fa fa-check-square-o" ></i> Request Reset Password</button>
              </form>
            </div>
        </div>
    </div>
</div>
    <?php
    include ('includes/login_script.php');
    // include ('includes/validation.js');
     ?>
     
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<?php
if($_GET['reset'] == 'success'){
    
    
    
    
    
    ?>
    <script>
        Swal.fire(
  'Password Reset!',
  'You will receive an email containing your new password',
  'success'
)
    </script>
    <?php
}elseif($_GET['reset'] == 'failed'){
    
    
    ?>
        <script>
        Swal.fire(
  'Employee ID Not Found!',
  '',
  'error'
)
    </script>
    
    
    <?php
}

?>

  </body>
</html>
