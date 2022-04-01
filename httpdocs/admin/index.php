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
$CURRENT_PAGE="Dashboard";
include ('../includes/sidebar.php');
?>


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title">Dashboard</h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                      <!-- Panel Floating Labels -->
                      <div class="panel">
                        <div class="panel-heading">
                          <h3 class="panel-title text-danger"><?php
                          echo "<div class='col-md-12'>";
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
                                         echo "<strong>Hi " . $_SESSION['firstname'] . "! Welcome back!</strong>";
                                     echo "</div>";

                               }

                               // echo "<div class='alert alert-info'>";
                               //     echo "Contents of your admin section will be here.";
                               // echo "</div>";

                           echo "</div>";

                       ?></h3>
                        </div>
                        <div class="panel-body container-fluid">
            <?php
             $query3 = "SELECT employeeID FROM tbl_employees";
              $stmt3 = $con->prepare($query3);
              $stmt3->execute();
              $num3 = $stmt3->rowCount();


              $query2 = "SELECT project_ID FROM tbl_project";
              $stmt2 = $con->prepare($query2);
              $stmt2->execute();
              $num2 = $stmt2->rowCount();

              $session = $_SESSION['user_id'];
              $sessionEmp = $_SESSION['employeeID'];
              $query11 = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leavegroup ON tbl_leavedetails.leaveGroup_ID = tbl_leavegroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID = tbl_employees.employeeID WHERE tbl_leavegroup.leaveGroup_status = 'Pending' AND tbl_leavegroup.leaveGroup_approver ='$sessionEmp'";
              $stmt11 = $con->prepare($query11);
              $stmt11->execute();
              $num11 = $stmt11->rowCount();
            ?>
            
                                 <div class="card-group">
                                         <?php
               $searchq = $_SESSION['employeeID'];
              $query = "SELECT DISTINCT accessedModules FROM tbl_accesslevels JOIN tbl_accessLevelEmp ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID JOIN tbl_accessLevelModules ON tbl_accessLevelModules.accessLevelID = tbl_accesslevels.accessLevelID 
            WHERE tbl_accessLevelEmp.employeeID = '$searchq'";
              $stmt = $con->prepare($query);
              $stmt->execute();
              $num = $stmt->rowCount();
            // if($num>0){
            //     echo '            <div class="card card-block p-0">
            //   <div class="counter counter-lg counter-inverse bg-blue-600 vertical-align h-150">
            //     <div class="vertical-align-middle">
            //       <div class="counter-icon mb-5"><i class="icon fa-user" aria-hidden="true"></i></div>
            //       <span class="counter-number">There Are '.$num3.' User/s.</span>
            //     </div>
            //   </div>
            // </div>';
           while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
               
                if($row['accessedModules']=='leaveManagementModule'){
                   echo '      <div class="card card-block p-0">
                   <a href="leave_application.php">
              <div class="counter counter-lg counter-inverse bg-purple-600 vertical-align h-150">
             
                <div class="vertical-align-middle">
               
                  <div class="counter-icon mb-5"><i class="icon fa-car" aria-hidden="true"></i></div>
                  <span class="counter-number">You Have '.$num11.' Pending Leave Applications For Your Approval</span>
                 
                </div>
              
              </div>
              </a>
            </div>';
               }
               
           
            }
    
    ?>

            <!-- <div class="card card-block p-0">
              <div class="counter counter-lg counter-inverse bg-red-600 vertical-align h-150">
                <div class="vertical-align-middle">
                  <div class="counter-icon mb-5"><i class="icon fa-code" aria-hidden="true"></i></div>
                  <span class="counter-number">You Currently Have <?php echo $num2 ?> Projects</span>
                </div>
              </div>
            </div> -->

     
          </div>
          <!-- End Card -->
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
  </body>
</html>
