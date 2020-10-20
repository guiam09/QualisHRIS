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
          <h1 class="page-title"><i>Employee List</i></h1>
      </div>
      <div class="page-content container-fluid">
        <div class="row">
                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"
                                  aria-controls="cardTab1" role="tab" aria-expanded="true">Employee List</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Per Designation</a></li>
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <table class="table table-hover dataTable table-striped w-full" id="exampleTableTools">
                                    <thead>
                                        <tr>
                                        <th>Photo</th>
                                        <th>Employee Code</th>
                                        <th>Name</th>
                                        <th>Birth Date</th>

                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Core Time</th>
                                        <th>Date Hired</th>
                                        <th>Status</th>
                                        </tr>
                                    </thead>

                                <?php
                                // select all data

                                $session = $_SESSION['user_id'];
                                $query = "SELECT * FROM tbl_employees INNER JOIN tbl_department ON tbl_employees.departmentID = tbl_department.departmentID INNER JOIN
                                tbl_position ON tbl_employees.positionID = tbl_position.positionID INNER JOIN tbl_coretime ON tbl_employees.coreTimeID = tbl_coretime.coreTimeID";
                                $stmt = $con->prepare($query);
                                $stmt->execute();
                                $num = $stmt->rowCount();
                                if($num>0){

                                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    $Image = $row['photo'];

                                    echo "        <tr>


                                                    <td>" . '<img height="90" width="90" src="../Images/'.$Image.'"/>' . "</td>
                                                    <td>" . $row['employeeCode']. "</td>
                                                    <td>" . $row["firstName"] . ' ' . $row['middleName']  .' '. $row["lastName"] . "</td>
                                                    <td>" . date('F d, Y',strtotime($row['birthdate'])) . "</td>

                                                    <td>" . $row['departmentName'] . "</td>
                                                      <td>" .$row['positionName'] . "</td>

                                                    <td>" . $row['timeIn'] . ' ' . $row['timeOut']. "</td>
                                                    <td>" .  date('F d, Y',strtotime($row['dateHired'])) . "</td>
                                                      <td>" .$row['status'] . "</td>


                                                  </tr>";
                                        }
                                        // echo "</table>";
                                    } else {


                                        // echo "</table>";
                                    }
                              ?>
                              </table>
                              </div>
                              <div class="tab-pane" id="cardTab2" role="tabpanel">

                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label>Department</label>
                                          <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >

                                        <select class="form-control select2" id="department"required name="department" required style="width: 100%;">
                                          <option disabled readonly selected value="">Search Department</option>
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
                                              <option value="<?php echo $row['departmentID'];?>"><?php echo $row['departmentName']  ?></option>
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
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label>Position</label>
                                        <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >

                                      <select class="form-control select2" id="position"required name="position" required style="width: 100%;">
                                        <option disabled readonly selected value="">Search Position</option>
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
                                            <option value="<?php echo $row['positionID'];?>"><?php echo $row['positionName'] ?></option>
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
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="page-content container-fluid">
                                        <div class="row">

                                                    <div class="col-md-12">
                                                      <!-- Panel Floating Labels -->
                                                      <div class="panel">
                                                        <div class="panel-heading">
                                                          <h3 class="panel-title text-info">List of Employees</h3>
                                                        </div>
                                                        <div class="panel-body container-fluid">
                                                          <div class="card-body table-responsive p-0"><br/>
                                                            <form  action="leavePdf.php" method="post">


                                                          <table id='example3'  class="table table-hover dataTable table-striped w-full" >


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




    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
 <script type="text/javascript">
 $(document).ready(function(){
     $('#department').on('change',function(){
         var departmentID = $(this).val();
         if(departmentID){
             $.ajax({
                 type:'POST',
                 url:'getEmployeeDesignations.php',
                 data:'departmentID='+departmentID,
                 success:function(html){
                     $('#example3').html(html);

                 }
             });
         }else{
             $('#example3').html('<option value="">Select Department first</option>');

         }
     });

     $('#position').on('change',function(){
         var positionID = $(this).val();

         if(positionID){
             $.ajax({
                 type:'POST',
                 url:'getEmployeeDesignations.php',
                 data:'positionID='+positionID,
                 success:function(html){
                     $('#example3').html(html);

                 }
             });
         }else{
             $('#example3').html('<option value="">Select Department first</option>');

         }
     });

 });
 </script>

  </body>
</html>
