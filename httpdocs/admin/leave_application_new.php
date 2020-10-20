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
$CURRENT_PAGE="Leave Application";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');

// functions
function generateLeaveDropdownList($con){
    $leaveValues = '';
    $query_leaveType = "SELECT * FROM tbl_leave";
    $stmt_leaveType = $con->prepare($query_leaveType);
    $stmt_leaveType->execute();
    $resultLeaveValues = $stmt_leaveType->fetchAll();
    
    foreach($resultLeaveValues as $rowLeaves)
    {
        $selectedLeave = $rowLeaves["leaveID"];
        $leaveOutput .= '<option value="' . $rowLeaves["leaveID"] .'">'.$rowLeaves["leaveName"].'</option>';
    }
    return $leaveOutput;
}



?>
<style>
.modal{
    z-index:9999;
}

.select2-container {
  z-index: 9999;
}

.select2-container--open{
z-index:999999
}

</style>
<!-- Page -->
    <div class="page">
        <div class="page-header">
            <h1 class="page-title"><i>Filing & Monitoring</i></h1>
        </div>
        <!-- Leave navigation tabs -->
        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item" style="margin-left:10px">
                <a class="nav-link active" id="custom-content-below-leaveApproval-tab" data-toggle="pill" href="#custom-content-below-leaveApproval" role="tab" aria-controls="custom-content-below-leaveApproval" aria-selected="true">Leave Approval</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-below-leaveCount-tab" data-toggle="pill" href="#custom-content-below-leaveCount" role="tab" aria-controls="custom-content-below-leaveCount" aria-selected="false">Leave Count</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-below-leaveConfiguration-tab" data-toggle="pill" href="#custom-content-below-leaveConfiguration" role="tab" aria-controls="custom-content-below-leaveConfiguration" aria-selected="false">Leave Configuration</a>
            </li>
        </ul>
        <!-- end Leave navigation tabs -->
        
        <div class="tab-content" id="custom-content-below-tabContent">
            <!-- Leave Approval Tab -->
            <div class="tab-pane fade active show" id="custom-content-below-leaveApproval" role="tabpanel" aria-labelledby="custom-content-below-leaveApproval-tab">
                <div class="page-content container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-danger" id="displayResult"></h3>
                                </div>
                                <div class="panel-body container-fluid">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <button type="button"  data-toggle="modal" data-target="#applyLeave" class="btn btn-block btn-info ">File Leave</button>
                                        </div>
                                    </div>
                                    <br />
                                    <form id="populateLeaveTable">
                                        <div class="col-md-12">
                                        <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">Employee</label>
                                                        <?php include ('searchEmployeeList.php');?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">Range</label><br/>
                                                        <select class="form-control col-md-3" data-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Select Range" tab-index="-1" width="auto" onchange="showDateRange(this.value)" />
                                                            <option value=""></option>
                                                            <option value="currentWeek">Current Week</option>
                                                            <option value="lastWeek">Last Week</option>
                                                            <option value="lastTwoWeeks">Last 2 Weeks</option>
                                                            <option value="lastMonth">Last Month</option>
                                                            <option value="custom">Custom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id='fromDateGroup' style=display:none>
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">From</label>
                                                        <div class="col-md-12 input-group input-daterange" id="fromleavedatepicker" data-plugin="datepicker">
                                                            <input type="text" name="leave_from_date" id="leave_from_date" class="form-control datepicker" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id='toDateGroup' style=display:none>
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">To</label>
                                                        <div class="col-md-12 input-group input-daterange" id="toleavedatepicker" data-plugin="datepicker">
                                                            <input type="text" name="leave_to_date" id="leave_to_date" class="form-control datepicker" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="col-md-1">-->
                                                <!--    <div class="col align-self-end">-->
                                                <!--        <div class="form-group form-material col-lg-2">-->

                                                <!--            <button type="submit" class="btn btn-primary" name="search" id="search"><i class="fas fa-search"></i> Search </button>-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-1">-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </form>
                                    <br />
                                    <div class="row">
                                        <table  id="leaveTable" class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Date Filed</th>
                                                    <th>Leave Type</th>
                                                    <th>Leave Date</th>
                                                    <th>Duration</th>
                                                    <th>Status</th>
                                                    <th>Date Approved</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                // <?php
                                                //     $query_leaveList = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_employees ON tbl_leavedetails.employeeID = tbl_employees.employeeID";
                                                //     $stmt_leaveList = $con->prepare($query_leaveList);
                                                //     if($stmt_leaveList->execute()){
                                                //         while($row_leaveList=$stmt_leaveList->fetch(PDO::FETCH_ASSOC)){
                                                //             echo "
                                                //             <tr>
                                                //                 <td>". $row_leaveList['lastName'] ." ". $row_leaveList['firstName'] ."</td>
                                                //                 <td>". $row_leaveList['dateFiled'] ."</td>
                                                //                 <td>". $row_leaveList['leaveID'] ."</td>
                                                //                 <td>". $row_leaveList['leaveFrom'] ."</td>
                                                //                 <td>". $row_leaveList['duration'] ."</td>
                                                //                 <td>". $row_leaveList['leaveStatus'] ."</td>
                                                //                 <td>". $row_leaveList['dateApproved'] ."</td>
                                                //                 <td>
                                                //                     <a href='#'><i class='fas fa-book' style='color:grey'></i></a>
                                                //                     <button type='button'>Review</button>
                                                //                     <button type='button'>View / Edit</button>
                                                //                     <button type='button'>Cancel</button>
                                                //                 </td>
                                                //             </tr>    
                                                //             ";
                                                //         }
                                                //     }else{
                                                //         echo "No Records Found.";
                                                //     }
                                                // ?>
                                                <!--version2 -->
                                                <?php
                                                    $query_leaveList = "SELECT * FROM tbl_leaveGroup INNER JOIN tbl_employees ON tbl_leaveGroup.employeeID = tbl_employees.employeeID INNER JOIN tbl_leavedetails ON tbl_leaveGroup.leaveGroup_ID = tbl_leavedetails.leaveGroup_ID";
                                                    $stmt_leaveList = $con->prepare($query_leaveList);
                                                    if($stmt_leaveList->execute()){
                                                        while($row_leaveList=$stmt_leaveList->fetch(PDO::FETCH_ASSOC)){
                                                            echo "
                                                            <tr>
                                                                <td>". $row_leaveList['lastName'] .", ". $row_leaveList['firstName'] ."</td>
                                                                <td>". $row_leaveList['leaveGroup_dateFiled'] ."</td>
                                                                <td>";
                                                                $leaveGroupID=$row_leaveList['leaveGroup_ID'];
                                                                $query_leaveData = "SELECT * FROM tbl_leavedetails WHERE leaveGroup_ID='$leaveGroupID'";
                                                                $stmt_leaveData = $con->prepare($query_leaveData);
                                                                $stmt_leaveData->execute();
                                                                while($row_leaveData=$stmt_leaveData->fetch(PDO::FETCH_ASSOC)){
                                                                    echo $row_leaveData." <br/>";
                                                                }
                                                                
                                                            echo    
                                                                "</td>
                                                                <td>". $row_leaveList['leaveFrom'] ."</td>
                                                                <td>". $row_leaveList['leaveGroup_duration'] ."</td>
                                                                <td>". $row_leaveList['leaveGroup_status'] ."</td>";
                                                                
                                                                if(isset($row_leaveList['dateApproved'])){
                                                                    echo "<td>". $row_leaveList['dateApproved'] ."</td>";
                                                                }else{
                                                                    echo "<td>----------------------</td>";
                                                                }
                                                                echo
                                                                "<td>
                                                                    <a href='#'><i class='fas fa-book' style='color:grey'></i></a>
                                                                    <button type='button' id='review". $row_leaveList['employeeCode'].$row_leaveList['dateFiled'] ."'>Review</button>
                                                                    <button type='button'>View / Edit</button>
                                                                    <button type='button'>Cancel</button>
                                                                </td>
                                                            </tr>    
                                                            ";
                                                        }
                                                    }else{
                                                        echo "No Records Found.";
                                                    }
                                                ?>
                                                <!-- END version 2 -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Leave Approval Tab -->
            
            <!-- Leave View Tab -->
            <div class="tab-pane fade active show" id="custom-content-below-leaveCount" role="tabpanel" aria-labelledby="custom-content-below-leaveCount-tab">
                <div class="page-content container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <!---->
                            <div class="panel">
                                <div class="panel-body container-fluid">
                                    <table id='example3' class='table table-hover table-striped'>
                                        <thead>
                                            <tr>
                                                <th>Leave Type</th>
                                                <th>Leave Credits</th>
                                                <th>Used</th>
                                                <th>Leave Balance</th>
                                            </tr>
                                        </thead>
                                            <?php
                                                // select all data
                                                $user = $_SESSION['employeeID'];
                                                $query = "SELECT * FROM tbl_leaveinfo INNER JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID  WHERE employeeID = '$user' ";
                                                $stmt = $con->prepare($query);
                                                $stmt->execute();
                                                $num = $stmt->rowCount();
                                                if($num>0){
            
                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
                                                        echo " <tr>
                                                                <td>" . $row['leaveName'] . "</td>
                                                                <td>" . $row["leaveCount"] .  "</td>
                                                                <td>" . $row["leaveUsed"] . "</td>
                                                                <td>" . $row["leaveRemaining"] . "</td>
                                                            </tr>";
                                                    }
                                    echo "</table>";
                                                } else {
                                                    echo "<div class='alert alert-secondary'>No Results Found!</div>";
                                    echo "</table>";
                                                }
                                            ?>
                                </div>
                            </div>
                        </div>
                </div>
                            <!---->
                        </div>
                    </div>
               
            <!-- End Leave Tab -->
            
            <!-- Leave Configuration Tab -->
            <div class="tab-pane fade active show" id="custom-content-below-leaveConfiguration" role="tabpanel" aria-labelledby="custom-content-below-leaveConfiguration-tab">
                <div class="page-content container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Leave Configuration Tab -->
        </div>
    </div>
