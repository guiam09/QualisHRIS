<?php
include ('../db/connection.php');
// include ('../includes/user_validation.php');

// July 12, 2019 UPDATED: active page color from .site-menu>.site-menu-item.active>a 
 ?>
<div class="site-menubar">
     <div class="site-menubar-body overflow:auto" >
    <div>
      <div>
          <!-- UPDATE -7/30/2019 Removed data plugin="menu"... left blank--->
        <ul class="site-menu" data-plugin="menu">
          <li class="site-menu-category">General</li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Dashboard") {?>active<?php }?>" >
            <a class="animsition-link" href="index.php">
                    <i class="site-menu-icon fa-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Dashboard</span>
                </a>
          </li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Profile") {?>active<?php }?>">
            <a class="animsition-link" href="profile.php">
                    <i class="site-menu-icon fa-user-o" aria-hidden="true"></i>
                    <span class="site-menu-title">Profile</span>
                </a>
          </li>
          <li class="site-menu-category">Employee</li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Attendance") {?>active<?php }?>">
            <a class="animsition-link" href="attendance.php">
                    <i class="site-menu-icon fa-clock-o" aria-hidden="true"></i>
                    <span class="site-menu-title">Attendance</span>
                </a>
          </li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Leave Application") {?>active<?php }?>">
            <a class="animsition-link" href="leave_application.php">
                    <i class="site-menu-icon fa-automobile" aria-hidden="true"></i>
                    <span class="site-menu-title">Leave</span>
                </a>
          </li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Timesheet Application") {?>active<?php }?>">
            <a class="animsition-link" href="timesheet_application.php">
                    <i class="site-menu-icon fa-calendar" aria-hidden="true"></i>
                    <span class="site-menu-title">Timesheet</span>
                </a>
          </li>
          <!--<li class="site-menu-category">Management</li>-->
          <?php 
        //   $stmt = getEmployee_AccessLevel($con, $_SESSION['employeeID']);
                      $searchq = $_SESSION['employeeID'];
              $query = "SELECT DISTINCT accessedModules FROM tbl_accesslevels JOIN tbl_accessLevelEmp ON tbl_accesslevels.accessLevelID = tbl_accessLevelEmp.accessLevelID JOIN tbl_accessLevelModules ON tbl_accessLevelModules.accessLevelID = tbl_accesslevels.accessLevelID 
            WHERE tbl_accessLevelEmp.employeeID = '$searchq'";
              $stmt = $con->prepare($query);
              $stmt->execute();
              $num = $stmt->rowCount();
            if($num>1){  /* July 10, 2019 UPDATE: Remove Administrator category for non admin users*/
                echo '  <li class="site-menu-category">Administrator</li>';
           while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
               
            //   if ($CURRENT_PAGE == "Leave Management") {active}
               
               if($row['accessedModules']=='leaveManagementModule'){
                   echo '          <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Leave Management"){
                       echo "active";
                   }
        //     echo ' ">
        //             <a class="animsition-link" href="leave_management.php">
        //             <i class="site-menu-icon fa-folder-open-o" aria-hidden="true"></i>
        //             <span class="site-menu-title">Leave Management</span>
        //         </a>
        //   </li>';
               }
               
               
               
                if($row['accessedModules']=='timesheetManagementModule'){
                   echo '           <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Timesheet Management"){
                       echo "active";
                   }
            echo '">
                    <a class="animsition-link" href="timesheet_management.php">
                    <i class="site-menu-icon fa-folder-open-o" aria-hidden="true"></i>
                    <span class="site-menu-title">Timesheet Management</span>
                </a>
          </li>';
               }
               
                if($row['accessedModules']=='projectMangementModule'){
                   echo '                      <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Project Management"){
                       echo "active";
                   }
            echo '">
            <a class="animsition-link" href="projectManagement.php">
                    <i class="site-menu-icon fa-code-fork" aria-hidden="true"></i>
                    <span class="site-menu-title">Project Management</span>
                </a>
          </li>';
               }
               
                              if($row['accessedModules']=='reportsModule'){
                   echo '                    <li class="site-menu-item has-sub">
            <a href="javascript:void(0)">
                    <i class="site-menu-icon fa-folder" aria-hidden="true"></i>
                    <span class="site-menu-title">Reports</span>
                            <span class="site-menu-arrow"></span>
                </a>
            <ul class="site-menu-sub">
               <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Employee List"){
                       echo "active";
                   }
            echo '">
                <a class="animsition-link" href="employee_list.php">
                  <span class="site-menu-title">Employee List</span>
                </a>
              </li>


              <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Audit Log"){
                       echo "active";
                   }
            echo '">
                <a class="animsition-link" href="audit_log.php">
                  <span class="site-menu-title">Audit Trail</span>
                </a>
              </li>
        

            </ul>

          </li>';
               }
               
               
                if($row['accessedModules']=='aedEmployeeModule'){
                   echo '                   
                   <li class="site-menu-item has-sub ';
                   if($CURRENT_PAGE == 'Add Employee' || $CURRENT_PAGE == 'Update Employees'){
                       echo 'open ';
                   }
                   
                   echo '">
            <a href="javascript:void(0)">
                    <i class="site-menu-icon fa-users" aria-hidden="true"></i>
                    <span class="site-menu-title">Manage Employees</span>
                            <span class="site-menu-arrow"></span>
                </a>
            <ul class="site-menu-sub">
               <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Add Employee"){
                       echo "active";
                   }
            echo '">
                <a class="animsition-link" href="add_employee.php">
                  <span class="site-menu-title">Add</span>
                </a>
              </li>
              <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Employee Details"){
                       echo "active";
                   }
            echo '">
                <a class="animsition-link" href="update_employee_details.php">
                  <span class="site-menu-title">Search/Update</span>
                </a>
              </li>
         

            </ul>

          </li>';
               }
               
               

               
               
               

          
          
          
               
                              if($row['accessedModules']=='configurationsModule'){
                   echo '                   <li class="site-menu-item has-sub">
            <a href="javascript:void(0)">
                    <i class="site-menu-icon fa-gears" aria-hidden="true"></i>
                    <span class="site-menu-title">Configurations</span>
                            <span class="site-menu-arrow"></span>
                </a>
            <ul class="site-menu-sub">
            
               <li class="site-menu-item has-sub">
                    <a href="javascript:void(0)">
                      <span class="site-menu-title">Configure Leaves</span>
                      <span class="site-menu-arrow"></span>
                    </a>
                    <ul class="site-menu-sub">
                      <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Config Leaves"){
                       echo "active";
                   }
            echo '">
                        <a class="animsition-link" href="configure_leaves.php">
                          <span class="site-menu-title">Add Leave Types</span>
                        </a>
                      </li>
                      <li class="site-menu-item  ';
                   
                   if($CURRENT_PAGE == "Assign Leave"){
                       echo "active";
                   }
            echo '">
                        <a class="animsition-link" href="assign_leave.php">
                          <span class="site-menu-title">Leave Credit/Balance</span>
                        </a>
                      </li>
                    </ul>
                  </li>
               <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Config Access Levels"){
                       echo "active";
                   }
            echo '">
                <a class="animsition-link" href="configure_accesslevels.php">
                  <span class="site-menu-title">Configure Access Levels</span>
                </a>
              </li>
               <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Config Designations"){
                       echo "active";
                   }
            echo '">
                <a class="animsition-link" href="configure_department.php">
                  <span class="site-menu-title">Configure Designations</span>
                </a>
              </li>

               <li class="site-menu-item ';
                   
                   if($CURRENT_PAGE == "Config Coretime"){
                       echo "active";
                   }
            // echo '">
            //     <a class="animsition-link" href="configure_coretime.php">
            //       <span class="site-menu-title">Configure Coretime</span>
            //     </a>
              

           
