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
include ('../includes/fetchData.php');

include ('get_week_range.php');

//functions
function fill_projectCode_select_box($con)
{
    $output = '';    
    $query = "SELECT * FROM tbl_project ORDER BY project_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    
    foreach($result as $row)
    {
        $selectedProject = $row["project_ID"];
        $output .= '<option value="' . $row["project_ID"] .'" SELECTED>'.$row["project_name"].'</option>';
    }
    return $output;
}

function fill_workType_select_box($con)
{
    $output = '';    
    $query = "SELECT * FROM tbl_worktype ORDER BY work_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    
    foreach($result as $row)
    {
        $output .= '<option value="' . $row["work_ID"] .'">'. $row["work_name"].'</option>';
    }
    return $output;
}

function fill_location_select_box($con)
{
    $output = '';    
    $query = "SELECT * FROM tbl_location ORDER BY location_name ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    
    foreach($result as $row)
    {
        $output .= '<option value="' . $row["location_ID"] .'">'.$row["location_name"].'</option>';
    }
    return $output;
}

//end functions

?>
<!-- Main Page Content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!---->

<style>

#week-picker-wrapper .datepicker .datepicker-days tr:hover td, #week-picker-wrapper .datepicker table tr td.day:hover, #week-picker-wrapper .datepicker table tr td.focused {
    color: #000!important;
    background: #e5e2e3!important;
    border-radius: 0!important;
}
</style>


<div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Timesheets Application</i></h1>
      </div>

<?php  
    if (!empty($_POST['action']) && $_POST['action'] == 'submit_timesheet') {
        $data = [
            'week_start'    => $_POST['week_start'],
            'week_end'      => $_POST['week_end'],
            'employeeCode'  => htmlspecialchars(strip_tags($_SESSION['user_id']))
        ];
        $updateSql = "UPDATE tbl_weeklyutilization SET weekly_approval='Pending',weekly_status='Pending' WHERE weekly_startDate=:week_start and weekly_endDate=:week_end and employeeCode = :employeeCode";
        $con->prepare($updateSql)->execute($data);

        echo "<div class='alert alert-success'>Timesheet Submitted! <?php?></div>";
    }
?>
     
