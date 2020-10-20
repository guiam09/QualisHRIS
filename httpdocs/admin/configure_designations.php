<?php
include ('../includes/header.php');
 ?>

<?php

include ('../includes/navbar.php');
include ('../includes/sidebar.php');
?>


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Employee's Designation</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                      <!-- Panel Floating Labels -->
                      <div class="panel">
                        <div class="col-md-7 panel-heading">
                          <h3 class="panel-title text-danger"><button type="button" data-target="#exampleFormModal" data-toggle="modal" class="col-md-2 btn btn-block btn-primary waves-effect waves-classic">Add Designation</button></h3>
                        </div>
                        <div class="panel-body container-fluid">
                          <br>
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
