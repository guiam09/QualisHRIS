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
?>


    <!-- Page -->
    <div class="page">
      <div class="page-content">
        <?php
        echo "<div class='col-md-12'>";
        echo   $_SESSION['logged_in'];
             // get parameter values, and to prevent undefined index notice
             $action = isset($_GET['action']) ? $_GET['action'] : "";
             // tell the user he's already logged in
             if($action=='already_logged_in'){
                 echo "<div class='alert alert-info'>";
                     echo "<strong>You</strong> are already logged in.";
                 echo "</div>";
             }

             else if($action=='logged_in_as_admin'){
                 echo "<div class='alert alert-info'>";
                     echo "<strong>You</strong> are logged in as admin.";
                 echo "</div>";
             }else if($action=='login_success'){
                   echo "<div class='alert alert-info'>";
                       echo "<strong>Hi " . $_SESSION['firstname'] . ", Welcome back!</strong>";
                   echo "</div>";

             }

             // echo "<div class='alert alert-info'>";
             //     echo "Contents of your admin section will be here.";
             // echo "</div>";

         echo "</div>";

     ?>
      </div>
    </div>
    <!-- End Page -->


    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
  </body>
</html>
