<?php


	switch ($_SERVER["SCRIPT_NAME"]) {
		case "/hris_v3/admin/index.php":
			$CURRENT_PAGE = "Dashboard";
			$PAGE_TITLE = "Dashboard";
			break;
		case "/hris_v3/admin/profile.php":
				$CURRENT_PAGE = "Profile";
				$PAGE_TITLE = "Profile";
				break;
		case "/hris_v3/admin/attendance.php":
				$CURRENT_PAGE = "Attendance";
				$PAGE_TITLE = "Attendance";
				break;
		case "/hris_v3/admin/leave_application.php":
				$CURRENT_PAGE = "Leave Application";
				$PAGE_TITLE = "Leave Application";
				break;
		case "/hris_v3/admin/timesheet_application.php":
				$CURRENT_PAGE = "Time Sheet Application";
				$PAGE_TITLE = "Time Sheet Application";
				break;
		case "/hris_v3/admin/leave_management.php":
				$CURRENT_PAGE = "Leave Management";
				$PAGE_TITLE = "Leave Management";
				break;
		case "/hris_v3/admin/timesheet_management.php":
				$CURRENT_PAGE = "Timesheet Management";
				$PAGE_TITLE = "Timesheet Management";
				break;
		case "/hris_v3/admin/employee_list.php":
				$CURRENT_PAGE = "Employee List";
				$PAGE_TITLE = "Employee List";

				break;
		case "/hris_v3/admin/attendance_list.php":
				$CURRENT_PAGE = "Attendance List";
				$PAGE_TITLE = "Attendance List";
				break;
		case "/hris_v3/admin/timesheet_reports.php":
				$CURRENT_PAGE = "Timesheet Reports";
				$PAGE_TITLE = "Timesheet Reports";
				break;
		case "/hris_v3/admin/leave_reports.php":
				$CURRENT_PAGE = "Leave Reports";
				$PAGE_TITLE = "Leave Reports";
				break;
		case "/hris_v3/admin/add_employee.php":
			$CURRENT_PAGE = "Add Employee";
			$PAGE_TITLE = "Add Employee";
			break;
		case "/hris_v3/admin/configure_coretime.php":
			$CURRENT_PAGE = "Configure Coretime";
			$PAGE_TITLE = "Configure Coretime";
			break;
		case "/hris_v3/admin/configure_accesslevels.php":
				$CURRENT_PAGE = "Configure Access Levels";
				$PAGE_TITLE = "Configure Access Levels";
				break;
		case "/hris_v3/admin/configure_department.php":
				$CURRENT_PAGE = "Configure Department";
				$PAGE_TITLE = "Configure Department";
				break;
		case "/hris_v3/admin/configure_positions.php":
				$CURRENT_PAGE = "Configure Positions";
				$PAGE_TITLE = "Configure Positions";
				break;
		case "/hris_v3/admin/configure_leaves.php":
			$CURRENT_PAGE = "Configure Leaves";
			$PAGE_TITLE = "Configure Leaves";
			break;
		case "/hris_v3/admin/configure_designations.php":
			$CURRENT_PAGE = "Configure Designations";
			$PAGE_TITLE = "Configure Designations";
			break;
		default:
			$CURRENT_PAGE = "HRIS";
			$PAGE_TITLE = "HRIS";
	}

 ?>
