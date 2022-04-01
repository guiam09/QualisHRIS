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
$CURRENT_PAGE="Timesheet Application";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');

include ('get_week_range.php');
?>
<!-- Main Page Content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!---->

<style>
th {
    vertical-align: middle !important;
}
.invalid {
    border: 1px solid red !important;
}
#week-picker-wrapper .datepicker .datepicker-days tr:hover td, #week-picker-wrapper .datepicker table tr td.day:hover, #week-picker-wrapper .datepicker table tr td.focused {
    color: #000!important;
    background: #e5e2e3!important;
    border-radius: 0!important;
}
.work-hours {
    width:5em; 
    padding-right: 3px;
}
</style>


<div class="page" ng-app="hris" ng-controller="TimeSheetController">
      <div class="page-header">
          <h1 class="page-title"><i>Timesheets Application</i></h1>
      </div>

<?php  
    if (!empty($_POST['action']) && $_POST['action'] == 'submit_timesheet') {
        
        $query = "SELECT * FROM 
                        tbl_weeklyutilization 
                    WHERE employeeCode = '".htmlspecialchars(strip_tags($_SESSION['user_id']))."' AND 
                        weekly_startDate = '".$_POST['week_start']."' AND 
                        weekly_endDate = '".$_POST['week_end']."'";           
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        
        $saturdayTotal = $sundayTotal = $mondayTotal = $tuesdayTotal = $wednesdayTotal = $thursdayTotal = $fridayTotal = $subtotal = $total = 0;

        if ($num>0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $saturdayTotal += $row['weekly_saturday'];
                $sundayTotal += $row['weekly_sunday'];
                $mondayTotal += $row['weekly_monday'];
                $tuesdayTotal += $row['weekly_tuesday'];
                $wednesdayTotal += $row['weekly_wednesday'];
                $thursdayTotal += $row['weekly_thursday'];
                $fridayTotal += $row['weekly_friday'];

                $subtotal = ($saturdayTotal + $sundayTotal + $mondayTotal + $tuesdayTotal + $wednesdayTotal + $thursdayTotal + $fridayTotal);
                $total += $subtotal;
            }
        }

        if ($total >= 40) {
            $weekly_status      = (isset($_POST['weekly_status']) && ($_POST['weekly_status'] != "Pending" && $_POST['weekly_status'] != "Not Submitted")) ? 'Amended Pending' : 'Pending';
            $weekly_approval    = '';
            $data = [
                'week_start'        => $_POST['week_start'],
                'week_end'          => $_POST['week_end'],
                'employeeCode'      => htmlspecialchars(strip_tags($_SESSION['user_id'])),
                // 'weekly_approval'   => (isset($_POST['weekly_status']) && $_POST['weekly_status'] == "Processed") ? $_POST['weekly_approval'] : 'Pending',
                'weekly_approval'   => 'Pending',
                'weekly_status'     => $weekly_status,
            ];

            $updateSql = "UPDATE tbl_weeklyutilization SET weekly_approval=:weekly_approval,weekly_status=:weekly_status,weekly_dateSubmitted='".date('Y-m-d')."' WHERE weekly_startDate=:week_start and weekly_endDate=:week_end and employeeCode = :employeeCode";
            $con->prepare($updateSql)->execute($data);

            $data = [
                'week_start'        => $_POST['week_start'],
                'week_end'          => $_POST['week_end'],
                'employeeCode'      => htmlspecialchars(strip_tags($_SESSION['user_id'])),
                // 'weekly_approval'   => (isset($_POST['weekly_status']) && $_POST['weekly_status'] == "Processed") ? $_POST['weekly_approval'] : 'Pending',
                // 'weekly_approval'   => 'Pending',
                // 'weekly_status'     => $weekly_status,
            ];

            $updateSql = "UPDATE tbl_weeklyutilization_history SET is_shown = 1 WHERE weekly_startDate=:week_start and weekly_endDate=:week_end and employeeCode = :employeeCode";
            $con->prepare($updateSql)->execute($data);

            echo "<div class='alert alert-success'>Timesheet Submitted! <?php?></div>";
            $timesheetStatus = (isset($_POST['weekly_status']) && ($_POST['weekly_status'] != "Pending" && $_POST['weekly_status'] != "Not Submitted")) ? 'Amended Pending' : 'Pending';
        } else {
            echo "<div class='alert alert-danger'>Minimum 40 hours total! <?php?></div>";
        }
    }