<!-- END Page -->

<!-- MODALS -->
    <div class="modal fade" id="applyLeave">
        <div class="modal-dialog modal-lg">
            <!--<form method="post" action=".php" id='confirmLeaves'>-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="card-title">Apply Leave</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <!--<h4 class="modal-title"><b><span class="employee_id"></span></b></h4>-->
                    </div>
                    <div class="modal-body ">
                        <div class='row'>
                            <div class='col-md-12'>
                                <table class="table dataTable"  id="updateLeaveTable">
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Date</th>
                                        <th>Hours</th>
                                        <th>Reason</th>
                                        <th><button type="button" class="btn btn-success" id="addFields" style="border-radius:100%"><i class="fas fa-plus"></i></button></th>
                                    </tr>
                                    <tr id="updateLeaveRow">
                                        <td>
                                            <select class="form-control col-md-3" data-plugin="select2" data-minimum-results-for-search="Infinity" name="leaveType1">
                                            <!-- Query Leave Types -->
                                            <?php
                                                $query_leaveType = "SELECT * FROM tbl_leave";
                                                $stmt_leaveType = $con->prepare($query_leaveType);
                                                $stmt_leaveType->execute();
                                                while($row_leaveType = $stmt_leaveType->fetch(PDO::FETCH_ASSOC)){
                                                    echo "<option value='". $row_leaveType[leaveID] ."'>". $row_leaveType[leaveName] ."</option>";
                                                }
                                            ?>
                                            <!-- End Query Leave Types-->
                                            </select>
                                        </td>
                                        <td>
                                            <div class="col-md-12 input-group input-daterange" data-plugin="datepicker" id="currentDate">
                                                <input type="text" id="applyLeaveDate1" name="leaveDate1" class="form-control datepicker" autocomplete="off"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div  data-toggle="tooltip" data-placement="top" data-html="true" title="<i class='fas fa-exclamation-circle' style='color:yellow'></i><?php echo $row_leaveType['leaveName']; ?>">
                                            <select class="form-control col-md-3" data-plugin="select2" data-minimum-results-for-search="Infinity" name="leaveDuration1">
                                                <option value="1">8</option>
                                                <option value="0.5">4</option>
                                            </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 input-group">
                                                <input type="text" id="applyLeaveReason1" name="leaveReason1" class="form-control" autocomplete="off" />
                                            </div>
                                        </td>
                                        <td>
                                            <!--<a href="#"><i class="fas fa-minus-circle" style="color:red"></i></a>-->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>                                                
                    </div>
                    <div class="modal-footer">
                        <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="button" class="btn btn-primary"  name="applyLeaveButton" onclick="applyLeave()"> <i class="fa fa-check-square-o" ></i> Apply Leave</button>
                    </div>
                </div>
            <!--</form>-->
        </div>
    </div>
