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

//functions
function fill_projectCode_select_box($con, $rowData = [])
{
    $output = '';    
    $query = "SELECT * FROM tbl_project ORDER BY project_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    
    foreach ($result as $row) {
        $selectedProject = $row["project_ID"];
        $selected = (!empty($rowData['project_ID']) && $rowData['project_ID'] == $row['project_ID']) ? 'selected' : '';
        $output .= '<option value="' . $row["project_ID"] .'" '.$selected.'>'.$row["project_name"].'</option>';
    }
    return $output;
}

function fill_workType_select_box($con, $rowData = [])
{
    $output = '';    
    $query = "SELECT * FROM tbl_worktype ORDER BY work_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();

    foreach($result as $row)
    {
        $selected = (!empty($rowData['work_ID']) && $rowData['work_ID'] == $row['work_ID']) ? 'selected' : '';
        $output .= '<option value="' . $row["work_ID"] .'" '.$selected.'>'. $row["work_name"].'</option>';
    }
    return $output;
}

function fill_location_select_box($con, $rowData = [])
{
    $output = '';    
    $query = "SELECT * FROM tbl_location ORDER BY location_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    
    foreach($result as $row)
    {
        $selected = (!empty($rowData['location_ID']) && $rowData['location_ID'] == $row['location_ID']) ? 'selected' : '';
        $output .= '<option value="' . $row["location_ID"] .'" '.$selected.'>'.$row["location_name"].'</option>';
    }
    return $output;
}

//end functions

?>
<!-- Main Page Content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!---->

<style>