?>
     
<!-- SAVING TIMESHEET PROCESS     -->
<?php
    if (isset($_POST['saveTimesheet'])) {
        $newProjectName = !empty($_POST['new_project_name']) ? $_POST['new_project_name'] : [];
        $newWork        = !empty($_POST['new_work_type']) ? $_POST['new_work_type'] : [];
        $newTask        = !empty($_POST['new_task_code']) ? $_POST['new_task_code'] : [];
        $newLocation    = !empty($_POST['new_work_location']) ? $_POST['new_work_location'] : [];

        $newSaturday    = !empty($_POST['new_saturday']) ? $_POST['new_saturday'] : [];
        $newSunday      = !empty($_POST['new_sunday']) ? $_POST['new_sunday'] : [];
        $newMonday      = !empty($_POST['new_monday']) ? $_POST['new_monday'] : [];
        $newTuesday     = !empty($_POST['new_tuesday']) ? $_POST['new_tuesday'] : [];
        $newWednesday   = !empty($_POST['new_wednesday']) ? $_POST['new_wednesday'] : [];
        $newThursday    = !empty($_POST['new_thursday']) ? $_POST['new_thursday'] : [];
        $newFriday      = !empty($_POST['new_friday']) ? $_POST['new_friday'] : [];    
                                
        $newArraySize = count($newProjectName);

        $weekID             = !empty($_POST['weekID']) ? $_POST['weekID'] : [];
        $savedProjectName   = !empty($_POST['saved_project_name']) ? $_POST['saved_project_name'] : [];
        $savedWork          = !empty($_POST['saved_work_name']) ? $_POST['saved_work_name'] : [];
        $savedTask          = !empty($_POST['saved_task_code']) ? $_POST['saved_task_code'] : [];
        $savedLocation      = !empty($_POST['saved_work_location']) ? $_POST['saved_work_location'] : [];
        $savedSaturday      = !empty($_POST['saved_saturday']) ? $_POST['saved_saturday'] : [];
        $savedSunday        = !empty($_POST['saved_sunday']) ? $_POST['saved_sunday'] : [];
        $savedMonday        = !empty($_POST['saved_monday']) ? $_POST['saved_monday'] : [];
        $savedTuesday       = !empty($_POST['saved_tuesday']) ? $_POST['saved_tuesday'] : [];
        $savedWednesday     = !empty($_POST['saved_wednesday']) ? $_POST['saved_wednesday'] : [];
        $savedThursday      = !empty($_POST['saved_thursday']) ? $_POST['saved_thursday'] : [];
        $savedFriday        = !empty($_POST['saved_friday']) ? $_POST['saved_friday'] : [];

        $savedArraySize = count($savedProjectName);
        try {
            
            $x = [$newSaturday, $newSunday, $newMonday, $newTuesday, $newWednesday, $newThursday, $newFriday];
            foreach ($x as $y) {
                $total = 0;
                foreach ($y as $z) {
                    $total += $z;

                    if (fmod($z, 0.5) != 0)  {
                        throw new Exception('Error: inputs must be divisible by .5');
                    }
                }
                if ($total > 24) {
                    throw new Exception('Error: 24 hours exceeded');
                }
            }

            $weekStart  = date("Y-m-d", strtotime($weekStart));

            $deleteQuery = 'Delete from tbl_weeklyutilization where weekly_startDate = :weekStart and employeeCode = "'.htmlspecialchars(strip_tags($_SESSION['user_id'])).'"';
            $stmt = $con->prepare($deleteQuery);
            $stmt->bindParam(':weekStart', $weekStart);

            $stmt->execute();
            $newArrayCtr = 0;
            while ($newArrayCtr < $newArraySize) {
                //insert query
                $query = "INSERT INTO tbl_weeklyutilization(
                        employeeCode,
                        weekly_startDate,
                        weekly_endDate,
                        project_ID,
                        work_ID,
                        weekly_sunday,
                        weekly_monday,
                        weekly_tuesday,
                        weekly_wednesday,
                        weekly_thursday,
                        weekly_friday,
                        weekly_saturday,
                        location_ID,
                        weekly_status,
                        weekly_taskCode,
                        weekly_approval
                    ) VALUES (
                        :employeeID,
                        :weekStart,
                        :weekEnd,
                        :projectName,
                        :work,
                        :sunday,
                        :monday,
                        :tuesday,
                        :wednesday,
                        :thursday,
                        :friday,
                        :saturday,
                        :location,
                        :status,
                        :taskcode,
                        :approval
                    )";

                $stmt = $con->prepare($query);
                    
                //posted values
                $employeeID             = htmlspecialchars(strip_tags($_SESSION['user_id']));
                $newPostedProjectName   = $newProjectName[$newArrayCtr];
                $newPostedWork          = "1"; // Work Type is removed, set default value of work type to 1 for Regular Hour
                $newPostedTask          = $newTask[$newArrayCtr];
                $newPostedLocation      = $newLocation[$newArrayCtr];
                $newPostedSaturday      = $newSaturday[$newArrayCtr];
                $newPostedSunday        = $newSunday[$newArrayCtr];
                $newPostedMonday        = $newMonday[$newArrayCtr];
                $newPostedTuesday       = $newTuesday[$newArrayCtr];
                $newPostedWednesday     = $newWednesday[$newArrayCtr];
                $newPostedThursday      = $newThursday[$newArrayCtr];
                $newPostedFriday        = $newFriday[$newArrayCtr];
                                            
                //converting one date format into another
                //also included in thead th. used in query and foreach. binding parameters above. card title
                //include '../getWeekRange.php';
                $weekEnd    = date("Y-m-d", strtotime($weekEnd));
                $status     = !empty($_POST['weekStatus'][$newArrayCtr]) ? $_POST['weekStatus'][$newArrayCtr] : "Not Submitted";
                $approval     = !empty($_POST['weekly_approval'][$newArrayCtr]) ? $_POST['weekly_approval'][$newArrayCtr] : "Pending";
                    
                //bind parameters
                $stmt->bindParam(':employeeID', $employeeID);
                $stmt->bindParam(':weekStart', $weekStart);
                $stmt->bindParam(':weekEnd', $weekEnd);
                $stmt->bindParam(':projectName', $newPostedProjectName);
                $stmt->bindParam(':taskcode', $newPostedTask);
                $stmt->bindParam(':work', $newPostedWork);
                $stmt->bindParam(':sunday', $newPostedSunday);
                $stmt->bindParam(':monday', $newPostedMonday);
                $stmt->bindParam(':tuesday', $newPostedTuesday);
                $stmt->bindParam(':wednesday', $newPostedWednesday);
                $stmt->bindParam(':thursday', $newPostedThursday);
                $stmt->bindParam(':friday', $newPostedFriday);
                $stmt->bindParam(':saturday', $newPostedSaturday);
                $stmt->bindParam(':location', $newPostedLocation);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':approval', $approval);

                $newArrayCtr++;
                $stmt->execute();
            }
            
            if (!empty($_POST['save_and_submit']) && $_POST['save_and_submit'] == 'yes') {
        
                $query = "SELECT * FROM 
                                tbl_weeklyutilization 
                            WHERE employeeCode = '".htmlspecialchars(strip_tags($_SESSION['user_id']))."' AND 
                                weekly_startDate = '".$_POST['week_start']."' AND 
                                weekly_endDate = '".$_POST['week_end']."'";           
                $stmt = $con->prepare($query);
                $stmt->execute();
                $num = $stmt->rowCount();
                
                $saturdayTotal = $sundayTotal = $mondayTotal = $tuesdayTotal = $wednesdayTotal = $thursdayTotal = $fridayTotal = $subtotal = $total = 0;

                if ($num>0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $saturdayTotal += $row['weekly_saturday'];
                        $sundayTotal += $row['weekly_sunday'];
                        $mondayTotal += $row['weekly_monday'];
                        $tuesdayTotal += $row['weekly_tuesday'];
                        $wednesdayTotal += $row['weekly_wednesday'];
                        $thursdayTotal += $row['weekly_thursday'];
                        $fridayTotal += $row['weekly_friday'];

                        $subtotal = ($saturdayTotal + $sundayTotal + $mondayTotal + $tuesdayTotal + $wednesdayTotal + $thursdayTotal + $fridayTotal);
                        $total += $subtotal;
                    }
                }

                if ($total >= 40) {
                    $weekly_status      = (isset($_POST['weekly_status']) && ($_POST['weekly_status'] != "Pending" && $_POST['weekly_status'] != "Not Submitted")) ? 'Amended Pending' : 'Pending';
                    $weekly_approval    = '';
                    $data = [
                        'week_start'        => $_POST['week_start'],
                        'week_end'          => $_POST['week_end'],
                        'employeeCode'      => htmlspecialchars(strip_tags($_SESSION['user_id'])),
                        // 'weekly_approval'   => (isset($_POST['weekly_status']) && $_POST['weekly_status'] == "Processed") ? $_POST['weekly_approval'] : 'Pending',
                        'weekly_approval'   => 'Pending',
                        'weekly_status'     => $weekly_status,
                    ];

                    $updateSql = "UPDATE tbl_weeklyutilization SET weekly_approval=:weekly_approval,weekly_status=:weekly_status,weekly_dateSubmitted='".date('Y-m-d')."' WHERE weekly_startDate=:week_start and weekly_endDate=:week_end and employeeCode = :employeeCode";
                    $con->prepare($updateSql)->execute($data);

                    $data = [
                        'week_start'        => $_POST['week_start'],
                        'week_end'          => $_POST['week_end'],
                        'employeeCode'      => htmlspecialchars(strip_tags($_SESSION['user_id'])),
                    ];

                    $updateSql = "UPDATE tbl_weeklyutilization_history SET is_shown = 1 WHERE weekly_startDate=:week_start and weekly_endDate=:week_end and employeeCode = :employeeCode";
                    $con->prepare($updateSql)->execute($data);

                    echo "<div class='alert alert-success'>Timesheet Submitted! <?php?></div>";
                    $timesheetStatus = (isset($_POST['weekly_status']) && ($_POST['weekly_status'] != "Pending" && $_POST['weekly_status'] != "Not Submitted")) ? 'Amended Pending' : 'Pending';
                } else {
                    echo "<div class='alert alert-danger'>Minimum 40 hours total! <?php?></div>";
                }
            } else {
                echo "<div class='alert alert-success'>Timesheet Saved! <?php?></div>";
            }
        }
         // show error
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
        catch (Exception $e) {
            echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";
        }
    }  
