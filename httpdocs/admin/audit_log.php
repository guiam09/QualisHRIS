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
$CURRENT_PAGE="Audit Log";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
?>



    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Audit Trail</i></h1>

      </div>

      <div class="page-content container-fluid">
        <div class="row">

                    <div class="col-md-12">
                        <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                          <div class="panel-heading p-30 pb-0 pt-10">
                            <ul class="nav nav-pills" role="tablist">
                              <!--<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#cardTab1"-->
                              <!--    aria-controls="cardTab1" role="tab" aria-expanded="true">Core Time</a></li>-->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab2" aria-controls="cardTab2"
                                  role="tab">Positions</a></li> -->
                              <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cardTab3" aria-controls="cardTab3"
                                  role="tab">tab2</a></li> -->
                            </ul>
                          </div>
                          <div class="panel-body">
                            <div class="tab-content">
                              <div class="tab-pane active" id="cardTab1" role="tabpanel">
                                <div class="panel-body container-fluid">
                             <table class="table table-hover table-striped w-full" id="example">
                             <thead>
                               <tr>
                                   <th>Module Name</th>
                                   <th>Sub-Module</th>
                                   <th>Old Data</th>
                                   <th>New Data</th>
                                   <th>Action</th>
                                   <th>Modified By</th>
                                   <th>Date Modified</th>
                               </tr>
                             </thead>
                             <?php
                             // select all data
                             $query = "SELECT * FROM tbl_auditLogs JOIN tbl_employees ON tbl_auditLogs.modified_by_id = tbl_employees.employeeID ORDER BY modified_timestamp DESC";
                             $stmt = $con->prepare($query);
                             $stmt->execute();
                             $num = $stmt->rowCount();
                             if($num>0){

                               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            
                                 echo "        <tr>

                                                   <td>" . $row['module'] . "</td>
                                                   <td>" . $row['sub_module'] . "</td>
                                                   <td>" . $row['old_data'] . "</td>
                                                   <td>" . $row['new_data'] . "</td>
                                                   <td>" . $row['action'] . "</td>
                                                   <td>" . $row['firstName'] . ' ' . $row['lastName']. "</td>
                                                   <td>" . date('F d, Y g:i a', strtotime($row['modified_timestamp'])) . "</td>
                                               </tr>";
     

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
<!-- END ADD MODAL-->

    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
<script>https://code.jquery.com/jquery-3.3.1.js</script>
<script>https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js</script>
<script>https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js</script>
<script>https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js</script>
<script>https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js</script>
<script>https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js</script>
<script>https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js</script>



<script>
$('.clockpicker').clockpicker()
.find('input').change(function(){
 twelvehour: true
});

$(document).ready(function() {
    $('#example').DataTable( {
        "order": [],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
  </body>
</html>