<!-- SAVING TIMESHEET PROCESS     -->
<?php
    if (isset($_POST['saveTimesheet'])) {
        $newProjectName = $_POST['new_project_name'];
        $newWork        = $_POST['new_work_type'];
        $newTask        = $_POST['new_task_code'];
        $newLocation    = $_POST['new_work_location'];
        $newSaturday    = $_POST['new_saturday'];
        $newSunday      = $_POST['new_sunday'];
        $newMonday      = $_POST['new_monday'];
        $newTuesday     = $_POST['new_tuesday'];
        $newWednesday   = $_POST['new_wednesday'];
        $newThursday    = $_POST['new_thursday'];
        $newFriday      = $_POST['new_friday'];    
                                
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
                        weekly_taskCode
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
                        :taskcode
                    )";

                $stmt = $con->prepare($query);
                    
                //posted values
                $employeeID             = htmlspecialchars(strip_tags($_SESSION['user_id']));
                $newPostedProjectName   = $newProjectName[$newArrayCtr];
                $newPostedWork          = $newWork[$newArrayCtr];
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
                $status     = "Not Submitted";
                    
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

                $newArrayCtr++;
                $stmt->execute();
            }
                                 
            /*$savedArrayCtr = 0;
            while ($savedArrayCtr < $savedArraySize) {
                $updateTimesheetQuery = "UPDATE tbl_weeklyutilization SET
                    project_ID=:projectName,
                    work_ID=:work,
                    weekly_sunday=:sunday,
                    weekly_monday=:monday,
                    weekly_tuesday=:tuesday,
                    weekly_wednesday=:wednesday,
                    weekly_thursday=:thursday,
                    weekly_friday=:friday,
                    weekly_saturday=:saturday,
                    location_ID=:location,
                    weekly_taskCode=:taskcode,
                    WHERE weekly_ID=:weekID";

               $stmtUpdate = $con->prepare($updateTimesheetQuery);
                //posted values 
                $employeeID             = htmlspecialchars(strip_tags($_SESSION['user_id']));
                $savedPostedProjectName = $savedProjectName[$savedArrayCtr];
                $savedPostedWork        = $savedWork[$savedArrayCtr];
                $savedPostedTask        = $savedTask[$savedArrayCtr];
                $savedPostedLocation    = $savedLocation[$savedArrayCtr];
                $savedPostedSaturday    = $savedSaturday[$savedArrayCtr];
                $savedPostedSunday      = $savedSunday[$savedArrayCtr];
                $savedPostedMonday      = $savedMonday[$savedArrayCtr];
                $savedPostedTuesday     = $savedTuesday[$savedArrayCtr];
                $savedPostedWednesday   = $savedWednesday[$savedArrayCtr];
                $savedPostedThursday    = $savedThursday[$savedArrayCtr];
                $savedPostedFriday      = $savedFriday[$savedArrayCtr];
                $savedWeekID            = $weekID[$savedArrayCtr];
                                            
                 //bind parameters
                $stmtUpdate->bindParam(':employeeID', $employeeID);
                // $stmt->bindParam(':weekStart', $weekStart);
                // $stmt->bindParam(':weekEnd', $weekEnd);
                $stmtUpdate->bindParam(':projectName', $savedPostedProjectName);
                $stmtUpdate->bindParam(':work', $savedPostedWork);
                $stmtUpdate->bindParam(':sunday', $savedPostedSunday);
                $stmtUpdate->bindParam(':monday', $savedPostedMonday);
                $stmtUpdate->bindParam(':tuesday', $savedPostedTuesday);
                $stmtUpdate->bindParam(':wednesday', $savedPostedWednesday);
                $stmtUpdate->bindParam(':thursday', $savedPostedThursday);
                $stmtUpdate->bindParam(':friday', $savedPostedFriday);
                $stmtUpdate->bindParam(':saturday', $savedPostedSaturday);
                $stmtUpdate->bindParam(':location', $savedPostedLocation);
                $stmtUpdate->bindParam(':status', $status);
                $stmtUpdate->bindParam(':weekID', $savedWeekID);
                $stmtUpdate->bindParam(':taskcode', $savedPostedWork);
                                             
                $savedArrayCtr = $savedArrayCtr + 1;
                $stmt->execute();
            }*/
                                 
            echo "<div class='alert alert-success'>Timesheet Saved! <?php?></div>";
                
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
                       <div class="col-md-5">
                            <div class="form-group col-md-12 col-md-offset-12" id="week-picker-wrapper">
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
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <h3 class="card-title">Weekly Time (
                           <?php
                           //also included in thead th. used in query and foreach. binding parameters above
                           //include '../getWeekRange.php';
                           //display active week range
                           echo  $displayWeekStart . " - " . $displayWeekEnd; ?>)</h3>
                        </div>
                        <div align="right" class="col-lg-7">
                            <button class="btn btn-success btn-sm submit" id="submit-btn">Submit <span class="glyphicon glyphicon-send"></span></button>
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
                                        <th>Project Code</th>
                                        <th>Work Type</th>
                                        <th>Task Code</th>
                                        <th>Location</th>
                                        <th>Saturday</th>
                                        <th>Sunday</th>
                                        <th>Monday</th>
                                        <th>Tuesday</th>
                                        <th>Wednesday</th>
                                        <th>Thursday</th>
                                        <th>Friday</th>
                                        <th>Total</th>
                                        <th>Action <button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
                                    </tr>
                                    
                                    <!-- Display data from database -->
                                    <?php
                                        $userID = $_SESSION['user_id'];
                                        $query = "SELECT * FROM tbl_weeklyutilization
                             
                                        INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID
                            
                                        INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID
                            
                                        INNER JOIN tbl_location ON tbl_weeklyutilization.location_ID = tbl_location.location_ID
                            
                                        WHERE employeeCode = '$userID' AND weekly_startDate = '$weekStart' AND weekly_endDate = '$weekEnd';
                                         ";
                            
                                        $stmt = $con->prepare($query);
                                        $stmt->execute();
                                        $num = $stmt->rowCount();
                                        
                                        if($num>0){
        
                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                
                                    ?>
                                                <tr>
                                                    <td class="col-sm-1">
                                                        <select name="new_project_name[]" class="form-control project_name saved"> 
                                                          <?php echo fill_projectCode_select_box($con); ?>
                                                          
                                                        </select>
                                                        <input type="text" name="weekID[]" value="<?php $row['weekly_ID'] ;?>" hidden></input>
                                                    </td>
                                          
                                                    <td class="col-sm-1">
                                                        <select name="new_work_type[]" class="form-control project_name saved"> 
                                                          <?php echo fill_workType_select_box($con); ?>
                                                        </select>
                                                    </td>
                                                    
                                                    <td class="col-sm-1">
                                                        <input type="text" name="new_task_code[]" value="<?php echo $row['weekly_taskCode']; ?>" class="form-control saved"></input>
                                                    </td>
                                                    
                                                    <td class="col-sm-1">
                                                        <select name="new_work_location[]" class="form-control project_name saved"> 
                                                          <?php echo fill_location_select_box($con); ?>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="new_saturday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours saturday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_saturday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="new_sunday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours sunday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_sunday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="new_monday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours monday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_monday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="new_tuesday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours tuesday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_tuesday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="new_wednesday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours wednesday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_wednesday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="new_thursday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours thursday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_thursday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="new_friday[]" id="<?php echo $row['weekly_ID']; ?>" class="form-control dailyWorkedHours friday saved <?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" value="<?php echo $row['weekly_friday']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <input type="text" id="<?php echo $row['weekly_ID']; ?>" class="form-control totalWeeklyWorkedHours<?php echo $row['weekly_ID']; ?>" step="0.5" min="0" max="24" readonly value="<?php echo $row['weekly_total']; ?>"></input>
                                                    </td>

                                                    <td>
                                                        <button type="button" name="remove" class="btn btn-danger btn-sm remove saved"><span class="glyphicon glyphicon-minus"></span></input>
                                                    </td>

                                                </tr>
                                            
                                                <?php
                                            }
                                        }
                                    ?>
                                    
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total</th>
                                    <td><input type="text" value = "0" border="0" readonly min="0" max="24" name="saturday_total[]" class="form-control saturdayTotal" /></td>
                                    <td><input type="text" value = "0" border="0" readonly min="0" name="sunday_total[]" class="form-control sundayTotal" /></td>
                                    <td><input type="text" value = "0" border="0" readonly min="0" name="monday_total[]" class="form-control mondayTotal" /></td>
                                    <td><input type="text" value = "0" border="0" readonly min="0" name="tuesday_total[]" class="form-control tuesdayTotal" /></td>
                                    <td><input type="text" value = "0" border="0" readonly min="0" name="wednesday_total[]" class="form-control wednesdayTotal" /></td>
                                    <td><input type="text" value = "0" border="0" readonly min="0" name="thursday_total[]" class="form-control thursdayTotal" /></td>
                                    <td><input type="text" value = "0" border="0" readonly min="0" name="friday_total[]" class="form-control fridayTotal" /></td>
                                    <td><input type="text" value = "0" border="0" readonly min="0" name="overall_total[]" class="form-control overallTotal" /></td>
                                    <td><button type="button" name="save" data-toggle="modal" data-target="#saveTimesheetModal" class="btn btn-success btn-sm save">Save <span class="glyphicon glyphicon-floppy-disk"></span></button></td>
                                </tfoot>
                            </table>
                        </div>
                        
                        
                        <!--SAVE MODAL-->
    
    <div class="modal fade" id="saveTimesheetModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="card-title">Do you want to save your timesheet?</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                </div>
                <div class="modal-footer">
                    <form class="form-horizontal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="row">
        
                            <div class="form-group">
        
                                <div class="col-md-3">
                                      
                                    <button type="submit" class="btn btn-success btn-flat btn-sm "  name="saveTimesheet"><i class="fa fa-check-square-o"></i> Yes </button>
                                    <button type="submit" class="btn btn-danger btn-flat  btn-sm" data-dismiss="modal" ><i class="fa fa-times-circle-o"></i> No </button>
                                </div>
                            </div>
                        </div
                    </form>
                </div>
            </div>
        </div>
    </div>
    
 <!--END SAVE MODAL-->

                        
                        
                    </form>
                    <!-- Timesheet Table code END -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
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
</form>