#week-picker-wrapper .datepicker .datepicker-days tr:hover td, #week-picker-wrapper .datepicker table tr td.day:hover, #week-picker-wrapper .datepicker table tr td.focused {
    color: #000!important;
    background: #e5e2e3!important;
    border-radius: 0!important;
}
.work-hours {
    width:4.5em; 
    padding-right: 3px;"
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



      <div class="page-content container-fluid">
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
        <div class="col-md-12">
        <!-- general form elements -->
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
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
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">Weekly Time (<?php echo  $displayWeekStart . " - " . $displayWeekEnd; ?>)</h3>
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
                    <form method="POST" id="insert_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" name="date" value="<?php echo !empty($_GET['date']) ? $_GET['date'] : '' ?>">
                        <div class=" card-body table-responsive p-0">
                            <table class="table table-striped table-hover" id="timesheet_table">
                                <tbody id="timesheet_table">    
                                    <tr>
                                        <th style="width:25%">Project Code</th>
                                        <th style="width:10%; display: none;">Work Type</th>
                                        <th style="width:15%">Task Code</th>
                                        <th style="width:10%; display: none;">Remarks</th>
                                        <th style="width:5%">Mo</th>
                                        <th style="width:5%">Tu</th>
                                        <th style="width:5%">We</th>
                                        <th style="width:5%">Th</th>
                                        <th style="width:5%">Fr</th>
                                        <th style="width:5%">Sa</th>
                                        <th style="width:5%">Su</th>
                                        <th style="width:5%">Total</th>
                                        <th style="width:20%"> <button <?php echo $disabled ?> type="button" name="add" class="btn btn-default btn-sm add"><i class="fas fa-plus"></i></button></th>
                                    </tr>
                                    
                                    <!-- Display data from database -->
                                    <?php
                                        $userID = $_SESSION['user_id'];
                                        $query = "SELECT * FROM tbl_weeklyutilization
                             
                                        INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID
                            
                                        INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID
                            
                                        WHERE employeeCode = '$userID' AND weekly_startDate = '$weekStart' AND weekly_endDate = '$weekEnd';
                                         ";
                            
                                        $stmt = $con->prepare($query);
                                        $stmt->execute();
                                        $num = $stmt->rowCount();
                                        $weekly_status = '';
                                        $weekly_approval = '';

                                        $saturdayTotal = $sundayTotal = $mondayTotal = $tuesdayTotal = $wednesdayTotal = $thursdayTotal = $fridayTotal = $subtotal = $total = 0;
                                        
                                        if($num>0){
            
                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                $weekly_status = $row['weekly_status'];
                                                $weekly_approval = $row['weekly_approval'];

                                                $saturdayTotal += $row['weekly_saturday'];
                                                $sundayTotal += $row['weekly_sunday'];
                                                $mondayTotal += $row['weekly_monday'];
                                                $tuesdayTotal += $row['weekly_tuesday'];
                                                $wednesdayTotal += $row['weekly_wednesday'];
                                                $thursdayTotal += $row['weekly_thursday'];
                                                $fridayTotal += $row['weekly_friday'];

                                                $subtotal = ($saturdayTotal + $sundayTotal + $mondayTotal + $tuesdayTotal + $wednesdayTotal + $thursdayTotal + $fridayTotal);
                                                $total += $subtotal;
                                                
                                    ?>
                                                <tr>
                                                    <td class="col-sm-3">
                                                        <select name="new_project_name[]" class="form-control project_name saved" style="min-width: 180px;"> 
                                                          <?php echo fill_projectCode_select_box($con, $row); ?>
                                                        </select>
                                                        <input type="text" name="weekID[]" value="<?php $row['weekly_ID'] ;?>" hidden></input>
                                                        <input type="text" name="weekStatus[]" value="<?php echo $row['weekly_status'] ;?>" hidden></input>
                                                        <input type="text" name="weekly_approval[]" value="<?php echo $row['weekly_approval'] ;?>" hidden></input>
                                                    </td>
                                          
                                                    <td class="col-sm-3" style="display: none;">
                                                        <select name="new_work_type[]" class="form-control project_name saved"> 
                                                          <?php echo fill_workType_select_box($con, $row); ?>
                                                        </select>
                                                    </td>
                                                    
                                                    <td class="col-sm-3">
                                                        <input <?php echo $disabled ?> type="text" name="new_task_code[]" value="<?php echo $row['weekly_taskCode']; ?>" class="form-control saved" style="min-width: 100px;"></input>
                                                    </td>
                                                    
                                                    <td class="col-sm-3" style="display:none">
                                                        <input type="text" name="new_work_location[]" value="<?php echo $row['location_ID'] ;?>" class="form-control saved remarks"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_monday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours work-hours monday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_monday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_tuesday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours work-hours tuesday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_tuesday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_wednesday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours work-hours wednesday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_wednesday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_thursday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours work-hours thursday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_thursday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_friday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours work-hours friday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_friday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_saturday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours work-hours saturday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_saturday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" <?php echo $disabled ?> type="number" name="new_sunday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours work-hours sunday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_sunday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input size="4" maxlength="4" type="text" value="<?php echo $subtotal ?>" id="<?php echo $row['weekly_ID']; ?>" class="form-control work-hours  totalWeeklyWorkedHours<?php echo $row['weekly_ID'] ?>" step="0.5" min="0" max="24" readonly value="<?php echo $row['weekly_total']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <button type="button" data-toggle="tooltip" title="Remarks" data-placement="top" class="btn btn-info btn-sm" onclick="show_remarks_modal($(this))">
                                                            <i class="fas fa-file"></i>
                                                        </button>
                                                        <br/>
                                                        <br/>
                                                        <button <?php echo $disabled ?> type="button" name="remove" class="btn btn-danger btn-sm remove saved">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </td>

                                                </tr>
                                            
                                                <?php
                                            }
                                        }
                                    ?>
                                
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th style="display: none;"></th>
                                    <th style="display: none;"></th>
                                    <th style="text-align: center; vertical-align: middle;">Total</th>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $mondayTotal ?>" border="0" readonly min="0" name="monday_total[]" class="form-control work-hours mondayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $tuesdayTotal ?>" border="0" readonly min="0" name="tuesday_total[]" class="form-control work-hours tuesdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $wednesdayTotal ?>" border="0" readonly min="0" name="wednesday_total[]" class="form-control work-hours wednesdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $thursdayTotal ?>" border="0" readonly min="0" name="thursday_total[]" class="form-control work-hours thursdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $fridayTotal ?>" border="0" readonly min="0" name="friday_total[]" class="form-control work-hours fridayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $saturdayTotal ?>" border="0" readonly min="0" max="24" name="saturday_total[]" class="form-control work-hours saturdayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $sundayTotal ?>" border="0" readonly min="0" name="sunday_total[]" class="form-control work-hours sundayTotal" /></td>
                                    <td><input size="4" maxlength="4" type="text" value = "<?php echo $subtotal ?>" border="0" readonly min="0" name="overall_total[]" class="form-control work-hours overallTotal" /></td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                        

                        
                    <input type="hidden" name="saveTimesheet">
                    <input type="hidden" id="save_and_submit" name="save_and_submit" value="no">
                    <input type="hidden" value="<?php echo $weekStart ?>" name="week_start">
                    <input type="hidden" value="<?php echo $weekEnd ?>" name="week_end">
                    <input type="hidden" value="<?php echo $weekly_status ;?>" name="weekly_status">
                    <input type="hidden" value="<?php echo $weekly_approval ;?>" name="weekly_approval">
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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

        $(document).on('click', '.add', function(){
        // var saturdayHours = 0;
           var html = '';
        //   var ctr = 0;
           var now = $.now();
           html += '<tr>';
           html += '<td><select name="new_project_name[]" class="form-control project_name" style="min-width: 180px;"><option value="">Select Project</option><?php echo fill_projectCode_select_box($con); ?></select></td>';
           html += '<td style="display: none;"><select name="new_work_type[]" class="form-control work_type"><option value="">Select work type</option><?php echo fill_workType_select_box($con); ?></select></td>';
           html += '<td><input type="text" name="new_task_code[]" class="form-control task_code" style="min-width: 100px;"/></td>';
           html += '<td style="display: none;"><input type="text" name="new_work_location[]" class="form-control task_code remarks" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" step="0.5" min="0" max="24" name="new_monday[]" id="' + now + '" class="form-control work-hours dailyWorkedHours monday ' + now + '" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" step="0.5" min="0" max="24" name="new_tuesday[]" id="' + now + '" class="form-control work-hours dailyWorkedHours tuesday ' + now + '" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" step="0.5" min="0" max="24" name="new_wednesday[]" id="' + now + '" class="form-control work-hours dailyWorkedHours wednesday ' + now + '" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" step="0.5" min="0" max="24" name="new_thursday[]" id="' + now + '" class="form-control work-hours dailyWorkedHours thursday ' + now + '" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" step="0.5" min="0" max="24" name="new_friday[]" id="' + now + '" class="form-control work-hours dailyWorkedHours friday ' + now + '" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" step="0.5" min="0" max="24" name="new_saturday[]" id="' + now + '" class="form-control work-hours dailyWorkedHours saturday ' + now + '" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" step="0.5" min="0" max="24" name="new_sunday[]" id="' + now + '" class="form-control work-hours dailyWorkedHours sunday ' + now + '" /></td>';
           html += '<td><input size="4" maxlength="4" type="number" value = "0" border="0" readonly min="0" id="' + now + '" class="form-control work-hours totalWeeklyWorkedHours' + now + '" /></td>';
           html += '<td><button type="button" data-toggle="tooltip" title="Remarks" data-placement="top" class="btn btn-info btn-sm" onclick="show_remarks_modal($(this))"><i class="fas fa-file"></i></button><button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button></td>'
           
           
        //    html += '<td></td>'
           html += '</tr>';
           
           $('#timesheet_table').append(html);
            
        });
        
        //getting weekly and daily totals script
        $(document).on('keyup click', '.dailyWorkedHours', function(){
            var overallTotal = 0;
            $('.dailyWorkedHours').each(function(){
            overallTotal += parseFloat($(this).val()); 
            });
            $('.overallTotal').val(overallTotal);              
        });    
        //end getting totals script
    

        var date = new Date();
        date.setDate(date.getDate() + 7);
        $('.apply-leave-date').datepicker({
            startDate: date
        });
    });

  
    $(document).ready(function(){

        var weekpicker, start_date, end_date;

        function set_week_picker(date) {
            start_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
            end_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
            weekpicker.datepicker('update', start_date);
            weekpicker.val((start_date.getMonth() + 1) + '/' + start_date.getDate() + '/' + start_date.getFullYear() + ' - ' + (end_date.getMonth() + 1) + '/' + end_date.getDate() + '/' + end_date.getFullYear());
        }


        weekpicker = $('.week-picker');

        weekpicker.datepicker({
            autoclose: true,
            forceParse: false,
            container: '#week-picker-wrapper',
             weekStart: 1
        }).on("changeDate", function(e) {
            set_week_picker(e.date);
            window.location.href = window.location.href.replace( /[\?#].*|$/, "?date="+start_date.getFullYear() + '-' + ("0" + (start_date.getMonth() + 1)).slice(-2) + '-' + ("0" + (start_date.getDate())).slice(-2));
        });
        $('.week-prev').on('click', function() {
            var prev = new Date(start_date.getTime());
            prev.setDate(prev.getDate() - 1);
            set_week_picker(prev);
            window.location.href = window.location.href.replace( /[\?#].*|$/, "?date="+start_date.getFullYear() + '-' +("0" + (start_date.getMonth() + 1)).slice(-2)  + '-' + ("0" + (start_date.getDate())).slice(-2));
        });
        $('.week-next').on('click', function() {
            var next = new Date(end_date.getTime());
            next.setDate(next.getDate() + 1);
            set_week_picker(next);
            window.location.href = window.location.href.replace( /[\?#].*|$/, "?date="+start_date.getFullYear() + '-' +("0" + (start_date.getMonth() + 1)).slice(-2)  + '-' + ("0" + (start_date.getDate())).slice(-2));
        });
        <?php 
        if( !empty($_GET['date'])) { 
            $dateParam = explode('-', $_GET['date']);
        ?>
        set_week_picker(new Date(<?php echo $dateParam[0] ?>,<?php echo $dateParam[1]-1 ?>,<?php echo $dateParam[2] ?>));
        <?php } else { ?>
        set_week_picker(new Date);
        <?php } ?>
    });

</script>




 