<!-- END MODALS -->

<!-- FOOTER -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>
 
<script type="text/javascript">
// showing custom date range
    function showDateRange(value){
        if(value=="custom"){
            document.getElementById('fromDateGroup').style.display='block';
            document.getElementById('toDateGroup').style.display='block';
        }else{
            document.getElementById('fromDateGroup').style.display='none';
            document.getElementById('toDateGroup').style.display='none';
        }
    }
// end showing custom date range

// adding new input fields v2

$('#addFields').click(function(){
    var html='';
    var now = $.now();
    html += '<tr>';
    html += '<td><select class="form-control col-md-3" data-plugin="select2" data-minimum-results-for-search="Infinity"></select></td>';
    html += '<td>';
    html += '<div class="col-md-12 input-group input-daterange" data-plugin="datepicker" id="currentDate">';
    html += '<input type="text" id="applyLeaveDate1" name="applyLeaveDate[]" class="form-control datepicker" autocomplete="off"/>';
    html += '</div>';
    html += '</td>';
    html += '<td>';
    html += '<div  data-toggle="tooltip" data-placement="top" data-html="true" title="<i class=\'fas fa-exclamation-circle\' style=\'color:yellow\'></i>">';
    html += '<select class="form-control col-md-3" data-plugin="select2" data-minimum-results-for-search="Infinity">';
    html += '<option value="1" selected>8</option>';
    html += '<option value="0.5">4</option>';
    html += '</select>';
    html += '</div>';
    html += '</td>';
    html += '<td>';
    html += '<div class="col-md-12 input-group">';
    html += '<input type="text" id="applyLeaveReason1" name="applyLeaveReason[]" class="form-control" autocomplete="off" />';
    html += '</div>'
    html += '</td>';
    html += '<td>';
    html += '<a href="#"><i class="fas fa-minus-circle fas-lg" style="color:red"></i></a>';
    html += '</td>';
    html += '</tr>';
    
    $('#updateLeaveTable').append(html);
});