<script>
    $(document).ready(function(){
      
        $(document).on('click', '.add', function(){
        // var saturdayHours = 0;
           var html = '';
        //   var ctr = 0;
           var now = $.now();
           html += '<tr>';
           html += '<td><select name="new_project_name[]" class="form-control project_name" ><option value="">Select Project</option><?php echo fill_projectCode_select_box($con); ?></select></td>';
           html += '<td><select name="new_work_type[]" class="form-control work_type"><option value="">Select work type</option><?php echo fill_workType_select_box($con); ?></select></td>';
           html += '<td><input type="text" name="new_task_code[]" class="form-control task_code" /></td>';
           html += '<td><select name="new_work_location[]" class="form-control work_location"><option value="">Select work location</option><?php echo fill_location_select_box($con); ?></select></td>';
           html += '<td><input type="number" value = "0" step="0.5" min="0" max="24" name="new_saturday[]" id="' + now + '" class="form-control dailyWorkedHours saturday ' + now + '" /></td>';
           html += '<td><input type="number" value = "0" step="0.5" min="0" max="24" name="new_sunday[]" id="' + now + '" class="form-control dailyWorkedHours sunday ' + now + '" /></td>';
           html += '<td><input type="number" value = "0" step="0.5" min="0" max="24" name="new_monday[]" id="' + now + '" class="form-control dailyWorkedHours monday ' + now + '" /></td>';
           html += '<td><input type="number" value = "0" step="0.5" min="0" max="24" name="new_tuesday[]" id="' + now + '" class="form-control dailyWorkedHours tuesday ' + now + '" /></td>';
           html += '<td><input type="number" value = "0" step="0.5" min="0" max="24" name="new_wednesday[]" id="' + now + '" class="form-control dailyWorkedHours wednesday ' + now + '" /></td>';
           html += '<td><input type="number" value = "0" step="0.5" min="0" max="24" name="new_thursday[]" id="' + now + '" class="form-control dailyWorkedHours thursday ' + now + '" /></td>';
           html += '<td><input type="number" value = "0" step="0.5" min="0" max="24" name="new_friday[]" id="' + now + '" class="form-control dailyWorkedHours friday ' + now + '" /></td>';
           html += '<td><input type="number" value = "0" border="0" readonly min="0" id="' + now + '" class="form-control totalWeeklyWorkedHours' + now + '" /></td>';
           html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td>'
           
           
           html += '<td></td>'
           html += '</tr>';
           
           $('#timesheet_table').append(html);
            
        });
        
        $(document).on('click', '.remove', function(){
             $(this).closest('tr').remove();
             
            var saturdaySum = 0;
            $('.saturday').each(function(){
            saturdaySum += parseFloat($(this).val());  
            });
            
            $('.saturdayTotal').val(saturdaySum);
            
            var sundaySum = 0;
            $('.sunday').each(function(){
            sundaySum += parseFloat($(this).val()); 
            });
            
            $('.sundayTotal').val(sundaySum);
            
            var mondaySum = 0;
            $('.monday').each(function(){
            mondaySum += parseFloat($(this).val()); 
            });
            
            $('.mondayTotal').val(mondaySum);

            var tuesdaySum = 0;
            $('.tuesday').each(function(){
            tuesdaySum += parseFloat($(this).val());  
            });
            
            $('.tuesdayTotal').val(tuesdaySum);

            var wednesdaySum = 0;
            $('.wednesday').each(function(){
            wednesdaySum += parseFloat($(this).val());
            });
            
            $('.wednesdayTotal').val(wednesdaySum);
            
            var thursdaySum = 0;
            $('.thursday').each(function(){
            thursdaySum += parseFloat($(this).val());  
            });
            
            $('.thursdayTotal').val(thursdaySum);
            
            var fridaySum = 0;
            $('.friday').each(function(){
            fridaySum += parseFloat($(this).val()); 
            });
            
            $('.fridayTotal').val(fridaySum);
            
            var overallTotal = 0;
            $('.dailyWorkedHours').each(function(){
            overallTotal += parseFloat($(this).val()); 
            });
            
            $('.overallTotal').val(overallTotal);
        });
        
    //getting weekly and daily totals script
        $(document).on('keyup click', '.saturday', function(){
            var saturdaySum = 0;
            $('.saturday').each(function(){
            saturdaySum += parseFloat($(this).val());  
            });
            
            $('.saturdayTotal').val(saturdaySum);
            
            var weeklySum = 0;
            var id = $(this).closest('input').attr('id');
            $('.'+id).each(function(){
               weeklySum += parseFloat($(this).val()); 
            });
            
            $('.totalWeeklyWorkedHours'+id).val(weeklySum);
        });
        
        $(document).on('keyup click', '.sunday', function(){
            var sundaySum = 0;
            $('.sunday').each(function(){
            sundaySum += parseFloat($(this).val()); 
            });
            
            $('.sundayTotal').val(sundaySum);
            
            var weeklySum = 0;
            var id = $(this).closest('input').attr('id');
            $('.'+id).each(function(){
               weeklySum += parseFloat($(this).val()); 
            });
     
            $('.totalWeeklyWorkedHours'+id).val(weeklySum);
        });
        
        $(document).on('keyup click', '.monday', function(){
            var mondaySum = 0;
            $('.monday').each(function(){
            mondaySum += parseFloat($(this).val()); 
            });
           
            $('.mondayTotal').val(mondaySum);
            
            var weeklySum = 0;
            var id = $(this).closest('input').attr('id');
            $('.'+id).each(function(){
               weeklySum += parseFloat($(this).val()); 
            });
  
            $('.totalWeeklyWorkedHours'+id).val(weeklySum);
        });
        
        $(document).on('keyup click', '.tuesday', function(){
            var tuesdaySum = 0;
            $('.tuesday').each(function(){
            tuesdaySum += parseFloat($(this).val());  
            });
            
            $('.tuesdayTotal').val(tuesdaySum);
            
            var weeklySum = 0;
            var id = $(this).closest('input').attr('id');
            $('.'+id).each(function(){
               weeklySum += parseFloat($(this).val()); 
            });
       
            $('.totalWeeklyWorkedHours'+id).val(weeklySum);
        });
        
        $(document).on('keyup click', '.wednesday', function(){
            var wednesdaySum = 0;
            $('.wednesday').each(function(){
            wednesdaySum += parseFloat($(this).val());
            });
          
            $('.wednesdayTotal').val(wednesdaySum);
            
            var weeklySum = 0;
            var id = $(this).closest('input').attr('id');
            $('.'+id).each(function(){
               weeklySum += parseFloat($(this).val()); 
            });
   
            $('.totalWeeklyWorkedHours'+id).val(weeklySum);
        });
        
        $(document).on('keyup click', '.thursday', function(){
            var thursdaySum = 0;
            $('.thursday').each(function(){
            thursdaySum += parseFloat($(this).val());  
            });
            
            $('.thursdayTotal').val(thursdaySum);
            
            var weeklySum = 0;
            var id = $(this).closest('input').attr('id');
            $('.'+id).each(function(){
               weeklySum += parseFloat($(this).val()); 
            });
   
            $('.totalWeeklyWorkedHours'+id).val(weeklySum);
        });
        
        $(document).on('keyup click', '.friday', function(){
            var fridaySum = 0;
            $('.friday').each(function(){
            fridaySum += parseFloat($(this).val()); 
            });
            
            $('.fridayTotal').val(fridaySum);
            
            var weeklySum = 0;
            var id = $(this).closest('input').attr('id');
            $('.'+id).each(function(){
               weeklySum += parseFloat($(this).val()); 
            });
     
            $('.totalWeeklyWorkedHours'+id).val(weeklySum);
        });    
        
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

$(document).ready(function() {
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
    // set_week_picker(new Date);

    $('#submit-btn').on('click', function() {
        $('#submit-timesheet-form').submit();
    });
});
    });
</script>




 