<?php
include_once ('../includes/configuration.php');

include ('../db/connection.php');
include ('../includes/fetchData.php');

// include login checker
$page_title="Admin";
$access_type ="Admin";

// include login checker
$require_login=true;
include_once "../includes/loginChecker.php";

include ('../includes/header.php');
include ('../includes/navbar.php');
$CURRENT_PAGE="Profile";
include ('../includes/sidebar.php');

  $getData = getEmployeeData($con, $_SESSION['user_id']);
?>

    <!-- Page -->
    <div class="page">
      <div class="page-content container-fluid">
        <div class="row">
          <div class="col-md-12">
              <div class="float-left mr-40">
                <div class="example">
                  <img class="img-rounded img-bordered-primary" width="150" height="140"
                   src="../images/<?php echo $getData['photo'] ?>" alt="...">
                </div>
              </div>
              <div class="float-left">
                <h2 class="person-name">
                  <a><?php echo $getData['firstName'].' '.$getData['lastName']; ?></a>
                </h2>
                <p class="card-text">
                  <!--<a class="blue-grey-400 font-size-20"><?php echo $getData['positionName'] ?></a>

                  <br> -->
                    <a class="blue-grey-400 font-size-16"><i><?php echo $getData['emailAddress'] ?></i></a> 

                  
                  <!-- July 12, 2019 UPDATED: Remove Address -->
                  
                  <!--<br>-->
                  <!--  <a class="blue-grey-400 font-size-14"><i>
                  <?php //echo $getData['address'] ?>
                  </i></a>-->
                  
                  <!-- End July 12, 2019 UPDATED: Remove Address -->
                  
                </p>
                <p class="card-text">
                </p>
              </div>
              
              <!-- June 12, 2019 UPDATED: Remove account settings button on profile -->
              
              <!--<div class="float-right">-->
              <!--    <a href="account_settings.php"><button type="button"  class="btn btn-block btn-info waves-effect waves-classic">Account Settings</button></a>-->
              <!--</div>-->
              
              <!-- End June 12, 2019 UPDATED: Remove account settings button on profile -->
              
          </div>
        </div>
        <div class="row">
          <!-- Employment Details Panel -->
          <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                  <h3 class="panel-title">Employment Info</h3>
                  <div class="panel-actions">
                    <!--<button type="button" data-target="#editEmployementDetails" data-toggle="modal" class="btn btn-block btn-primary waves-effect waves-classic">Edit</button>-->
                  </div>
              </div>
              <table class="table">
                    <tbody>
                      <tr>
                        <td class="font-size-18">Date Hired</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['dateHired']; ?></b></td>
                      </tr>

                      <tr>
                        <td class="font-size-18">Department</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['departmentName']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Position</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['positionName']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Address</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['address']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Reports To</td>
                        <td colspan="3"class="text-right font-size-18"><b>  <?php
                        $reportingToName = $getData['reportingTo'];
                        $query2 = "SELECT * FROM tbl_employees WHERE employeeID = '$reportingToName'";
                        $stmt7 = $con->prepare($query2);
                        $stmt7->execute();
                        $rows = $stmt7->fetch(PDO::FETCH_ASSOC);
                        echo $rows['firstName'] . ' ' . $rows['lastName']
                         ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Tel. No.</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['contactInfo']; ?></b></td>
                      </tr>

                      <tr>
                        <td class="font-size-18">Civil Status</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['civilStatus']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Gender</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['gender']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Birth Date</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['birthdate']; ?></b></td>
                      </tr>


                    </tbody>
                  </table>
            </div>
          </div>
          <!-- Identification Cards Panel -->
          <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                  <h3 class="panel-title">ID's</h3>
                  <div class="panel-actions">
                    <!--<button type="button" data-target="#editID" data-toggle="modal" class="btn btn-block btn-primary waves-effect waves-classic">Edit</button>-->
                  </div>
              </div>
              <table class="table">
                    <tbody>
                      <tr>
                        <td class="font-size-18">Employee ID</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['employeeCode']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">SSS ID</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['sssID']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">PhilHealth ID</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['philhealthID']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">PAGIBIG ID</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['pagibigID']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">TIN ID</td>
                          <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['tinID']; ?></b></td>
                      </tr>
                    </tbody>
                  </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                  <!--<h3 class="panel-title">Joining Details</h3> -->
                  <div class="panel-actions">
                    <!-- <button type="button" data-target="#exampleFormModal" data-toggle="modal" class="btn btn-block btn-primary waves-effect waves-classic">Edit</button> -->
                  </div>
              </div>
              <table class="table">
                    <!--<tbody>
                      <tr>
                        <td class="font-size-18">Date Hired</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['dateHired']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Status</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['status']; ?></b></td>
                      </tr> 
                    </tbody> -->
                  </table>
            </div>
          </div>
          <!-- <div class="col-md-6">
            <div class="panel panel-bordered">
              <div class="panel-heading">
                  <h3 class="panel-title">Status</h3>
                  <div class="panel-actions">

                  </div>
              </div>
              <table class="table">
                    <tbody>
                      <tr>
                        <td class="font-size-18">Date Hired</td>
                        <td colspan="3"class="text-right  font-size-18"><b><?php echo $getData['dateHired']; ?></b></td>
                      </tr>
                      <tr>
                        <td class="font-size-18">Status</td>
                        <td colspan="3"class="text-right font-size-18"><b><?php echo $getData['status']; ?></b></td>
                      </tr>
                    </tbody>
                  </table>
            </div>
          </div> -->
        </div>
      </div>
    </div>
    <!-- End Page -->
    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>




        <div class="modal fade"  id="editEmployementDetails">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Update Employement Info</h4>
                  </div>
                    <div class="modal-body">
                      <form enctype = "multipart/form-data" method="post" action='process_edit_profile.php'  autocomplete="off">
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Department: </label>
                                    <div class="col-md-9">
                                      <select  name="department"  required data-plugin="select2" class="form-control" style="width: 100%;">
                                        <option selected value="<?php echo $getData['departmentName']; ?>"><?php echo $getData['departmentName']; ?></option>
                                        <?php
                                          // select all data
                                          $query = "SELECT * FROM tbl_department";
                                          $stmt = $con->prepare($query);
                                          $stmt->execute();
                                          $num = $stmt->rowCount();
                                          // check if more than 0 record found
                                          if($num>0){
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                              ?>
                                            <option value="<?php echo $row['departmentID'];?>"><?php echo $row['departmentName'];?></option>
                                            <!-- end of database -->
                                           <?php
                                           }
                                           // if no records found
                                           }else{
                                             echo "no records found";
                                           }
                                          ?>
                                      </select>

                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Position: </label>
                                    <div class="col-md-9">
                                        <select  name="position"  required data-plugin="select2" class="form-control" style="width: 100%;">
                                          <option selected value="<?php echo $getData['positionName']; ?>"><?php echo $getData['positionName']; ?></option>
                                          <?php
                                            // select all data
                                            $query = "SELECT * FROM tbl_position";
                                            $stmt = $con->prepare($query);
                                            $stmt->execute();
                                            $num = $stmt->rowCount();
                                            // check if more than 0 record found
                                            if($num>0){
                                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                              <option value="<?php echo $row['positionID'];?>"><?php echo $row['positionName'];?></option>
                                              <!-- end of database -->
                                             <?php
                                             }
                                             // if no records found
                                             }else{
                                               echo "no records found";
                                             }
                                            ?>
                                      </select>

                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Location: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="edit_contact" value="<?php echo $getData['address']; ?>" name="location">

                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Reports To: </label>
                                    <div class="col-md-9">
                                      <select  name="reportingTo"  required data-plugin="select2" class="form-control" style="width: 100%;">
                                        <option value="<?php echo $getData['reportingTo']; ?>"><p class="text-muted"><?php echo $getData['firstName'] . ' ' . $getData['lastName'] ?></p></option>
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
                                            <option value="<?php echo $row['employeeID'];?>"><?php echo $row['firstName'] . ' ' . $row['lastName']?></option>
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
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                      <button type="submit" class="btn btn-primary" name="edit_currentPosition"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade"  id="editID">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Update Identification Cards</h4>
                  </div>
                    <div class="modal-body">
                      <form enctype = "multipart/form-data" method="post" action='process_edit_profile.php'  autocomplete="off">
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Employee ID: </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="edit_contact" readonly name="employeeID" value="<?php echo $getData['employeeCode']; ?>">

                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">SSS ID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="sssID" value="<?php echo $getData['sssID'];; ?>">-->
                                    <input type="text" class="form-control" id="sssID"
                                      placeholder="12-1234567-1" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[99]]-[[9999999]]-[[9]]" name="sssID" value="<?php echo $getData['sssID'];; ?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">PhilHealthID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="philhealthID" value="<?php echo $getData['philhealthID']; ?>">-->
                                        <input type="text" class="form-control" id="inputBasicLastName" 
                                      placeholder="123-123-123-123"  autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[9999]]-[[9999]]-[[9999]]" name="philhealthID" value="<?php echo $getData['philhealthID']; ?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">PAGIBIG ID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="pagibigID" value="<?php echo $getData['pagibigID']; ?>">-->
                                    <input type="text" class="form-control" id="inputCredit" data-plugin="formatter"
                      data-pattern="[[9999]]-[[9999]]-[[9999]]" 
                                      placeholder="1234-1234-1234-1234" autocomplete="off" name="pagibigID" value="<?php echo $getData['pagibigID']; ?>">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">TIN ID: </label>
                                    <div class="col-md-9">
                                        <!--<input type="text" class="form-control" id="edit_contact" autocomplete="off" data-inputmask='"mask": "99-9999999-9"' data-mask  required name="tinID" value="<?php echo $getData['tinID']; ?>">-->
                                     <input type="text" class="form-control" id="inputBasicLastName" 
                                      placeholder="123-123-123-123" autocomplete="off"  id="inputCredit" data-plugin="formatter"
                        data-pattern="[[999]]-[[999]]-[[999]]-[[999]]" name="tinID" value="<?php echo $getData['tinID']; ?>">
                                    </div>
                                  </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                      <button type="submit" class="btn btn-primary" name="editID"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade"  id="updateImage">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Update Picture</h4>
                  </div>
                    <div class="modal-body">
                      <form enctype = "multipart/form-data" method="post" action='process_edit_profile.php'  autocomplete="off">
                                  <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Update Picture: </label>
                                    <div class="col-md-9">
                                        <input required type = "file" name = "Image" />

                                    </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                      <button type="submit" class="btn btn-primary" name="updateImage"> <i class="fa fa-check-square-o" ></i> Update</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>


  </body>
</html>


