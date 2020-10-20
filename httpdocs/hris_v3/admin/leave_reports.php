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
          <h1 class="page-title"><i>Leave Reports</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">
                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">View Employee Leaves</a></li>
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Leave Entitlement</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label>Employee Name</label>
                                        <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >

                                      <select class="form-control" data-plugin="select2" data-placeholder="Select Here" id="employee"required name="employee" required style="width: 100%;">
                                        <option></option>
                                        <?php
                                          // select all data
                                          $query = "SELECT * FROM tbl_employees";
                                          $stmt = $con->prepare($query);
                                          $stmt->execute();
                                          $num = $stmt->rowCount();
                                          // check if more than 0 record found
                                          if($num>0){
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                              ?>
                                            <option value="<?php echo $row['employeeCode'];?>"><?php echo $row['firstName'] . ' ' . $row['lastName']?></option>
                                            <!-- end of database -->
                                           <?php
                                           }
                                           // if no records found
                                           }else{
                                             ?>
                                               <option>"><?php echo "No Records Found";?></option>
                                             <?php
                                             echo "no records found";
                                           }
                                          ?>

                                      </select>
                                    </div>

                                </div>

                                </div>
                              </div>
                              <div class="tab-pane" id="cardTab2" role="tabpanel">
                                <h4>Protervi dissensio</h4>
                                Protervi dissensio consuetudine equos publicam ingenia. Voluptatibus legendus initia
                                confirmare sententiam. Desistunt possint habeatur dediti dubio,
                                triarium is offendimur reprehenderit exercitus laudabilis motus
                                celeritas, utrum dissentio renovata, habet partus natus. Iustius
                                disserunt, quantum ennii admodum divinum mortem elaborare primum
                                autem.
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
        <div class="row">
          <div class="col-md-12">
            <div class="page-content container-fluid">
              <div class="row">

                          <div class="col-md-12">
                            <!-- Panel Floating Labels -->
                            <div class="panel">
                              <div class="panel-heading">
                                <h3 class="panel-title text-info">Leave History</h3>
                              </div>
                              <div class="panel-body container-fluid">
                                <div class="card-body table-responsive p-0"><br/>
                                <table id='exampleTableTools' class='table table-hover dataTable table-striped w-full example3' >
                                    </table>
                                </div>

                              </div>
                            </div>
                            <!-- End Panel Floating Labels -->
                          </div>
              </div>
            </div>

          </div>



        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="page-content container-fluid">
              <div class="row">
                          <div class="col-md-12">
                            <!-- Panel Floating Labels -->
                            <div class="panel">
                              <div class="panel-heading">
                                <h3 class="panel-title text-info">Leave Count</h3>
                              </div>
                              <div class="panel-body container-fluid">
                                <div class="card-body table-responsive p-0"><br/>
                                  <form  action="leavePdf.php" method="post">
                                <table id='example4'  class="table table-hover dataTable table-striped w-full " >
                                    </table>
                                </div>

                              </div>
                            </div>
                            <!-- End Panel Floating Labels -->
                          </div>
              </div>
            </div>

          </div>



        </div>
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
 <script type="text/javascript">
 $(document).ready(function(){
     $('#employee').on('change',function(){
         var employeeCode = $(this).val();
         if(employeeCode){
             $.ajax({
                 type:'POST',
                 url:'getLeaveView.php',
                 data:'employeeCode='+employeeCode,
                 success:function(html){
                     $('#example4').html(html);

                 }
             });
         }else{
             $('.example3').html('<option value="">Select Employee first</option>');

         }
     });

 });
 </script>
 <script type="text/javascript">
 $(document).ready(function(){
     $('#employee').on('change',function(){
         var employeeCode = $(this).val();
         if(employeeCode){
             $.ajax({
                 type:'POST',
                 url:'getLeaveView2.php',
                 data:'employeeCode='+employeeCode,
                 success:function(html){
                     $('.example3').html(html);

                 }
             });
         }else{
             $('#example4').html('<option value="">Select Employee first</option>');

         }
     });

 });
 </script>

  </body>
</html>