?>
<!-- END OF SAVING TIMESHEET PROCESS -->



      <div class="page-content container-fluid" style="padding: 0px;">
          <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!--<h1>Time sheets</h1>-->
          </div>
        </div>
        </div>
        </section>

<section class="content">
<div class="container-fluid">
    <div class="row">
    <!-- left column -->
        <div class="col-md-12" style="padding: 0px;">
        <!-- general form elements -->
            <div class="panel">
                <div class="panel-heading">
                    <div class="row" style="padding: 15px 15px 0 15px;">
                        <div class="form-group col-md-5" id="week-picker-wrapper">
                            <label for="week" class="control-label">Select Week</label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-rm week-prev">&laquo;</button>
                                </span>
                                <input type="text" class="form-control week-picker" placeholder="Select a Week" value="<?php echo $weekStart .' - '.$weekEnd ?>">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-rm week-next">&raquo;</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 0 15px 0 15px;">
                        <div class="col-md-6">
                            <h3 class="card-title">Weekly Time ({{ weekStartDate | date : 'MMM dd, yyyy' }} - {{ weekEndDate | date : 'MMM dd, yyyy' }})</h3>
                        </div>
                        <div align="right" class="col-md-3">
                            <span>Status: <?php echo $timesheetStatus ?></span>
                        </div>
                        <div align="right" class="col-md-3">
                            <button ng-click="submitConfirm()" <?php echo $disabled ?> class="btn btn-default btn-sm submit" id="submit-btn">Submit <span class="glyphicon glyphicon-send"></span></button>
                            <button type="button" <?php echo $disabled ?> ng-click="saveConfirm()" class="btn btn-default btn-sm save">Save <span class="glyphicon glyphicon-floppy-disk"></span></button>
                        </div>
                    </div>
                    <!-- Timesheet Table code Start -->
                    <!--<form method="POST" id="insert_form">-->
                    <form method="POST" id="insert_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?date=".$weekStart); ?>">
                        <input type="hidden" name="date" value="<?php echo !empty($_GET['date']) ? $_GET['date'] : '' ?>">
                        <div class=" card-body table-responsive p-0">
                            <table class="table table-striped table-hover" id="timesheet_table">
                                <tbody id="timesheet_table">    
                                    <tr>
                                        <th style="width: 400px;">Project Code</th>
                                        <th style="width: 400px;">Task Code</th>
                                        <th style="display: none;">Remarks</th>
                                        <th class="work-hours">Mo</th>
                                        <th class="work-hours">Tu</th>
                                        <th class="work-hours">We</th>
                                        <th class="work-hours">Th</th>
                                        <th class="work-hours">Fr</th>
                                        <th class="work-hours">Sa</th>
                                        <th class="work-hours">Su</th>
                                        <th class="work-hours">Total</th>
                                        <th style="min-width: 100px; width: 100px;"> <button <?php echo $disabled ?> type="button" name="add" class="btn btn-default btn-sm add" ng-click="addTask()"><i class="fas fa-plus"></i></button></th>
                                    </tr>
                                    <!-- Display data from database -->
                                    <tr ng-if="weeklyUtilization.length > 0" ng-repeat="task in weeklyUtilization">
                                        <td>
                                            <select name="new_project_name[]" class="form-control project_name saved">
                                                <option value="" ng-if="task.project_ID == ''">Select project</option>
                                                <option ng-repeat="project in projects" value="{{project.project_ID}}" ng-selected="task.project_ID === project.project_ID">{{project.project_name}}</option>
                                            </select>
                                            <input type="text" name="weekID[]" value="{{task.weekly_ID}}" hidden></input>
                                            <input type="text" name="weekStatus[]" value="{{task.weekly_status}}" hidden></input>
                                            <input type="text" name="weekly_approval[]" value="{{task.weekly_approval}}" hidden></input>
                                        </td>
                                
                                        <td class="col-sm-3" style="display: none;">
                                            <select name="new_work_type[]" class="form-control project_name saved"> 
                                                <option ng-repeat="workType in workTypes" value="{{workType.work_ID}}" ng-selected="task.work_ID === workType.work_ID">{{workType.work_name}}</option>
                                            </select>
                                        </td>
                                        
                                        <td>
                                            <input <?php echo $disabled ?> type="text" name="new_task_code[]" value="{{task.weekly_taskCode}}" class="form-control saved"></input>
                                        </td>
                                        
                                        <td class="col-sm-3" style="display:none">
                                            <input type="text" name="new_work_location[]" value="{{task.location_ID}}" class="form-control saved remarks"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_monday[]" id="{{task.weekly_ID}}" class="form-control dailyWorkedHours monday saved {{task.weekly_ID}}" step="0.5" min="0" max="24" value="{{task.weekly_monday}}"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_tuesday[]" id="{{task.weekly_ID}}" class="form-control dailyWorkedHours tuesday saved {{task.weekly_ID}}" step="0.5" min="0" max="24" value="{{task.weekly_tuesday}}"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_wednesday[]" id="{{task.weekly_ID}}" class="form-control dailyWorkedHours wednesday saved {{task.weekly_ID}}" step="0.5" min="0" max="24" value="{{task.weekly_wednesday}}"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_thursday[]" id="{{task.weekly_ID}}" class="form-control dailyWorkedHours thursday saved {{task.weekly_ID}}" step="0.5" min="0" max="24" value="{{task.weekly_thursday}}"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_friday[]" id="{{task.weekly_ID}}" class="form-control dailyWorkedHours friday saved {{task.weekly_ID}}" step="0.5" min="0" max="24" value="{{task.weekly_friday}}"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_saturday[]" id="{{task.weekly_ID}}" class="form-control dailyWorkedHours saturday saved {{task.weekly_ID}}" step="0.5" min="0" max="24" value="{{task.weekly_saturday}}"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_sunday[]" id="{{task.weekly_ID}}" class="form-control dailyWorkedHours sunday saved {{task.weekly_ID}}" step="0.5" min="0" max="24" value="{{task.weekly_sunday}}"></input>
                                        </td>

                                        <td>
                                            <input size="4" maxlength="4" type="number" id="{{task.weekly_ID}}" class="form-control totalWeeklyWorkedHours{{task.weekly_ID}}" readonly value="{{task.weekly_total | number : 1}}"></input>
                                        </td>

                                        <td>
                                            <div>
                                                <button type="button" data-toggle="tooltip" title="Remarks" class="btn btn-info btn-sm" onclick="show_remarks_modal($(this))">
                                                    <i class="fas fa-file"></i>
                                                </button>
                                                <button <?php echo $disabled ?> type="button" name="remove" class="btn btn-danger btn-sm remove saved">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th style="display: none;"></th>
                                    <th style="display: none;"></th>
                                    <th style="text-align: right; vertical-align: middle;">Total</th>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Monday | number : 1" border="0" readonly min="0" name="monday_total[]" class="form-control work-hours mondayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Tuesday | number : 1" border="0" readonly min="0" name="tuesday_total[]" class="form-control work-hours tuesdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Wednesday | number : 1" border="0" readonly min="0" name="wednesday_total[]" class="form-control work-hours wednesdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Thursday | number : 1" border="0" readonly min="0" name="thursday_total[]" class="form-control work-hours thursdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Friday | number : 1" border="0" readonly min="0" name="friday_total[]" class="form-control work-hours fridayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Saturday | number : 1" border="0" readonly min="0" max="24" name="saturday_total[]" class="form-control work-hours saturdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Sunday | number : 1" border="0" readonly min="0" name="sunday_total[]" class="form-control work-hours sundayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" ng-value="dailyTotal.Overall() | number : 1" border="0" readonly min="0" name="overall_total[]" class="form-control work-hours overallTotal" /></td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                        

                        
                        <input type="hidden" name="saveTimesheet">
                        <input type="hidden" id="save_and_submit" name="save_and_submit" value="no">
                        <input type="hidden" value="<?php echo $weekStart ?>" name="week_start">
                        <input type="hidden" value="<?php echo $weekEnd ?>" name="week_end">
                        <input type="hidden" value="{{weeklyStatus}}" name="weekly_status">
                        <input type="hidden" value="{{weeklyApproval}}" name="weekly_approval">
                    </form>
                    <!-- Timesheet Table code END -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    </div>

    <!-- Warning Modal -->
    <div class="modal fade" id="warningModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h3 class="card-title">Warning</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <div class='row' ng-repeat="message in warningMessages">
                        <div class='col-md-12'>
                            {{ message }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-outline" data-dismiss="modal">
                        <i class="fa fa-close"></i> 
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Warning Modal -->
</div>
<?php
    include ('../includes/footer.php');
    include ('../includes/scripts.php');
    include ('../includes/form_scripts.php');
?>
</body>
</html>

<form id="submit-timesheet-form" method="post">
    <input type="hidden" value="<?php echo $weekStart ?>" name="week_start">
    <input type="hidden" value="<?php echo $weekEnd ?>" name="week_end">
    <input type="hidden" value="submit_timesheet" name="action">
    <input type="hidden" value="<?php echo $weekly_status ;?>" name="weekly_status">
    <input type="hidden" value="<?php echo $weekly_approval ;?>" name="weekly_approval">
</form>

<div class="modal fade" id="applyLeaveModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="card-title">Remarks</h3>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
                <!--<h4 class="modal-title"><b><span class="employee_id"></span></b></h4>-->
            </div>
            <div class="modal-body ">
                <div class='row'>
                    <div class='col-md-12'>
                        <textarea id="temp_remarks" style="width:100%"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="set_remarks()" class="btn btn-default btn-outline" data-dismiss="modal">
                    <i class="fa fa-close"></i> 
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="../node_modules/angular/angular.min.js"></script>
<script type="text/javascript" src="../node_modules/angularjs-datatables/src/angular-datatables.js"></script>
<script type="text/javascript" src="TimeSheet/timeSheetCtrl.js"></script>
<script>
    var remarks_elem;

    function show_remarks_modal(elem) {
        remarks_elem = elem;

        $('#temp_remarks').val(remarks_elem.parent().parent().find('.remarks').val());

        $('#applyLeaveModal').modal('show');
    }

    function set_remarks()
    {
        remarks_elem.parent().parent().find('.remarks').val($('#temp_remarks').val());
    }

    $(document).ready(function(){
        var date = new Date();
        date.setDate(date.getDate() + 7);
        $('.apply-leave-date').datepicker({
            startDate: date
        });
    });

</script>




 