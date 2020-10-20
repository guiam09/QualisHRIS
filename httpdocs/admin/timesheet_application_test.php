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
        $output .= '<option value="' . $row["project_name"] .'">'.$row["project_name"].'</option>';
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
        $output .= '<option value="' . $row["work_name"] .'">'.$row["work_name"].'</option>';
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
        $output .= '<option value="' . $row["location_name"] .'">'.$row["location_name"].'</option>';
    }
    return $output;
}

//end functions

?>
<!---->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!---->
<div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Timesheets Application</i></h1>
      </div>
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
                            <h3 class="card-title">Weekly Time (
                           <?php
                           //also included in thead th. used in query and foreach. binding parameters above
                           //include '../getWeekRange.php';
                           //display active week range
                           echo  $displayWeekStart . " - " . $displayWeekEnd; ?>)</h3>
                        </div>
                        <button>Submit</button>
                    </div>
                    <!-- Timesheet Table code Start -->
                    <form method="POST" id="insert_form">
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
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total</th>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="saturday_total[]" class="form-control total" /></td>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="sunday_total[]" class="form-control total" /></td>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="monday_total[]" class="form-control total" /></td>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="tuesday_total[]" class="form-control total" /></td>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="wednesday_total[]" class="form-control total" /></td>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="thursday_total[]" class="form-control total" /></td>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="friday_total[]" class="form-control total" /></td>
                                    <td><input type="text" value = "" border="0" disabled min="0" name="overall_total[]" class="form-control overall_total" /></td>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                    <!-- Timesheet Table code END -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include ('../includes/footer.php');
    include ('../includes/scripts.php');
    include ('../includes/form_scripts.php');
?>

</body>
</html>

<script>
    $(document).ready(function(){
        $(document).on('click', '.add', function(){
           var html = '';
           html += '<tr>';
           html += '<td cellpadding="5"><select name="project_name[]" class="form-control project_name" ><option value="">Select Project</option><?php echo fill_projectCode_select_box($con); ?></select></td>';
           html += '<td><select name="work_type[]" class="form-control work_type"><option value="">Select work type</option><?php echo fill_workType_select_box($con); ?></select></td>';
           html += '<td><input type="text" name="task_code[]" class="form-control task_code" /></td>';
           html += '<td><select name="work_location[]" class="form-control work_location"><option value="">Select work location</option><?php echo fill_location_select_box($con); ?></select></td>';
           html += '<td><input type="number" step="0.5" min="0" max="24" class="hours" name="saturday[]" class="form-control satuday" /></td>';
           html += '<td><input type="number" step="0.5" min="0" max="24" class="hours" name="sunday[]" class="form-control sunday" /></td>';
           html += '<td><input type="number" step="0.5" min="0" max="24" class="hours" name="monday[]" class="form-control monday" /></td>';
           html += '<td><input type="number" step="0.5" min="0" max="24" class="hours" name="tuesday[]" class="form-control tuesday" /></td>';
           html += '<td><input type="number" step="0.5" min="0" max="24" class="hours" name="wednesday[]" class="form-control wednesday" /></td>';
           html += '<td><input type="number" step="0.5" min="0" max="24" class="hours" name="thursday[]" class="form-control thursday" /></td>';
           html += '<td><input type="number" step="0.5" min="0" max="24" class="hours" name="friday[]" class="form-control friday" /></td>';
           html += '<td><input type="text" value = "" border="0" disabled min="0" name="total[]" class="form-control total" /></td>';
           html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td>'
           
           
           html += '<td></td>'
           html += '</tr>';
           
           $('#timesheet_table').append(html);
           
        });
        
         $(document).on('click', '.remove', function(){
             $(this).closest('tr').remove();
         });
        
 /*       $(document).on('input', '.hours', function(){
           var total= 
            <?php 
                
            ?> 
        });
   */     

        
    });
</script>
