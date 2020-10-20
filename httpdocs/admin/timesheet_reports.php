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
          <h1 class="page-title"><i>Leave Application</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">
                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">Leave Types</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Leave Entitlement</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li>
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <button type="button" data-target="#exampleFormModal" data-toggle="modal" class="btn btn-primary waves-effect waves-classic">Add Leave Type</button>
                                <br><br><br>
                                <table  class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                  <thead>
                                    <tr>
                                      <th>Rank Name</th>
                                      <th>Rank Description</th>
                                      <th>Members</th>
                                      <th>Created By</th>
                                      <th>Created Date</th>
                                      <th>Updated By</th>
                                      <th>Updated Date</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>Damon</td>
                                      <td>5516 Adolfo Green</td>
                                      <td>Littelhaven</td>
                                      <td>85</td>
                                      <td>Damon</td>
                                      <td>Damon</td>
                                      <td>2014/06/13</td>
                                      <td class="actions">
                        <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-editing save-row"
                          data-toggle="tooltip" data-original-title="Save" hidden><i class="icon md-wrench" aria-hidden="true"></i></a>
                        <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-editing cancel-row"
                          data-toggle="tooltip" data-original-title="Delete" hidden><i class="icon md-close" aria-hidden="true"></i></a>
                        <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-default edit-row"
                          data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit" aria-hidden="true"></i></a>
                        <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row"
                          data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a>
                      </td>
                                    </tr>
                                  </tbody>
                                </table>
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


  </body>
</html>