echo'"</li>
            </ul>
          </li>';
               }
               
               
               
               
               
               
               
           }
            }
           
          
          
          ?>





     

        </ul>      </div>
    </div>
  </div>
    
    <style>
        #minimize:hover, #minimize:focus {background-color:#263238; }
     
    </style>
    <!-- July 10, 2019 UPDATE: toggle Menu collapse relocate -->
    <div class="site-menu-item float-none">
        <li class="nav-item hidden-float" id="toggleMenubar" style="background-color">
          <a id=minimize class="nav-link" data-toggle="menubar" href="#" role="button">
            <i class="sv-slim-icon fas fa-angle-double-left float-right" data-toggle="tooltip" data-placement="right" title="Minimize">
              <span class="sr-only">Toggle menubar</span>
              <span class="hamburger-bar"></span>
            </i>
          </a>
        </li>
        <!-- <li class="nav-item hidden-sm-down" id="toggleFullscreen">
          <a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
            <span class="sr-only">Toggle fullscreen</span>
          </a>
        </li> -->
        <!--<li class="nav-item hidden-float">-->
        <!--  <a class="nav-link icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search"-->
        <!--    role="button">-->
        <!--    <span class="sr-only">Toggle Search</span>-->
        <!--  </a>-->
        <!--</li>-->
           
    </div>
      
    <!-- End of July 10, 2019 UPDATE: toggle Menu collapse relocate -->
      
</div>
