<?php
include_once ('../includes/configuration.php');
include ('../db/connection.php');

// include login checker
$page_title     = "Admin";
$access_type    = "Admin";

// include login checker
$require_login = true;

include_once "../includes/loginChecker.php";

include ('../includes/header.php');
include ('../includes/navbar.php');
$CURRENT_PAGE='Timesheet Management';
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
include ('get_week_range.php');
?> 
<!-- Page -->
<style>
    thead input {
        width: 80%;
    }
    
    

    .modal {
        z-index: 1950;
    }

    .select2-container {
        z-index: 9999;
    }

    .select2-container--open {
        z-index: 99999
    }

    .swal2-container {
        z-index: 999999;
    }

    
</style>
<div class="page">
    <div class="page-header">
          <!--<h1 class="page-title"><i>Leave Application</i></h1>-->
    </div>
    <div class="page-content container-fluid">
        <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Time Sheets</h1>
                    </div>
                </div> 
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->
        <div class="row">
            <div class="col-12">
                <!-- Custom Tabs -->
                <div class="card">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-pills p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Timesheet Approval</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Processed Timesheets</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row" style="margin-top: 50px">
                                    <div class="col-sm-4">
                                        <div class="col-md-12">
                                        <label class="form-control-label">Week Ending</label><br />
                                        <input type="text" class="form-control col-md-12" id="datepicker">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-md-12">
                                            <label class="form-control-label">Employee Name</label><br />
                                            <select class="form-control col-md-12" data-plugin="select2"  id="employee_name" name="employee_name" data-placeholder="Select Employee">
                                            <?php
                                            include('searchEmployeeListTimesheet.php');
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-md-6">
                                        <label class="form-control-label">Status</label><br />
                                        <select class="form-control col-md-12" data-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Select status" tab-index="-1" width="auto" id="status_filter">
                                            <option value="">All</option>
                                            <option>Pending</option>
                                            <option>Approved</option>
                                            <option>Amended Pending</option>
                                            <option>Amended Approved</option>
                                            <option>Declined</option>
                                            <option>Amended Declined</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            if (!empty($_POST['process_action']) && $_POST['process_action'] == "approve") {

                                $approved   = !empty($_POST['submitted']) ? $_POST['submitted'] : [];
                                $ctr        = 0;
                                foreach ($approved as $key=>$id) {
                                    $status = 'Approved';
                                    if (isset($_POST['weekly_status'][$key]) && $_POST['weekly_status'][$key] == "Amended Pending") {
                                        $status = 'Amended Approved';
                                    }
                                    $query = "UPDATE tbl_weeklyutilization
                                    SET
                                        weekly_dateProcessed=:dateProcessed,
                                        weekly_approval=:approval,
                                        weekly_status='".$status."'
                                    WHERE
                                        employeeCode=:employeeCode AND
                                        weekly_startDate=:weekly_start_date AND
                                        weekly_endDate=:weekly_end_date";
                                    $stmt = $con->prepare($query);

                                    //post values
                                    $dateProcessed=date("Y-m-d");
                    
                                    $approval="Approved";

                                    //bind
                                    $stmt->bindParam(':dateProcessed',$dateProcessed);
                                    $stmt->bindParam(':approval',$approval);
                                    $stmt->bindParam(':employeeCode', $_POST['employee_code'][$key]);
                                    $stmt->bindParam(':weekly_start_date', $_POST['weekly_start_date'][$key]);
                                    $stmt->bindParam(':weekly_end_date', $_POST['weekly_end_date'][$key]);

                                    if($stmt->execute()){
                                        $ctr++;
                                    }

                                    $deleteQuery = 'Delete from tbl_weeklyutilization_history where weekly_startDate = :weekStart and employeeCode = "'.htmlspecialchars(strip_tags($_POST['employee_code'][$key])).'"';
                                    $stmt = $con->prepare($deleteQuery);
                                    $stmt->bindParam(':weekStart', $_POST['weekly_start_date'][$key]);

                                    $stmt->execute();

                                    $historyInsertSql = 'INSERT INTO tbl_weeklyutilization_history
                                                            SELECT NULL,
                                                                employeeCode,
                                                                weekly_startDate,
                                                                weekly_endDate,
                                                                project_ID,
                                                                work_ID,
                                                                activityOthers_ID,
                                                                activityAdmin_ID,
                                                                weekly_description,
                                                                weekly_sunday,
                                                                weekly_monday,
                                                                weekly_tuesday,
                                                                weekly_wednesday,
                                                                weekly_thursday,
                                                                weekly_friday,
                                                                weekly_saturday,
                                                                weekly_total,
                                                                weekly_overallTotal,
                                                                location_ID,
                                                                weekly_timeSubmitted,
                                                                weekly_dateProcessed,
                                                                weekly_status,
                                                                weekly_approval,
                                                                weekly_dateSubmitted,
                                                                weekly_taskCode,
                                                                weekly_saturdayComment,
                                                                weekly_sundayComment,
                                                                weekly_mondayComment,
                                                                weekly_tuesdayComment,
                                                                weekly_wednesdayComment,
                                                                weekly_thursdayComment,
                                                                weekly_fridayComment,
                                                                weekly_timesheetCode,
                                                                is_latest
                                                            FROM tbl_weeklyutilization WHERE
                                                                weekly_startDate = "'.$_POST['weekly_start_date'][$key].'" AND
                                                                weekly_endDate = "'.$_POST['weekly_end_date'][$key].'" AND
                                                                employeeCode = "'.htmlspecialchars(strip_tags($_POST['employee_code'][$key])).'"';

                                    $con->prepare($historyInsertSql)->execute();
                                }
                            
                                if ($ctr==0) {
                                    echo "<div class='alert alert-danger'>Approval Failed. No Timesheet Selected</div>";
                                } else {
                                    echo " <div class='alert alert-success'> ". $ctr  ." timesheet(s) approved</div>";
                                }
                            } elseif (!empty($_POST['process_action']) && $_POST['process_action'] == "reject") {
                                $declined   = $_POST['submitted'];
                                $ctr        = 0;
                                foreach ($declined as $key=>$id) {
                                    $status = 'Declined';
                                    if (isset($_POST['weekly_status'][$key]) && $_POST['weekly_status'][$key] == "Amended Pending") {
                                        $status = 'Amended Declined';
                                    }

                                    $weeklyNumber = $id;
                                    $query = "UPDATE tbl_weeklyutilization
                                                SET
                                                    weekly_dateProcessed=:dateProcessed,
                                                    weekly_approval=:approval,
                                                    weekly_status='".$status."'
                                                WHERE
                                                    employeeCode=:employeeCode AND
                                                    weekly_startDate=:weekly_start_date AND
                                                    weekly_endDate=:weekly_end_date";
                                    $stmt           = $con->prepare($query);
                                    $dateProcessed  = date("Y-m-d");
                                    $approval       = "Declined";
                        
                                    $stmt->bindParam(':dateProcessed',$dateProcessed);
                                    $stmt->bindParam(':approval',$approval);
                                    $stmt->bindParam(':employeeCode', $_POST['employee_code'][$key]);
                                    $stmt->bindParam(':weekly_start_date', $_POST['weekly_start_date'][$key]);
                                    $stmt->bindParam(':weekly_end_date', $_POST['weekly_end_date'][$key]);

                                    if ($stmt->execute()) {
                                        $ctr++;
                                    }

                                    $deleteQuery = 'Delete from tbl_weeklyutilization_history where weekly_startDate = :weekStart and employeeCode = "'.htmlspecialchars(strip_tags($_POST['employee_code'][$key])).'"';
                                    $stmt = $con->prepare($deleteQuery);
                                    $stmt->bindParam(':weekStart', $_POST['weekly_start_date'][$key]);

                                    $stmt->execute();

                                    $historyInsertSql = 'INSERT INTO tbl_weeklyutilization_history
                                                            SELECT NULL,
                                                                employeeCode,
                                                                weekly_startDate,
                                                                weekly_endDate,
                                                                project_ID,
                                                                work_ID,
                                                                activityOthers_ID,
                                                                activityAdmin_ID,
                                                                weekly_description,
                                                                weekly_sunday,
                                                                weekly_monday,
                                                                weekly_tuesday,
                                                                weekly_wednesday,
                                                                weekly_thursday,
                                                                weekly_friday,
                                                                weekly_saturday,
                                                                weekly_total,
                                                                weekly_overallTotal,
                                                                location_ID,
                                                                weekly_timeSubmitted,
                                                                weekly_dateProcessed,
                                                                weekly_status,
                                                                weekly_approval,
                                                                weekly_dateSubmitted,
                                                                weekly_taskCode,
                                                                weekly_saturdayComment,
                                                                weekly_sundayComment,
                                                                weekly_mondayComment,
                                                                weekly_tuesdayComment,
                                                                weekly_wednesdayComment,
                                                                weekly_thursdayComment,
                                                                weekly_fridayComment,
                                                                weekly_timesheetCode,
                                                                is_latest
                                                            FROM tbl_weeklyutilization WHERE
                                                                weekly_startDate = "'.$_POST['weekly_start_date'][$key].'" AND
                                                                weekly_endDate = "'.$_POST['weekly_end_date'][$key].'" AND
                                                                employeeCode = "'.htmlspecialchars(strip_tags($_POST['employee_code'][$key])).'"';

                                    $con->prepare($historyInsertSql)->execute();
                                }
                                if($ctr==0){
                                    echo "<div class='alert alert-danger'>Approval Failed. No Timesheet Selected</div>";
                                } else {
                                    echo " <div class='alert alert-success'> ". $ctr  ." timesheet(s) declined</div>";
                                }
                            }
                            ?>
                                <!--MAIN CONTENT-->
                                <div>
                                <?php
                                $query = "SELECT *,SUM(weekly_sunday) + SUM(weekly_monday) + SUM(weekly_tuesday) + SUM(weekly_wednesday) + SUM(weekly_thursday) + SUM(weekly_friday) + SUM(weekly_saturday) AS `total_hours` FROM tbl_weeklyutilization INNER JOIN tbl_employees ON tbl_employees.employeeCode = tbl_weeklyutilization.employeeCode
                                    WHERE weekly_approval='Pending' and weekly_status != 'Not Submitted' and tbl_weeklyutilization.employeeCode != ".htmlspecialchars(strip_tags($_SESSION['user_id']))."  GROUP BY weekly_startDate, tbl_weeklyutilization.employeeCode ORDER BY weekly_endDate DESC";

                                $query = "SELECT *,SUM(weekly_sunday) + SUM(weekly_monday) + SUM(weekly_tuesday) + SUM(weekly_wednesday) + SUM(weekly_thursday) + SUM(weekly_friday) + SUM(weekly_saturday) AS `total_hours` FROM tbl_weeklyutilization INNER JOIN tbl_employees ON tbl_employees.employeeCode = tbl_weeklyutilization.employeeCode
                                    WHERE weekly_approval='Pending' and weekly_status != 'Not Submitted' GROUP BY weekly_startDate, tbl_weeklyutilization.employeeCode ORDER BY weekly_endDate DESC";

                                $stmt = $con->prepare($query);
                                $stmt->execute();
                                $num = $stmt->rowCount();

                                if ($num > 0) {
                                ?>
                                    <form class="form-horizontal" id="form-process-timesheet" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <table id='example1' class="table table-hover"><br/>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Week Ending</th>
                                                    <th>Employee Name</th>
                                                    <th>Hours</th>
                                                    <th>Date Submitted</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $xx = 0;
                                            while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $employeeNumber = $row['employeeCode'];
                                                $dateSub = $row['weekly_dateSubmitted'];
                                                $timeSub = $row['weekly_timeSubmitted'];
                                                $dateEnd = $row['weekly_endDate'];
                                                $timesheetCode = $row['weekly_timesheetCode'];
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input class="pending_checkbox" type='checkbox' name='submitted[<?php echo $xx ?>]'  value='<?php echo $row["weekly_ID"]; ?>' <?php if($row['weekly_approval'] == "Approved"){echo "DISABLED";} ?> >
                                                        <input type="hidden" name="employee_code[<?php echo $xx ?>]" value="<?php echo $row['employeeCode'] ?>">
                                                        <input type="hidden" name="weekly_start_date[<?php echo $xx ?>]" value="<?php echo $row['weekly_startDate'] ?>">
                                                        <input type="hidden" name="weekly_end_date[<?php echo $xx ?>]" value="<?php echo $row['weekly_endDate'] ?>">
                                                        <input type="hidden" name="weekly_status[<?php echo $xx ?>]" value="<?php echo $row['weekly_status'] ?>">
                                                    </td>
                                                    <td>
                                                        <a href="" data-toggle='modal' data-target='#viewTimesheetDetails-<?php echo $row['employeeCode'] ?>-<?php echo $row['weekly_startDate'] ?>-normal' class="navbar-link"><input type='text' name='timesheet_ID' hidden value=' <?php echo $row['weekly_ID']; ?>'><?php echo $row['weekly_endDate'];?></a>
                                                    </td>
                                                    <td><?php echo $row['lastName'] . ", " . $row['firstName'] . " " . $row['middleName'] ;?></td>
                                                    <td><?php echo $row['total_hours'];?></td>
                                                    <td><?php echo $row['weekly_dateSubmitted'];?></td>
                                                    <td><?php echo $row['weekly_status'];?></td>
                                                </tr>
                                            <?php
                                            $xx++;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <!--APPROVEDECLINE-->
                                        
                                            <input type="hidden" name="process_action" id="process_action">
                                            <button type="button" class="btn btn-default" id="decline-btn" onclick="rejectConfirm()">Decline</button>
                                            <button type="button" class="btn btn-default" onclick="approveConfirm()">Approve</button>
                                        
                                    </form>
                                <?php
                                } else {
                                    echo "No Timesheets submitted.";
                                }
                                ?>
                                </div>
                            </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="row" style="margin-top: 50px">
                                    <div class="col-sm-4">
                                        <div class="col-md-12">
                                        <label class="form-control-label">Week Ending</label><br />
                                        <input type="text" class="form-control col-md-12" id="datepicker2">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-md-12">
                                            <label class="form-control-label">Employee Name</label><br />
                                            <select class="form-control col-md-12" data-plugin="select2"  id="employee_name2" name="employee_name2" data-placeholder="Select Employee">
                                            <?php
                                            include('searchEmployeeListTimesheet.php');
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="col-md-6">
                                        <label class="form-control-label">Status</label><br />
                                        <select class="form-control col-md-12" data-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Select status" tab-index="-1" width="auto" id="status_filter2">
                                            <option value="">All</option>
                                            <option>Pending</option>
                                            <option>Approved</option>
                                            <option>Amended Pending</option>
                                            <option>Amended Approved</option>
                                            <option>Declined</option>
                                            <option>Amended Declined</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                <!-- /.content -->

