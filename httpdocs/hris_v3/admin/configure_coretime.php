<?php
include_once ('../includes/configuration.php');

include ('../db/connection.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
?>



    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Core Time Configuration</i></h1>

      </div>

      <div class="page-content container-fluid">
        <?php
                    $message = isset($_GET['add_coretime']) ? $_GET['add_coretime'] : "";

                    if($message=='success'){
                        echo "<div class='alert alert-success'>Coretime Successfully Added!</div>";
                    }

                    else if($message=='failed'){
                      echo "<div class='alert alert-danger'>Unable to add a new Coretime.</div>";
                    }


                    $message3 = isset($_GET['edit_coretime']) ? $_GET['edit_coretime'] : "";

                    if($message3=='success'){
                        echo "<div class='alert alert-success'>Coretime Successfully Updated!</div>";
                    }

                    else if($message3=='failed'){
                      echo "<div class='alert alert-danger'>Unable to update Coretime.</div>";
                    }

                    $message4 = isset($_GET['delete_coretime']) ? $_GET['delete_coretime'] : "";

                    if($message4=='success'){
                        echo "<div class='alert alert-success'>Coretime Successfully Deleted!</div>";
                    }

                    else if($message4=='failed'){
                      echo "<div class='alert alert-danger'>Unable to delete Coretime.</div>";
                    }
        ?>
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">Core Time</a></li>
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Positions</a></li> -->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <button type="button" data-target="#addCoretime" data-toggle="modal" class="btn btn-primary waves-effect waves-classic">Add Core Time</button>

                                <div class="panel-body container-fluid">
                         <br>
                         <table  class="table dataTable table-striped w-full" data-plugin="dataTable">
                             <thead>
                               <tr>
                                   <th>Name</th>
                                   <th>Time In</th>
                                   <th>Time Out</th>
                                   <th>Action</th>
                               </tr>
                             </thead>
                             <?php
                             // select all data
                             $query = "SELECT * FROM tbl_coretime";
                             $stmt = $con->prepare($query);
                             $stmt->execute();
                             $num = $stmt->rowCount();
                             $output ="";
                             if($num>0){

                               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                 $timeIn = $row['timeIn'];
                                 echo "        <tr>

                                                 <td>" . $row['coretimeName'] . "</td>
                                                   <td>" . date('g:i A', strtotime($row['timeIn'])) . "</td>
                                                   <td>" . date('g:i A', strtotime($row['timeOut'])) . "</td>
                                                 <td class='actions'>
                                   <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
                                      data-original-title='Edit' data-toggle='modal' data-target='#editButton".$row['coreTimeID']."' data-id=" .  $row['coreTimeID'] . " ><i class='icon md-edit' aria-hidden='true'></i></a>
                                   <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
                                      data-original-title='Remove' data-toggle='modal' data-target='#deleteButton".$row['coreTimeID']."' data-id=" .  $row['coreTimeID'] . "><i class='icon md-delete' aria-hidden='true'></i></a>
                                 </td>
                                               </tr>";
                                               ?>
                                               <div class="modal fade" id="editButton<?php echo $row['coreTimeID'];?>">
                                                   <div class="modal-dialog modal-lg">
                                                       <form method="post" action="process_coretime.php">
                                                       <div class="modal-content">
                                                           <div class="modal-header">
                                                             <h3 class="card-title">Edit Core Time</h3>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                 <span aria-hidden="true">&times;</span></button>
                                                             <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                           </div>
                                                           <div class="modal-body">
                                                             <div class="form-group row">
                                                               <label class="col-md-3 col-form-label">Core Time Name: </label>
                                                               <div class="col-md-9">
                                                                  <input type="text" maxlength="25"class="form-control" autocomplete="off" name="coretimeName" value="<?php echo $row['coretimeName'] ?>" required placeholder="<?php echo $row['coretimeName'] ?>">
                                                               </div>
                                                             </div>
                                                             <div class="form-group row">
                                                               <label class="col-md-3 col-form-label">Time In: </label>
                                                               <div class="col-md-9">
                                                                  <input type="text" id="timepicker1" name="timeIn" required placeholder="<?php echo date('g:i A', strtotime($row['timeIn'])) ?>" value="<?php echo date('g:i A', strtotime($row['timeIn'])) ?>" class="timepicker form-control" data-plugin="clockpicker" data-autoclose="true">
                                                               </div>
                                                             </div>
                                                             <div class="form-group row">
                                                               <label class="col-md-3 col-form-label">Time Out: </label>
                                                               <div class="col-md-9">
                                                                    <input type="text" name="timeOut" required placeholder="<?php echo date('g:i A', strtotime($row['timeOut'])) ?>" value="<?php echo date('g:i A', strtotime($row['timeOut'])) ?>" class="timepicker form-control" data-plugin="clockpicker" data-autoclose="true">
                                                               </div>
                                                             </div>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button  type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                              <input type="hidden" name="coreTimeID" value="<?php echo $row['coreTimeID'] ?>">
                                                             <button  type="submit" class="btn btn-primary"name="editCoreTime"><i class="fa fa-check-square-o"></i> Update Core Time</button>
                                                           </form>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                               <div class="modal fade" id="deleteButton<?php echo $row['coreTimeID'];?>">
                                                   <div class="modal-dialog">
                                                       <form method="post" action="process_coretime.php">
                                                       <div class="modal-content">
                                                           <div class="modal-header">
                                                             <h3 class="card-title">Delete Core Time</h3>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                 <span aria-hidden="true">&times;</span></button>
                                                             <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                           </div>
                                                           <div class="modal-body">
                                                             <p>Do you want to delete <b class = "text-primary"><?php echo $row['coretimeName'] ?></b>?</p>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                 <input type="hidden" name="coreTimeID" value="<?php echo $row['coreTimeID'] ?>">
                                                             <button type="submit" class="btn btn-primary"  name="deleteCoreTime"><i class="fa fa-check-square-o"></i> Delete Core Time</button>
                                               </form>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>

                                               <?php

                                     }
                                     echo "</table>";
                                 } else {

                                     echo "<div class='alert alert-error'>No Results Found!</div>";
                                     echo "</table>";
                                 }
                           ?>
                       </div>
                              </div>
                              <div class="tab-pane" id="cardTab2" role="tabpanel">
                              </div>
                              <div class="tab-pane" id="cardTab3" role="tabpanel">
                              </div>
                            </div>
                          </div>
                        </div>

                      <!-- End Panel Floating Labels -->
                    </div>
        </div>
      </div>
    </div>
    <!-- End Page -->


<!-- ADD MODAL -->
<div class="modal fade" id="addCoretime">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Add Core Time</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='process_coretime.php'   autocomplete="off">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Core Time Name: </label>
                            <div class="col-md-9">
                              <input type="text"  class="form-control"  required maxlength="50" onkeypress="return restrictCharacters(this, event, alphanumeric);" autocomplete="off"name="coretimeName">

                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Time In: </label>
                            <div class="col-md-9">
                              <input required name="timeIn"type="text" class="timepicker form-control" data-plugin="clockpicker" data-autoclose="true">

                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Time Out: </label>
                            <div class="col-md-9">
                              <input  required name="timeOut"type="text" class="timepicker form-control" data-plugin="clockpicker" data-autoclose="true">

                            </div>
                          </div>
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary" name="addCoretime"> <i class="fa fa-check-square-o" ></i> Add Core Time</button>
              </form>
            </div>
        </div>
    </div>
</div>
<!-- END ADD MODAL-->

    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>

<script>
$('.clockpicker').clockpicker()
.find('input').change(function(){
 twelvehour: true
});
</script>
  </body>
</html>
