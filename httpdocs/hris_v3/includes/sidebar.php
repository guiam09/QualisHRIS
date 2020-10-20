<?php
 ?>
<div class="site-menubar">
  <div class="site-menubar-body">
    <div>
      <div>
        <ul class="site-menu" data-plugin="menu">
          <li class="site-menu-category">General</li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Dashboard") {?>active<?php }?>">
            <a class="animsition-link" href="index.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Dashboard</span>
                </a>
          </li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Profile") {?>active<?php }?>">
            <a class="animsition-link" href="profile.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Profile</span>
                </a>
          </li>
          <li class="site-menu-category">Employee</li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Attendance") {?>active<?php }?>">
            <a class="animsition-link" href="attendance.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Attendance</span>
                </a>
          </li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Leave Application") {?>active<?php }?>">
            <a class="animsition-link" href="leave_application.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Leave Application</span>
                </a>
          </li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Time Sheet Application") {?>active<?php }?>">
            <a class="animsition-link" href="timesheet_application.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Time Sheet Application</span>
                </a>
          </li>
          <li class="site-menu-category">Management</li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Leave Management") {?>active<?php }?>">
            <a class="animsition-link" href="leave_management.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Leave Management</span>
                </a>
          </li>
          <li class="site-menu-item <?php if ($CURRENT_PAGE == "Timesheet Management") {?>active<?php }?>">
            <a class="animsition-link" href="timesheet_management.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Timesheet Management</span>
                </a>
          </li>

          <li class="site-menu-item has-sub">
            <a href="javascript:void(0)">
                    <i class="site-menu-icon md-apps" aria-hidden="true"></i>
                    <span class="site-menu-title">Reports</span>
                            <span class="site-menu-arrow"></span>
                </a>
            <ul class="site-menu-sub">
              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Employee List") {?>active<?php }?>">
                <a class="animsition-link" href="employee_list.php">
                  <span class="site-menu-title">Employee List</span>
                </a>
              </li>
              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Attendance List") {?>active<?php }?>">
                <a class="animsition-link" href="attendance_list.php">
                  <span class="site-menu-title">Attendance List</span>
                </a>
              </li>
              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Leave Reports") {?>active<?php }?>">
                <a class="animsition-link" href="leave_reports.php">
                  <span class="site-menu-title">Leave Reports</span>
                </a>
              </li>
              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Timesheet Reports") {?>active<?php }?>">
                <a class="animsition-link" href="timesheet_reports.php">
                  <span class="site-menu-title">Timesheet Reports</span>
                </a>
              </li>

            </ul>

          </li>
          <li class="site-menu-category">Administrator</li>
          <li class="site-menu-item">
            <a class="animsition-link" href="add_employee.php">
                    <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                    <span class="site-menu-title">Add Employee</span>
                </a>
          </li>
          <li class="site-menu-item has-sub">
            <a href="javascript:void(0)">
                    <i class="site-menu-icon md-apps" aria-hidden="true"></i>
                    <span class="site-menu-title">Configurations</span>
                            <span class="site-menu-arrow"></span>
                </a>
            <ul class="site-menu-sub">
              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Configure Leaves") {?>active<?php }?>">
                <a class="animsition-link" href="configure_leaves.php">
                  <span class="site-menu-title">Configure Leaves</span>
                </a>
              </li>
              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Configure Access Levels") {?>active<?php }?>">
                <a class="animsition-link" href="configure_accesslevels.php">
                  <span class="site-menu-title">Configure Access Levels</span>
                </a>
              </li>
              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Configure Designations") {?>active<?php }?>">
                <a class="animsition-link" href="configure_department.php">
                  <span class="site-menu-title">Configure Designations</span>
                </a>
              </li>

              <li class="site-menu-item<?php if ($CURRENT_PAGE == "Configure Coretime") {?>active<?php }?>">
                <a class="animsition-link" href="configure_coretime.php">
                  <span class="site-menu-title">Configure Coretime</span>
                </a>
              </li>

              <!-- <li class="site-menu-item">
                <a class="animsition-link" href="apps/calendar/calendar.html">
                  <span class="site-menu-title">Holiday Management</span>
                </a>
              </li> -->

            </ul>
          </li>

        </ul>      </div>
    </div>
  </div>
</div>
