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
          <h1 class="page-title"><i>Setting Access Levels</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">Access Level</a></li>
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Leave Entitlement</a></li> -->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <?php
                                //ADD LEAVE
                                $message = isset($_GET['add_access_level']) ? $_GET['add_access_level'] : "";

                                if($message=='success'){
                                    echo "<div class='alert alert-success'>Access Level Successfully Added!</div>";
                                }

                                else if($message=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to add Access Level.</div>";
                                }

                                //EDIT Leave

                                $message2 = isset($_GET['edit_access_level']) ? $_GET['edit_access_level'] : "";

                                if($message2=='success'){
                                    echo "<div class='alert alert-success'>Access Level Successfully Updated!</div>";
                                }

                                else if($message2=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to update Access Level.</div>";
                                }


                                //DELETE LEAVE
                                $message3 = isset($_GET['delete_access_level']) ? $_GET['delete_access_level'] : "";

                                if($message3=='success'){
                                    echo "<div class='alert alert-success'>Access Level Deleted!</div>";
                                }

                                else if($message3=='failed'){
                                  echo "<div class='alert alert-danger'>Unable to Access Level.</div>";
                                }

                                ?>
                                <button type="button" data-target="#addAccessLevel" data-toggle="modal" class="btn btn-primary waves-effect waves-classic">Add Access Level</button>
                                <br><br><br>
                                <table  class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                  <thead>
                                    <tr>
                                      <th>Access Level</th>
                                      <th>Members</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      $stmt = getAccessLevels($con);
                                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        echo "        <tr>

                                                        <td>" . $row["accessLevel"] . "</td>
                                                        <td>" . $row["Members"] . "</td>
                                                        <td class='actions'>
                                          <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default edit-row'
                                             data-original-title='Edit' data-toggle='modal' data-target='#editButton".$row['accessLevelID']."' data-id=" .  $row['accessLevelID'] . " ><i class='icon md-edit' aria-hidden='true'></i></a>
                                          <a href='#' class='btn btn-sm btn-icon btn-pure btn-default on-default remove-row'
                                             data-original-title='Remove' data-toggle='modal' data-target='#deleteButton".$row['accessLevelID']."' data-id=" .  $row['accessLevelID'] . "><i class='icon md-delete' aria-hidden='true'></i></a>
                                        </td>
                                                      </tr>";

                                                      ?>
                                                      <div class="modal fade" id="editButton<?php echo $row['accessLevelID'];?>">
                                                          <div class="modal-dialog modal-lg">
                                                              <form method="post" action="process_leave_configuration.php" autocomplete="off">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Edit Leave Type</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Name: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control"  maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="accessLevel"
                                                                        value="<?php echo $row['accessLevel'] ?>" required placeholder="<?php echo $row['accessLevel'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                      <legend class="col-md-3 col-form-legend">Required: </legend>
                                                                      <div class="col-md-9">
                                                                        <div class="radio-custom radio-default radio-inline">
                                                                          <input type="radio" id="inputHorizontalMale" name="required" value="1" <?php echo ($row['required'] =='1')? 'checked':'' ?>/>
                                                                          <label for="inputHorizontalMale">Yes</label>
                                                                        </div>
                                                                        <div class="radio-custom radio-default radio-inline">
                                                                          <input type="radio" id="inputHorizontalFemale" name="required" value="0" <?php echo ($row['required'] =='0')? 'checked':'' ?>
                                                                          />
                                                                          <label for="inputHorizontalFemale">No</label>
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                      <label class="col-md-3 col-form-label">Leave Count: </label>
                                                                      <div class="col-md-9">
                                                                        <input type="text" class="form-control" required  maxlength="10" onkeypress="return restrictCharacters(this, event, digitsOnly);" autocomplete="off"name="leaveCount"
                                                                          value="<?php echo $row['leaveCount'] ?>" required placeholder="<?php echo $row['leaveCount'] ?>"/>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                      <input type="hidden" name="accessLevelID" value="<?php echo $row['accessLevelID'] ?>">
                                                                    <button type="submit" class="btn btn-primary"  name="editLeave"><i class="fa fa-check-square-o"></i> Update Leave Type</button>
                                                                  </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="modal fade" id="deleteButton<?php echo $row['accessLevelID'];?>">
                                                          <div class="modal-dialog">
                                                              <form method="post" action="process_leave_configuration.php">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <h3 class="card-title">Delete Leave Type</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                    <p>Do you want to delete <b class = "text-primary"><?php echo $row['accessLevel'] ?></b> Leave?</p>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <input type="hidden" name="accessLevelID" value="<?php echo $row['accessLevelID'] ?>">
                                                                    <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                                    <button type="submit" class="btn btn-primary"  name="deleteLeave"> <i class="fa fa-check-square-o" ></i> Delete Leave Type</button>
                                                      </form>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <?php
                                                    }
                                     ?>

                                  </tbody>
                                </table>
                              </div>
                              <div class="tab-pane" id="cardTab2" role="tabpanel">

                              </div>
                              <div class="tab-pane" id="cardTab3" role="tabpanel">
                                <h4>Incurrunt latinam</h4>
                                Incurrunt latinam, faciendi dedecora evertitur delicatissimi, afficit noctesque
                                detracta illustriora epicurum contenta rogatiuncula dolores
                                perspecta indocti, eveniunt confirmatur tractat consuevit durissimis
                                iuvaret coercendi familiarem. Dolere prima fortunae intellegamus
                                vix porro huic errorem molestum, graecos deinde effugiendorum
                                aliter appetendum afferrent eosdem.
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

