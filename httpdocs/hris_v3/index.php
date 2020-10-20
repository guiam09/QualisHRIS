<?php
include_once ('includes/configuration.php');

// set page title
$page_title1 = "Login";
$require_login=false;
include_once "includes/login_loginChecker.php";

// default to false
$access_denied=false;
include ('db/connection.php');
include ('includes/login_header.php');

 ?>
  <body  class="animsition page-login-v3 layout-full">
    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
      <div class="page-content vertical-align-middle">
        <div class="panel">
          <div class="panel-body">
            <div class="brand">
              <!-- <img class="brand-img" src="assets//images/logo-colored.png" alt="..."> -->
              <h2 class="brand-text font-size-18">CoreSys Solutions Inc.</h2>
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
        // if the login form was submitted
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
            $_SESSION['user_id'] = $row['employeeCode'];
            $_SESSION['access_level'] = $row['accessLevel'];
            $_SESSION['firstname'] = $row['firstName'];
            $_SESSION['lastname'] = $row['lastName'];
              if ($extractaccessLevel == 1){
                header("Location: {$home_url}admin/index.php?action=login_success");
                echo $_SESSION['firstname'];
              }elseif ($extractaccessLevel == 2) {
                  header("Location: {$home_url}employee/index.php?action=login_success");

              }elseif ($extractaccessLevel == 3) {
                  header("Location: {$home_url}sup/home.php?action=login_success");
              }else{

              }
            }else{

                echo "<div class='alert alert-danger'>Invalid Password.</div>";
            }
          }
          // if no records found
          }else{

              echo "<div class='alert alert-danger'>No user found.</div>";
          }

        // login validation will be here
        }
         ?>

            </div>
            <form method="post" id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control" name="username" />
                <label class="floating-label">Username</label>
              </div>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="password" class="form-control" name="password" />
                <label class="floating-label">Password</label>
              </div>
              <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg mt-40">Login</button>
            </form>

          </div>
        </div>


      </div>
    </div>
    <!-- End Page -->

    <?php
    include ('includes/login_script.php');
    include ('includes/validation.js');
     ?>

  </body>
</html>