<!--                  MAIN CONTENT TAB2                 -->

<?php
  $query_approve="SELECT 
    *,
    SUM(weekly_sunday) + SUM(weekly_monday) + SUM(weekly_tuesday) + SUM(weekly_wednesday) + SUM(weekly_thursday) + SUM(weekly_friday) + SUM(weekly_saturday) AS `total_hours`,
    'normal' as `type`
FROM
    tbl_weeklyutilization
        INNER JOIN
    tbl_employees ON tbl_weeklyutilization.employeeCode = tbl_employees.employeeCode
WHERE
    weekly_approval IN ('Approved' , 'Declined')
GROUP BY weekly_startDate , tbl_weeklyutilization.employeeCode 
UNION ALL SELECT 
    *,
    SUM(weekly_sunday) + SUM(weekly_monday) + SUM(weekly_tuesday) + SUM(weekly_wednesday) + SUM(weekly_thursday) + SUM(weekly_friday) + SUM(weekly_saturday) AS `total_hours`,
    'history' as `type`
FROM
    tbl_weeklyutilization_history
        INNER JOIN
    tbl_employees ON tbl_weeklyutilization_history.employeeCode = tbl_employees.employeeCode
where is_shown = 1
GROUP BY weekly_startDate , tbl_weeklyutilization_history.employeeCode
ORDER BY weekly_endDate DESC";
  $stmt_approve=$con->prepare($query_approve);
  $stmt_approve->execute();
  $num=$stmt_approve->rowCount();

  if($num>0){
      ?>
    <form class="form-horizontal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<table id='example2' class='table table-hover table-striped'><br/>
    <thead>
    <tr>
        
        <th>Week Ending</th>
        <th>Employee Name</th>
        <th>Hours</th>
        <th>Date Submitted</th>
        <th>Status</th>
        <th>Date Processed</th>

    </tr>
        </thead>
    <tbody>
        <?php
//                         $rowCounter=0;
      while($row_approve=$stmt_approve->fetch(PDO::FETCH_ASSOC)){
          $employeeNumber = $row_approve['employeeCode'];
          $approvedTimeSubmit = $row_approve['weekly_timeSubmitted'];
          $approvedDateSubmit = $row_approve['weekly_dateSubmitted'];
          $endDateSubmit = $row_approve['weekly_endDate'];
          $timesheetCode = $row_approve['weekly_timesheetCode'];

    ?>

          <tr>
              
              <td><a href="#" data-toggle='modal' data-target='#viewTimesheetDetails-<?php echo $row_approve['employeeCode'] ?>-<?php echo $row_approve['weekly_startDate'] ?>-<?php echo $row_approve['type'] ?>'  class="navbar-link"><input type='text' name='timesheet_ID' hidden value=' <?php echo $row_approve['weekly_ID']; ?>'><?php echo $row_approve['weekly_endDate'];?></a></td>

              <td><?php echo $row_approve['lastName'] . ", " . $row_approve['firstName'] . " " . $row_approve['middleName'] ;?></td>
              <td><?php echo $row_approve['total_hours'];?></td>
              <td><?php echo $row_approve['weekly_dateSubmitted'];?></td>
              <td><?php echo $row_approve['weekly_status'];?></td>
              <td><?php echo $row_approve['weekly_dateProcessed'];?></td>
    </tr>
    <?php
//                            $rowCounter=$rowCounter + 1;
      }
    ?>
        </tbody>
    </table>
<!--    MODAL START                  -->
<?php
//                        $modalCounter=0;
//                          while($modalCounter<=$rowCounter) {

        ?>

<!--  MODAL END   -->


        <?php
//                        }
      ?>
<!--
    </tbody>
</table>

-->

<!--                          APPROVEDECLINE-->
    </form>

<?php
  }