<div class="modal fade" id="addAccessLevel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Add Access Level</h4>
          </div>
            <div class="modal-body">
              <form enctype = "multipart/form-data" method="post" action='process_access_level.php'  autocomplete="off">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Access Level Name: </label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" required maxlength="50" onkeypress="return restrictCharacters(this, event, alphaOnly);" autocomplete="off" name="accessLevel"
                              />
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Modules Accessed: </label>
                            <div class="col-md-9">
                              <select class="form-control" data-plugin="select2"  multiple name="coreTime" data-placeholder="Select Here">
                                <option></option>
                                <option value="CA">California</option>
                      <option value="NV">Nevada</option>
                      <option value="OR">Oregon</option>
                      <option value="WA">Washington</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Leave Count: </label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" required    autocomplete="off" name="leaveCount"
                              />
                            </div>
                          </div>
            </div>
            <div class="modal-footer">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary"  name="addLeave"> <i class="fa fa-check-square-o" ></i> Add Leave Type</button>
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

 <!-- <script>
     function deleteDetails(button){
                         // alert("Call me");
                         swal({
                             title: "Are you sure?",
                             text: "Once Deleted, you will not be able to recover this",
                             icon: "warning",
                             buttons: true,
                             dangerMode: true,

                         })
                         .then((willDelete) => {
                             if (willDelete){
                                 swal({
                                     title: "Leave Type Successfully Deleted!",
                                     text: " ",
                                     icon: "success",
                                     button: false,
                                     timer: 1000
                                 })

                         .then(function() {
                             // setTimeout(function(){ window.location.reload() }, 2000);
                              window.location.reload();
                          });
                             } else{
                                 swal("Action Cancelled" ,"", "info");
                             }
                         })
                     }
                     function editDetails(button){
                         swal({
                                     title: "Leave Type Updated!",
                                     text: " ",
                                     icon: "success",
                                     button: false,
                                     timer: 1000
                                 })
                                 .then(function() {
                             window.location.reload();
                          });
                     }
                     function addDetails(button){
                       var form = this;
                         swal({
                                     title: "Leave Type Added!",
                                     text: " ",
                                     icon: "success",
                                     button: false,
                                     timer: 1000
                                 })
                                 .then(function() {
                               setTimeout(function(){form.submit(); }, 2000);

                          });
                     }
 </script> -->
<!-- <script>
$(document).on('submit', '[id=submitLeave]' , function(e) {
e.preventDefault();
swal({
                title: "Leave Type Added!",
                text: " ",
                icon: "success",
                button: false,
                timer: 1000
            })
            .then(function() {
              $.ajax({
                  type: 'POST',
                  url: 'process_leave_configuration.php',
                  data: data,
                });
     });
return false
});
</script> -->

<script>
  $( document ).ready(function() {
    e.preventDefault();

  });
</script>
<script>
$(document).on('click', '#submit', function(e) {
    e.preventDefault();
    swal({
                title: "Leave Type Added!",
                text: " ",
                icon: "success",
                button: false,
                timer: 1000
            })
            .then(function() {
          $('#submitLeave').submit();

     });
}
});
</script>
  </body>
</html>
