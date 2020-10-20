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
$CURRENT_PAGE="Leave Reports";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
?>
    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>View Employee Leaves</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">
                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                 <div class="panel panel-bordered">
                                <div class="panel-heading">
                                  <h3 class="panel-title">Search</h3>
                                </div>
                                <form method="GET" action="#">
                                <div class="panel-body">
                                           <div class="form-group row col-md-6">
                                  <label class="col-form-label col-md-4">Search Employee</label>
                                  <div class="col-md-8">
                                      <div class="input-group">
                                      <select class="form-control"  id="employee" name="employee"  style="width: 100%;">
                                        <option <?php 
                                        if(isset($_GET['search'])){
                                            $row = getEmployeeData($con, $_GET['employee']);
                                            echo "value='".$row['firstName']. ' '. $row['lastName']."'";
                                        }
                                        
                                        ?>></option>
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
                                <div class="form-group row col-md-6">
                                  <label class="col-form-label col-md-4">Leave Type</label>
                                  <div class="col-md-8">
                                      <div class="input-group">
                                      <select class="form-control"  id="leaveType" name="leaveType"  style="width: 100%;">
                                        <option></option>
                                        <?php
                                          // select all data
                                          $query = "SELECT * FROM tbl_leave";
                                          $stmt = $con->prepare($query);
                                          $stmt->execute();
                                          $num = $stmt->rowCount();
                                          // check if more than 0 record found
                                          if($num>0){
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                              ?>
                                            <option value="<?php echo $row['leaveID'];?>"><?php echo $row['leaveName'] ?></option>
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
                                             <div class="form-group row col-md-6">
                                  <label class="col-form-label col-md-4">Leave Date Range</label>
                                  <div class="col-md-8">
                                      <div class="input-daterange" id="datepicker"data-plugin="datepicker" style="position: relative; z-index: 100000 !important;">
                                        <div class="input-group">
                                          <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                          </span>
                                          <input type="text" class="form-control" id="from" name="from" style="position: relative; z-index: 100000 !important;"/>
                                        </div>
                                        <div class="input-group">
                                          <span class="input-group-addon">to</span>
                                          <input type="text" class="form-control" id="to" name="to" style="position: relative; z-index: 100000 !important;"/>
                                        </div>
                                      </div>
                                  </div>
                                 
                                </div>
                               <div class="form-group row col-md-6">
                                  <div class="col-md-9">
                                    <button type="submit" name="search" class="btn btn-info">Search </button>
                                    <button type="reset" class="btn btn-default btn-outline">Reset</button>
                                  </div>
                                </div>
                                 </form>
                                </div>
                              </div>
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
                                    <?php
                                    if (isset($_GET['search'])){
                                            $q = "";
    $emp = $_GET['employee'];
    $lt = $_GET['leaveType'];
    $from = $_GET['from'];
    $to = $_GET['to'];
    
             // converting one date format into another
    if(!empty($_GET['employee'])){
        $q .= " tbl_leavedetails.employeeID='$emp' ";
        
        if(!empty($_GET['leaveType'])){
             $q .= " AND tbl_leavedetails.leaveID='$lt'";
             
             if(!empty($_GET['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }else{
                 
             }
        }else{
            if(!empty($_GET['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }
            
        }
    }else{
        if(!empty($_GET['leaveType'])){
             $q .= " tbl_leavedetails.leaveID='$lt'";
             
             if(!empty($_GET['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }
        }else{
            if(!empty($_GET['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " leaveFrom >= '$from' AND leaveTo <= '$to' "; 
            }
        }
    }


    // select all data
    $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leave ON tbl_leavedetails.leaveID = tbl_leave.leaveID JOIN tbl_employees ON tbl_leavedetails.employeeID = tbl_employees.employeeID WHERE $q ";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo "    <thead>
          <tr>
              <th>Name</th>
              <th>Date Filed</th>
              <th>Leave Type</th>

              <th>Duration</th>
              <th>From</th>
              <th>To</th>
                <th>Reason</th>
              <th>Status</th>
          </tr>
      </thead>";
    if($num>0){

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "        <tr>
                          <td>" . $row['firstName'] . ' ' . $row['lastName']. "</td>
                        <td>" . date('F d, Y',strtotime($row['dateFiled'])) . "</td>
                          <td>" . $row['leaveName'] . "</td>
                            <td>" . $row['duration'] . ' Days' . "</td>
                        <td>" . date('F d, Y',strtotime($row["leaveFrom"])) . "</td>
                        <td>" . date('F d, Y',strtotime($row["leaveTo"]))  . "</td>

                        <td>" . $row['reason'] . "</td>
                        <td>" . $row["leaveStatus"] . "</td>
                      </tr>
                         ";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
       // echo "no records found";
     }


                                    }
                                    ?>
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
                                 <table id='' class='table table-hover dataTable table-striped w-full example3' >
                                    <?php
                                    if (isset($_GET['search'])){
                                      
    $emp = $_GET['employee'];
   

    // select all data
    $query = "SELECT * FROM tbl_leaveinfo JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID WHERE employeeID = $emp ";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo "    <thead>
          <tr>
              <th>Leave Name</th>
              <th>Leave Credits</th>
              <th>Leave Used</th>
              <th>Leave Balance</th>
          </tr>
      </thead>";
    if($num>0){

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "        <tr>
                          <td>" . $row['leaveName'] . "</td>
                          <td>" . $row['allowedLeave'] . "</td>
                          <td>" . $row['leaveUsed'] . "</td>
                          <td>" . $row['allowedLeave'] . "</td>
                      </tr>
                         ";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
       // echo "no records found";
     }


                                    }
                                    ?>
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
//  $(document).ready(function(){
//      $('#employee').on('change',function(){
//          var employeeCode = $(this).val();
//          if(employeeCode){
//              $.ajax({
//                  type:'POST',
//                  url:'getLeaveView.php',
//                  data:'employeeCode='+employeeCode,
//                  success:function(html){
//                      $('#example4').html(html);

//                  }
//              });
//          }else{
//              $('.example3').html('<option value="">Select Employee first</option>');

//          }
//      });

//  });
 </script>
 <script type="text/javascript">
//  $(document).ready(function(){
//      $('#employee').on('change',function(){
//          var employee = $('#employee').val();
//          var leaveType = $('#leaveType').val();
//          var from = $('#from').val();
//          var to = $('#to').val();
//          alert(leaveType);
//              $.ajax({
//                  type:'POST',
//                  url:'getLeaveView2.php',
//                  data:{employee: employee, leaveType: leaveType, from: from, to: to },
//                  success:function(html){
//                      $('.example3').html(html);

//                  }
//              });
      
//      });

//  });
 </script>

  </body>
</html>