else {
echo "No Timesheets submitted.";
}
  ?>

</div>

            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- ./card -->
      </div>
      <!-- /.col -->
    </div>

    <!-- /.content -->
  </div>

<!-- MODALS -->

<?php
    $query3 ="SELECT 
    *,
    'normal' as `type`
FROM
    tbl_weeklyutilization
        INNER JOIN
    tbl_employees ON tbl_employees.employeeCode = tbl_weeklyutilization.employeeCode
GROUP BY tbl_weeklyutilization.employeeCode , weekly_startDate
UNION
SELECT 
    *,
    'history' as `type`
FROM
    tbl_weeklyutilization_history
        INNER JOIN
    tbl_employees ON tbl_employees.employeeCode = tbl_weeklyutilization_history.employeeCode
GROUP BY tbl_weeklyutilization_history.employeeCode , weekly_startDate";
    $stmts =$con->prepare($query3);
    $stmts->execute();
    $nums = $stmts->rowCount();

    while ($row_tables=$stmts->fetch(PDO::FETCH_ASSOC)) {
        $employeeId     = $row_tables['employeeCode'];
        $timeSubmitted  = $row_tables['weekly_timeSubmitted'];
        $dateSubmitted  = $row_tables['weekly_dateSubmitted'];
        $timesheetCode  = $row_tables['weekly_timesheetCode'];
?>
    <div class = "modal fade" role = "dialog" id = "viewTimesheetDetails-<?php echo $row_tables['employeeCode'] ?>-<?php echo $row_tables['weekly_startDate'] ?>-<?php echo $row_tables['type'] ?>">
        <div class = "modal-dialog modal-lg">
            <div class = "modal-content">
                <div class = "modal-header"> View Timesheet </div>
                <div class = "modal-body">
                    <table id="classTable" class="table table-bordered">
                        <thead></thead>
                        <tbody>
                            <tr>
                                <th class="col-sm-1">Project Code</th>
                                <!-- <th class="col-sm-1">Work Type</th> -->
                                <th class="col-sm-1">Task Code</th>
                                <th class="col-sm-1">Remarks</th>
                                <th class="col-sm-1">Mon</th>
                                <th class="col-sm-1">Tue</th>
                                <th class="col-sm-1">Wed</th>
                                <th class="col-sm-1">Thu</th>
                                <th class="col-sm-1">Fri</th>
                                <th class="col-sm-1">Sat</th>
                                <th class="col-sm-1">Sun</th>
                                <th class="col-sm-1">Total</th>
                            </tr>
                            <?php
                            if ($row_tables['type'] == "normal") {
                                $query_table = "SELECT * FROM tbl_weeklyutilization
                                    INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID
                                    INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID
                                    WHERE employeeCode = '". $employeeId ."' AND weekly_startDate='" . $row_tables['weekly_startDate'] . "'";
                            } else {
                                $query_table = "SELECT * FROM tbl_weeklyutilization_history
                                    INNER JOIN tbl_project ON tbl_weeklyutilization_history.project_ID = tbl_project.project_ID
                                    INNER JOIN tbl_worktype ON tbl_weeklyutilization_history.work_ID = tbl_worktype.work_ID
                                    WHERE employeeCode = '". $employeeId ."' AND weekly_startDate='" . $row_tables['weekly_startDate'] . "'";
                            }

                            $stmt_table = $con->prepare($query_table);
                            $stmt_table->execute();

                            $sundayTotal    = 0;
                            $mondayTotal    = 0;
                            $tuesdayTotal   = 0;
                            $wednesdayTotal = 0;
                            $thursdayTotal  = 0;
                            $fridayTotal    = 0;
                            $saturdayTotal  = 0;
                            $total          = 0;
                            while ($row_table=$stmt_table->fetch(PDO::FETCH_ASSOC)) {
                                $sundayTotal    = $sundayTotal+ $row_table['weekly_sunday'];
                                $mondayTotal    = $mondayTotal+ $row_table['weekly_monday'];
                                $tuesdayTotal   = $tuesdayTotal+ $row_table['weekly_tuesday'];
                                $wednesdayTotal = $wednesdayTotal+ $row_table['weekly_wednesday'];
                                $thursdayTotal  = $thursdayTotal+ $row_table['weekly_thursday'];
                                $fridayTotal    = $fridayTotal+ $row_table['weekly_friday'];
                                $saturdayTotal  = $saturdayTotal+ $row_table['weekly_saturday'];
                                $overallTotal   = $row_table['weekly_overallTotal'];

                                $subtotal   = $row_table['weekly_sunday'] + $row_table['weekly_monday'] + $row_table['weekly_tuesday'] + $row_table['weekly_wednesday'] + $row_table['weekly_thursday'] + $row_table['weekly_friday'] + $row_table['weekly_saturday'];
                                $total      = $total + $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $row_table['project_name'];?></td>
                                <!-- <td><?php echo $row_table['work_name'];?></td> -->
                                <td><?php echo $row_table['weekly_taskCode'];?></td>
                                <td><?php echo $row_table['location_ID'];?></td>
                                <td><?php echo $row_table['weekly_monday'];?></td>
                                <td><?php echo $row_table['weekly_tuesday'];?></td>
                                <td><?php echo $row_table['weekly_wednesday'];?></td>
                                <td><?php echo $row_table['weekly_thursday'];?></td>
                                <td><?php echo $row_table['weekly_friday'];?></td>
                                <td><?php echo $row_table['weekly_saturday'];?></td>
                                <td><?php echo $row_table['weekly_sunday'];?></td>
                                <td><?php echo $subtotal;?></td>
                            </tr>
                                                        <?php
                                
                            }
                            ?>
                            <tr>
                                <td></td>
                                <!-- <td></td> -->
                                <td></td>
                                <th>Total</th>
                                <th><?php echo $mondayTotal;?></th>
                                <th><?php echo $tuesdayTotal;?></th>
                                <th><?php echo $wednesdayTotal;?></th>
                                <th><?php echo $thursdayTotal;?></th>
                                <th><?php echo $fridayTotal;?></th>
                                <th><?php echo $saturdayTotal;?></th>
                                <th><?php echo $sundayTotal;?></th>
                                <th><?php echo $total;?></th>
                            </tr>
                        </tbody>
                    </table>

                    <?php if ($row_tables['weekly_approval'] == "Approved") { ?>
                    <div class = "modal-footer"><a href="generate_xls.php?employee_id=<?php echo $employeeId ?>&weekly_enddate=<?php echo $row_tables['weekly_startDate'] ?>">print</a></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


<!--        Modal for Approved-->
        <?php
    }
        $query4="SELECT * FROM tbl_weeklyutilization WHERE weekly_approval='Approved'
                    ";
        $stmt4=$con->prepare($query4);
        $stmt4->execute();
        $num4=$stmt4->rowCount();

    while($row_table4=$stmt4->fetch(PDO::FETCH_ASSOC)){
        $employeeId4 = $row_table4['employeeCode'];
        $timeSubmitted4 = $row_table4['weekly_timeSubmitted'];
        $dateSubmitted4 = $row_table4['weekly_dateSubmitted'];
        $endDateSubmitted4 = $row_table4['weekly_endDate'];
        $timesheetCode4 = $row_table4['weekly_timesheetCode']
    ?>

    <div class = "modal fade" role = "dialog" id ="viewApprovedTimesheets-<?php echo $row['employeeCode'] ?>-<?php echo $row['weekly_startDate'] ?>">
        <div class = "modal-dialog modal-lg">
            <div class = "modal-content">
                <div class = "modal-header"> View Timesheet</div>
                <div class = "modal-body">

                <table id="classTable" class="table table-bordered">
                    <thead>
                    </thead>
                    <tbody>
                    <tr>
                        <th class="col-sm-1">Project Code</th>
                        <th class="col-sm-1">Work Type</th>
                        <th class="col-sm-1">Task Code</th>
                        <th class="col-sm-1">Remarks</th>
                        <th class="col-sm-1">Mon</th>
                        <th class="col-sm-1">Tue</th>
                        <th class="col-sm-1">Wed</th>
                        <th class="col-sm-1">Thu</th>
                        <th class="col-sm-1">Fri</th>
                        <th class="col-sm-1">Sat</th>
                        <th class="col-sm-1">Sun</th>
                        <th class="col-sm-1">Total</th>
                    </tr>

                <?php
                            $query_table5="SELECT * FROM tbl_weeklyutilization

                            INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID

                            INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID

                            INNER JOIN tbl_location ON tbl_weeklyutilization.location_ID = tbl_location.location_ID

                            WHERE employeeCode='". $employeeId4 ."'
                            AND weekly_timesheetCode='". $timesheetCode4 ."'  AND weekly_approval='Approved' GROUP BY weekly_ID, weekly_dateSubmitted
                              
                            ";
                            //AND weekly_approval='Approved'
                            
                            $stmt_table5=$con->prepare($query_table5);
                            $stmt_table5->execute();

                            $sundayTotalApproved=0;
                            $mondayTotalApproved=0;
                            $tuesdayTotalApproved=0;
                            $wednesdayTotalApproved=0;
                            $thursdayTotalApproved=0;
                            $fridayTotalApproved=0;
                            $saturdayTotalApproved=0;
        
                            while($row_table5=$stmt_table5->fetch(PDO::FETCH_ASSOC)){

                              ?>

                        <tr>
                            <td><?php echo $row_table5['project_name'];?></td>
                            <td><?php echo $row_table5['work_name'];?></td>
                            <td><?php echo $row_table5['weekly_taskCode'];?></td>
                            <td><?php echo $row_table5['location_name'];?></td>
                            <td><?php echo $row_table5['weekly_monday'];?></td>
                            <td><?php echo $row_table5['weekly_tuesday'];?></td>
                            <td><?php echo $row_table5['weekly_wednesday'];?></td>
                            <td><?php echo $row_table5['weekly_thursday'];?></td>
                            <td><?php echo $row_table5['weekly_friday'];?></td>
                            <td><?php echo $row_table5['weekly_saturday'];?></td>
                            <td><?php echo $row_table5['weekly_sunday'];?></td>
                            <td><?php echo $row_table5['weekly_total'];?></td>

                        </tr>

                            <?php
                                $sundayTotalApproved=$sundayTotalApproved+ $row_table5['weekly_sunday'];
                                $mondayTotalApproved=$mondayTotalApproved+ $row_table5['weekly_monday'];
                                $tuesdayTotalApproved=$tuesdayTotalApproved+ $row_table5['weekly_tuesday'];
                                $wednesdayTotalApproved=$wednesdayTotalApproved+ $row_table5['weekly_wednesday'];
                                $thursdayTotalApproved=$thursdayTotalApproved+ $row_table5['weekly_thursday'];
                                $fridayTotalApproved=$fridayTotalApproved+ $row_table5['weekly_friday'];
                                $saturdayTotalApproved=$saturdayTotalApproved+ $row_table5['weekly_saturday'];
                                $overallTotalApproved=$row_table5['weekly_overallTotal'];
                            }
                            ?>
                        
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th>Total</th>
                            <th><?php echo $mondayTotalApproved;?></th>
                            <th><?php echo $tuesdayTotalApproved;?></th>
                            <th><?php echo $wednesdayTotalApproved;?></th>
                            <th><?php echo $thursdayTotalApproved;?></th>
                            <th><?php echo $fridayTotalApproved;?></th>
                            <th><?php echo $saturdayTotalApproved;?></th>
                            <th><?php echo $sundayTotalApproved;?></th>
                            <th><?php echo $overallTotalApproved;?></th>
                        </tr>
                    </tbody>

                </table>

                    <div class = "modal-footer"><button type='submit' name='printTimesheet'>Print</button></div>
            </div>
        </div>
    </div>
        </div>
    <?php

    }

    
    ?>
      </div>
    </div>
    <!-- End Page -->


<!-- ADD MODAL -->
<div class="modal fade" id="exampleFormModal" aria-hidden="false" aria-labelledby="exampleFormModalLabel"
  role="dialog" tabindex="-1">
  <div class="modal-dialog modal-simple">
    <form class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="exampleFormModalLabel">Add Designation</h4>
      </div>
      <div class="modal-body">
        <form>
                   <div class="form-group form-material row">
                     <label class="col-md-4 col-form-label">Designation Name: </label>
                     <div class="col-md-8">
                       <input type="text" class="form-control" name="name" placeholder="Designation Name" autocomplete="off"
                       />
                     </div>
                   </div>
                   <div class="form-group form-material row">
                     <label class="col-md-4 col-form-label">Designation Description: </label>
                     <div class="col-md-8">
                       <textarea class="form-control" placeholder="Briefly Describe"></textarea>
                     </div>
                   </div>
                    <div class="form-group form-material row">
                       <label class="col-md-4 col-form-label" for="inputBasicEmail">Select Employees</label>
                       <div class="col-md-8">
                         <select class="form-control" required name="gender" multiple data-plugin="select2" data-placeholder="Select Here">
                           <option></option>
                             <option value="AK">Male</option>
                             <option value="HI">Female</option>
                         </select>
                       </div>
                 </div>
                 <br>
                   <div class="form-group form-material row">
                     <div class="col-md-9">
                       <button type="button" class="btn btn-primary">Submit </button>
                     </div>
                   </div>
                 </form>
      </div>
    </form>
  </div>
</div>
<!-- END ADD MODAL-->

    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');



 ?>

<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<script>
   function approveConfirm()
        {
            Swal.fire({
                title:'Are you sure you want to approve the timesheet?',
                type:'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I\'m sure'
            }).then((result) => {
                if(result.value){
                    $('#process_action').val('approve');
                    swal(
                        "Saving. . .",{
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            buttons: false
                        }
                    )
                    $('#form-process-timesheet').submit();
                }
            });
        }

        function rejectConfirm()
        {
            Swal.fire({
                title:'Are you sure you want to reject the timesheet?',
                type:'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I\'m sure'
            }).then((result) => {
                if(result.value){
                    $('#process_action').val('reject');
                    swal(
                        "Saving. . .",{
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            buttons: false
                        }
                    )
                    $('#form-process-timesheet').submit();
                }
            });
        }

        $('.pending_checkbox').change(function() {
            if ($('input.pending_checkbox:checked').length > 1) {
                $('#decline-btn').attr('disabled',true);
            } else {
                $('#decline-btn').attr('disabled',false);
            }
        });

        /*$(document).ready(function() {
            var table = $('#example1').DataTable( {
                orderCellsTop: true,
                fixedHeader: true
            } );
            // Setup - add a text input to each footer cell
            $('#example1 thead tr').clone(true).appendTo( '#example1 thead' );
            $('#example1 thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                if(title != "") {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
             
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table.column(i).search() !== this.value ) {
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    } );
                }
            } );
         
            
        } );

        $(document).ready(function() {
            var table = $('#example2').DataTable( {
                orderCellsTop: true,
                fixedHeader: true
            } );
            // Setup - add a text input to each footer cell
            $('#example2 thead tr').clone(true).appendTo( '#example2 thead' );
            $('#example2 thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                if(title != "") {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
             
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table.column(i).search() !== this.value ) {
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    });
                }
            });
         
            
        } );*/

        $(document).ready(function() {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                daysOfWeekDisabled: [1,2,3,4,5,6],
                autoclose: true
            });

            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd',
                daysOfWeekDisabled: [1,2,3,4,5,6],
                autoclose: true
            });

            var table1 = $('#example1').DataTable( {
                "searching": false,
                orderCellsTop: true,
                fixedHeader: true
            } );

            var table2 = $('#example2').DataTable( {
                "searching": false,
                orderCellsTop: true,
                fixedHeader: true
            } );

            $('#datepicker').change(function(){
                table1.column(1).search($(this).val()).draw() ;
            });

            $('#employee_name').change(function(){
                table1.column(2).search($(this).val()).draw() ;
            });

            $('#status_filter').change(function(){
                if ($(this).val() == ''){
                    table1.column(5).search($(this).val(), false, false).draw() ;
                } else {
                    table1.column(5).search('^'+$(this).val()+'$', true, false).draw() ;
                }
            });



            $('#datepicker2').change(function(){
                table2.column(0).search($(this).val()).draw() ;
            });

            $('#employee_name2').change(function(){
                table2.column(1).search($(this).val()).draw() ;
            });

            $('#status_filter2').change(function(){
                if ($(this).val() == ''){
                    table2.column(4).search($(this).val(), false, false).draw() ;
                } else {
                    table2.column(4).search('^'+$(this).val()+'$', true, false).draw() ;
                }
            });
        });
</script>

<!--  </body>-->
<!--</html>-->