// end adding new input fields v2


function applyLeave(){
    var leaveType = $(':input[name=leaveType1]').val();
    var leaveDate = $(':input[name=leaveDate1]').val();
    var leaveDuration = $(':input[name=leaveDuration1]').val();
    var leaveReason = $(':input[name=leaveReason1]').val();
    
    //alert(leaveType+leaveDate+leaveDuration+leaveReason);
    $.ajax({
        method: 'POST',
        url: 'leave_applyLeaveProcess.php',
        data: {leaveType:leaveType, leaveDate:leaveDate, leaveDuration:leaveDuration, leaveReason:leaveReason},
        dataType:'text',
        success: function(response) {
            $('#displayResult').html(response);
            swal('Done!','You have succesfully updated attendance journal!','success');
            
            // $('#showAttendanceUpdatesModal". $attendanceID ."').modal('hide');
        //   document.getElementById('modifyAttendance').submit();
        }
    })
}



// adding new input fields
    // var leaveCtr = 1;
    // var leaveID = "#updateLeaveTable";
    // $(leaveID).on("change keyup", function(){
    //     var leaveDate = $('#applyLeaveDate1').val();
    //     var leaveReason = $('#applyLeaveReason1').val();
    
    //     if(leaveReason == "" || leaveDate == ""){
            
    //     }else{
    //         // alert('Null values');
    //         var lastID = leaveCtr;
    //         leaveCtr++;
    //         // $
    //         $(updateLeaveRow).append('<td><a href="#"><i class="fas fa-minus-circle fas-lg" style="color:red"></i></a></td>');
    //         $(updateLeaveTable).append('<tr><td><select><option>Leave</option></select></td></tr>');
            
    //             // ----------------------------------------------------------------
                
                
                
    //             // -----------------------------------------------------------------
    //             // )
    //         // alert(leaveCtr);
    //     }
    // });
// 

// script for apply leave date limit
        var date = new Date();
        date.setDate(date.getDate()+7);
    
        $('#currentDate').datepicker({ 
            startDate: date
        });
// // end script for apply leave date limit

// bug fix for closing calendar after selection
$('#currentDate').datepicker({autoclose:true});
// 

// $(document).ready(function() {
//     $('#populateLeaveTable').on('change',function(){
        
//          var employeeID = $('#employeeId').val();
//          var range = $('#rangeLength').val();
//          var from_date = document.getElementById('from_date').value;
//          var to_date = document.getElementById('to_date').value;
//         //  alert(to_date);
         
//         if(employeeID){
//             $.ajax({
//                 url: "getAttendanceHistory.php",
//                 method: "post",
//                 data: {employeeID:employeeID, range:range, from_date:from_date, to_date:to_date},
//                 dataType: "text",
//                 success:function(search_result) {
//                     var search_data = $.trim(search_result);
//                     $('#empAttendanceReports').html(search_data);
//                 }
//             });
//         }else{
           

//         }
//     }); 
// });  
// });
 

// $('#leaveTable').dataTable({ "sDom":"ltipr" }); --> for removing search in grid table


// -->try for date range

// // Get the elements
// var from_input = $('#leave_from_date').pickadate(),
// from_picker = from_input.pickadate('picker')
// var to_input = $('#leave_to_date').pickadate(),
// to_picker = to_input.pickadate('picker')

// // Check if there’s a “from” or “to” date to start with and if so, set their appropriate properties.
// if ( from_picker.get('value') ) {
// to_picker.set('min', from_picker.get('select'))
// }
// if ( to_picker.get('value') ) {
// from_picker.set('max', to_picker.get('select'))
// }

// // Apply event listeners in case of setting new “from” / “to” limits to have them update on the other end. If ‘clear’ button is pressed, reset the value.
// from_picker.on('set', function(event) {
// if ( event.select ) {
// to_picker.set('min', from_picker.get('select'))
// }
// else if ( 'clear' in event ) {
// to_picker.set('min', false)
// }
// })
// to_picker.on('set', function(event) {
// if ( event.select ) {
// from_picker.set('max', to_picker.get('select'))
// }
// else if ( 'clear' in event ) {
// from_picker.set('max', false)
// }
// })

 </script>