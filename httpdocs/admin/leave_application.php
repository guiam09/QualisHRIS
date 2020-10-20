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

?>
<style>
    thead input {
        width: 80%;
    }
</style>
<style>
    .modal {
        z-index: 1950;
    }

    .select2-container {
        z-index: 9999;
    }

    .select2-container--open {
        z-index: 99999
    }

    .swal2-container {
        z-index: 999999;
    }
</style>
<!-- Page -->
<div class="page">
    <div class="page-header">
        <h1 class="page-title"><i>Leave</i></h1>
    </div>
    <!-- Leave navigation tabs -->
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item" style="margin-left:10px">
            <a class="nav-link active" id="custom-content-below-leave-monitoring-tab" data-toggle="pill" href="#custom-content-below-leave-monitoring" role="tab" aria-controls="custom-content-below-leaveApproval" aria-selected="true">Requests</a>
        </li>

        <?php
        //debug: modify access level in database
        $userCode = $_SESSION['user_id'];
        $query_checkUserAccount = "SELECT * FRoM tbl_employees WHERE employeeCode = '$userCode'";
        $stmt_checkUserAccount = $con->prepare($query_checkUserAccount);
        if($stmt_checkUserAccount->execute()){
            while($row_checkUserAccount = $stmt_checkUserAccount->fetch(PDO::FETCH_ASSOC)){
                $userID=$row_checkUserAccount['employeeID'];
                $userAccountLevel = $row_checkUserAccount['positionID']."<br>";
                $userDepartmentID = $row_checkUserAccount['departmentID'];
                if($userAccountLevel == 15 || $userDepartmentID <= 2){
                    echo '<li class="nav-item" style="margin-left:10px">
                            <a class="nav-link" id="custom-content-below-leaveApproval-tab" data-toggle="pill" href="#custom-content-below-leaveApproval" role="tab" aria-controls="custom-content-below-leaveApproval" aria-selected="true">Approval</a>
                        </li>';
                }
            }
        }
        ?>
        <li class="nav-item">
            <a class="nav-link" id="custom-content-below-leaveCount-tab" data-toggle="pill" href="#custom-content-below-leaveCount" role="tab" aria-controls="custom-content-below-leaveCount" aria-selected="false">Balance</a>
        </li>
    </ul>
    <!-- end Leave navigation tabs -->

    <div class="tab-content" id="custom-content-below-tabContent">
        <!-- Leave Approval Tab -->
        <div class="tab-pane" id="custom-content-below-leaveApproval" role="tabpanel" aria-labelledby="custom-content-below-leaveApproval-tab">
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title text-danger" id="displayResult"></h3>
                            </div>
                            <div class="panel-body container-fluid">
                                <form id="populateLeaveTable">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <?php
                                            //debug: modify access level in database
                                            $userCode = $_SESSION['user_id'];
                                            $query_checkUserAccount = "SELECT * FRoM tbl_employees WHERE employeeCode = '$userCode'";
                                            $stmt_checkUserAccount = $con->prepare($query_checkUserAccount);
                                            if($stmt_checkUserAccount->execute()){
                                                while($row_checkUserAccount = $stmt_checkUserAccount->fetch(PDO::FETCH_ASSOC)){
                                                    $userID=$row_checkUserAccount['employeeID'];
                                                    $userAccountLevel = $row_checkUserAccount['positionID']."<br>";
                                                    $userDepartmentID = $row_checkUserAccount['departmentID'];
                                                    if($userAccountLevel == 15 || $userDepartmentID <= 2){
                                                        echo
                                                        '<div class="col-md-3"><div class="form-group form-material col-md-12">
                                                <label class="form-control-label">Employee</label>
                                                <select class="form-control col-md-6" data-plugin="select2"  id="employeeId" name="employeeId" data-placeholder="Select Employee">';
                                                 include ('searchEmployeeList.php');
                                                 
                                            echo '</select></div></div>';
                                                    }
                                                }
                                            }
                                            ?>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">Status</label><br />
                                                    <select class="form-control col-md-12" id="showStatus">
                                                        <option value=""></option>
                                                        <option>Pending</option>
                                                        <option>Approved</option>
                                                        <option>Declined</option>
                                                        <option>Retracted Pending</option>
                                                        <option>Retracted Approved</option>
                                                        <option>Retracted Declined</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">Range</label><br />
                                                    <select class="form-control col-md-12" data-plugin="select" data-minimum-results-for-search="Infinity" data-placeholder="Select Range" tab-index="-1" width="auto" id="showDateRange">
                                                        <option value=" "></option>
                                                        <option value="currentWeek">Current Week</option>
                                                        <option value="lastWeek">Last Week</option>
                                                        <option value="lastTwoWeeks">Last 2 Weeks</option>
                                                        <option value="lastMonth">Last Month</option>
                                                        <option value="custom">Custom</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--<div class="date-range">-->
                                            <div class="col-md-3" id='fromDateGroup' style=display:none>
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">From</label>
                                                    <div class="col-md-12 input-group input-daterange" id="fromleavedatepicker" data-plugin="datepicker">
                                                        <input type="text" name="leave_from_date" id="leave_from_date" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id='toDateGroup' style=display:none>
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">To</label>
                                                    <div class="col-md-12 input-group input-daterange" id="toleavedatepicker" data-plugin="datepicker">
                                                        <input type="text" name="leave_to_date" id="leave_to_date" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--</div>-->
                                            <?php
                                            if($userAccountLevel != 15 && $userDepartmentID > 2){
                                            echo '<div class="col-md-3">
                                            </div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </form>
                                <br />
                                <div class="row">
                                    <table id="showLeaveTable" class="table table-hover dataTable table-striped w-full" data-plugin="">
                                        <thead>
                                            <tr>
                                                <th>Date Filed</th>
                                                <th>Name</th>
                                                <th>Leave Type</th>
                                                <th>Leave Date</th>
                                                <th>Duration</th>
                                                <th>Status</th>
                                                <th>Date Approved/Declined</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
        <div class="tab-pane fade" id="custom-content-below-leaveCount" role="tabpanel" aria-labelledby="custom-content-below-leaveCount-tab">
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
                                        if ($num>0) {
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

        <div class="tab-pane fade active show" id="custom-content-below-leave-monitoring" role="tabpanel" aria-labelledby="custom-content-below-leave-monitoring-tab">
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!---->
                        <div class="panel">
                            <div class="panel-body container-fluid">
                                <div class="row">
                                    
                                        <div class="col-md-12"><form id="populateLeaveTable2">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">Status</label><br />
                                                    <select class="form-control col-md-12" id="showStatus2">
                                                        <option value=""></option>
                                                        <option>Pending</option>
                                                        <option>Approved</option>
                                                        <option>Declined</option>
                                                        <option>Retracted Pending</option>
                                                        <option>Retracted Approved</option>
                                                        <option>Retracted Declined</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">Range</label><br />
                                                    <select class="form-control col-md-12" data-plugin="select" data-minimum-results-for-search="Infinity" data-placeholder="Select Range" tab-index="-1" width="auto" id="showDateRange2">
                                                        <option value=" "></option>
                                                        <option value="currentWeek">Current Week</option>
                                                        <option value="lastWeek">Last Week</option>
                                                        <option value="lastTwoWeeks">Last 2 Weeks</option>
                                                        <option value="lastMonth">Last Month</option>
                                                        <option value="custom">Custom</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--<div class="date-range">-->
                                            <div class="col-md-3" id='fromDateGroup2' style=display:none>
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">From</label>
                                                    <div class="col-md-12 input-group input-daterange" id="fromleavedatepicker2" data-plugin="datepicker">
                                                        <input type="text" name="leave_from_date2" id="leave_from_date2" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id='toDateGroup2' style=display:none>
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">To</label>
                                                    <div class="col-md-12 input-group input-daterange" id="toleavedatepicker2" data-plugin="datepicker">
                                                        <input type="text" name="leave_to_date2" id="leave_to_date2" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--</div>-->
                                            <?php
                                            if($userAccountLevel != 15 && $userDepartmentID > 2){
                                            echo '<div class="col-md-3">
                                            </div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    </form>
                                    <div class="col-md-2">
                                        
					<button type="button" data-toggle="modal" data-target="#applyLeaveModal" class="btn btn-block btn-info">File Leave</button>
                                        
                                    </div>
                                </div>
                                <br />
                                <table id='datatable1' class='table table-hover table-striped'>
                                    <thead>
                                        <tr>
                                            <th>Date Filed</th>
                                            <th>Leave Type</th>
                                            <th>Leave Date</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Date Approved/Declined</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        // select all data
                                        $user = $_SESSION['employeeID'];
                                        $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID  INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leavedetails.employeeID = '$user' group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";

                                        $stmt = $con->prepare($query_leaveList);
                                        $stmt->execute();
                                        $num = $stmt->rowCount();

                                        if ($num>0) {
    
                                            while ($row_leaveList = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                if ($row_leaveList['leaveGroup_status'] == 'Approved' || $row_leaveList['leaveGroup_status'] == 'Declined') {
                                                    $viewEditButton = "view";
                                                } else {
                                                    $viewEditButton = "edit";
                                                }
                                                echo " <tr>
                                                       <td>". $row_leaveList['leaveGroup_dateFiled'] ."</td>
                                                       <td>". $row_leaveList['leave_details'] ."</td>
                                                        <td>". $row_leaveList['leave_from'] ."</td>
                                                        <td>". $row_leaveList['leaveGroup_duration'] ." day</td>
                                                        <td>". $row_leaveList['leaveGroup_status'] ."</td>";
                                                        if(isset($row_leaveList['leaveGroup_dateApproved'])){
                                                        echo "<td>". $row_leaveList['leaveGroup_dateApproved'] ."</td>";
                                                        }else{
                                                        echo "<td>----------------------</td>";
                                                        }
                                                        echo " <td><button type='button' id='cancelLeaveRequest".$row_leaveList['leavedetails_ID'] ."' class='btn btn-block btn-danger'>Cancel</button>";
                                                        echo " <button type='button' data-target='#". $viewEditButton.$row_leaveList['leaveGroup_ID'] ."' data-toggle='modal' class='btn btn-block btn-success'>View / Edit</button></td>";
                                                 echo"   </tr>";
                                            ?>
                                            <!-- EDIT MODAL -->
                                            <div class="modal fade" id="edit<?php echo $row_leaveList['leaveGroup_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"> View Leave</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="update-form-<?php echo $row_leaveList['leaveGroup_ID'] ?>">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                <h6>Leave Type</h6>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                <h6>Date</h6>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                <h6>Days</h6>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                <h6>Reason</h6>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php 
                                                                $_query = "SELECT *,tbl_leavedetails.leaveID as leave_id FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID where tbl_leaveGroup.leaveGroup_ID = ".$row_leaveList['leaveGroup_ID'];

                                                                $a = $con->prepare($_query);
                                                                if ($a->execute()):
                                                                    while ($z=$a->fetch(PDO::FETCH_ASSOC)):
                                                                ?>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <input name="leave_delail_id[]" value="<?php echo $z['leavedetails_ID'] ?>" type="hidden">
                                                                            <select class="form-control col-md-12" id="leaveTypeUpdate<?php echo $z['leavedetails_ID'] ?>" name="leaveType[]">
                                                                                <?php
                                                                                    $query_leaveType = "SELECT * FROM tbl_leave";
                                                                                    $stmt_leaveType = $con->prepare($query_leaveType);
                                                                                    $stmt_leaveType->execute();
                                                                                    while($row_leaveType = $stmt_leaveType->fetch(PDO::FETCH_ASSOC)){
                                                                                        // ---------------
                                                                                        $userID = $_SESSION['user_id'];
                                                                                        $query_getEmployeeID = "SELECT employeeID FROM tbl_employees WHERE employeeCode = '$userID'";
                                                                                        $stmt_getEmployeeID=$con->prepare($query_getEmployeeID);
                                                                                        $stmt_getEmployeeID->execute();
                                                                                        while($row_getEmployeeID = $stmt_getEmployeeID->fetch(PDO::FETCH_ASSOC)){
                                                                                            $employeeID = $row_getEmployeeID['employeeID'];
                                                                                        }
                                                                                        
                                                                                        $query_allowedLeaveType = "SELECT * FROM tbl_leaveinfo WHERE employeeID='$employeeID'";
                                                                                        $stmt_allowedLeaveType = $con->prepare($query_allowedLeaveType);
                                                                                        $stmt_allowedLeaveType->execute();
                                                                                        while($row_allowedLeaveType = $stmt_allowedLeaveType->fetch(PDO::FETCH_ASSOC)){
                                                                                            if($row_leaveType['leaveID'] == $row_allowedLeaveType['leaveID']){
                                                                                                $selected = '';
                                                                                                if($row_leaveType['leaveID'] == $z['leave_id']){
                                                                                                    $selected= "selected";
                                                                                                }

                                                                                                echo "<option ".$selected." value='". $row_leaveType['leaveID'] ."'>". $row_leaveType['leaveName'] ."</option>";
                                                                                            }
                                                                                        }
                                                                                        // ----------------
                                                                                        //echo "<option value='". $row_leaveType['leaveID'] ."'";
                                                                                        if($row_leaveType['leaveID'] == $z['leave_id']){
                                                                                            echo " selected";
                                                                                        }
                                                                                        
                                                                                        echo ">". $row_leaveType['leaveName'] ."</option>";
                                                                                    }
                                                                                            
                                                                                    $orginalDateFormat=$z['leavedetails_leaveFrom'];
                                                                                    $newDateFormat = date("m-d-Y", strtotime($orginalDateFormat));
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <div class="col-md-12 input-group input-daterange preferredleaveDate currentDate" data-plugin="datepicker">
                                                                                <input type="text" id="leaveDateUpdate<?php echo $z['leavedetails_ID'] ?>" name="leaveDate[]" class="form-control datepicker" autocomplete="off" value="<?php echo $newDateFormat; ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<i class='fas fa-exclamation-circle' style='color:yellow'></i><?php echo $row_leaveType['leaveName']; ?>">
                                                                                <select class="form-control col-md-12" id="leaveDurationUpdate<?php echo $z['leavedetails_ID'] ?>" name="leaveDuration[]">
                                                                                    <option value="1" <?php if($z['leavedetails_duration']==1){echo 'selected';} ?>>1</option>
                                                                                    <option value="0.5" <?php if($z['leavedetails_duration']==0.5){echo 'selected';} ?>>0.5</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <div class="col-md-12 input-group">
                                                                                <input type="text" id="leaveReasonUpdate<?php echo $z['leavedetails_ID'] ?>" name="leaveReason[]" class="form-control" autocomplete="off" value="<?php echo $z['leavedetails_reason'] ;?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php 
                                                                    endwhile;
                                                                endif;
                                                                ?>
                                                                <input type="hidden" value="<?php echo $row_leaveList['leaveGroup_ID'] ?>" name="leave_group_id">
                                                            </form>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <?php
                                                                        $approverName = $row_leaveList['leaveGroup_approver'];
                                                                        $query_getApproverName = "SELECT lastName, firstName, middleName FROM tbl_employees WHERE employeeID=:employeeID";
                                                                        $stmt_getApproverName=$con->prepare($query_getApproverName);
                                                                        
                                                                        $stmt_getApproverName->bindParam(':employeeID',$approverName);
                                                                        
                                                                        if($stmt_getApproverName->execute()){
                                                                            while($row_getApproverName=$stmt_getApproverName->fetch(PDO::FETCH_ASSOC)){
                                                                                $approverFirstName = $row_getApproverName['firstName'];
                                                                                $approverLastName = $row_getApproverName['lastName'];
                                                                                $approverMiddleName = $row_getApproverName['middleName'];
                                                                            }
                                                                        }
                                                                        
                                                                        ?>
                                                                        <div class="form-control" readonly><?php echo $approverLastName.", ".$approverFirstName." ".$approverMiddleName; ?></div>
                                                                        <!--<select class="form-control col-md-12" data-plugin="selectpicker" id="adminIdUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="adminId" data-placeholder="Select Employee">-->
                                                                            <?php //include ('searchAdminList.php');?>
                                                                        <!--</select>-->
                                                                    </div>
                                                                    <input type="hidden" id="leavedetailsIdUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" value="<?php echo $row_leaveList['leavedetails_ID'] ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" id="closeEdit<?php echo $row_leaveList['leavedetails_ID'] ;?>" class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                                                            <button type="button" class="btn btn-primary" name="updateLeaveRequestButton" id="updateLeaveRequestButton<?php echo $row_leaveList['leavedetails_ID'] ?>"> <i class="fa fa-check-square-o"></i> Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- View MODAL-->
                                            <div class="modal fade" id="view<?php echo $row_leaveList['leaveGroup_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"> View Leave</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="reviewLeaveType" class="col-sm-12 control-label">
                                                                            <h6>Leave Type</h6>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="reviewLeaveDate" class="col-sm-12 control-label">
                                                                            <h6>Date</h6>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="reviewLeaveDuration" class="col-sm-12 control-label">
                                                                            <h6>Days</h6>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label for="reviewLeaveReason" class="col-sm-12 control-label">
                                                                            <h6>Reason</h6>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php 
                                                                $_query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID where tbl_leaveGroup.leaveGroup_ID = ".$row_leaveList['leaveGroup_ID'];

                                                                $a = $con->prepare($_query);
                                                                if ($a->execute()):
                                                                    while ($z=$a->fetch(PDO::FETCH_ASSOC)):
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="form-control" readonly><?php echo $z['leaveName']; ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="form-control" readonly><?php echo $z['leavedetails_leaveFrom']; ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="form-control" readonly><?php echo $z['leavedetails_duration']; ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="form-control" readonly><?php echo $z['leavedetails_reason']; ?></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php 
                                                                    endwhile;
                                                                endif;
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="showLeaveApprover" class="col-sm-12 control-label">
                                                                            <h6>Approver</h6>
                                                                        </label>
                                                                        <div class="form-control" readonly>
                                                                        <?php 
                                                        $approverID = $row_leaveList['leaveGroup_approver'];
                                                        $query_getApprover = "SELECT lastName, firstName, middleName FROM tbl_employees WHERE employeeID=:approverID";
                                                        $stmt_getApprover = $con->prepare($query_getApprover);
                                                        
                                                        $stmt_getApprover->bindParam(':approverID', $approverID);
                                                        if($stmt_getApprover->execute()){
                                                            while($row_approverName = $stmt_getApprover->fetch(PDO::FETCH_ASSOC)){
                                                                echo $row_approverName['lastName'].", ".$row_approverName['firstName']." ".$row_approverName['middleName'];
                                                            }
                                                        }
                                                                            ?>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="showLeaveNotes" class="col-sm-12 control-label">
                                                                            <h6>Notes</h6>
                                                                        </label>
                                                                        <textarea class="form-control" readonly><?php echo $row_leaveList['leaveGroup_notes']; ?></textarea>
                                                                        <input type="hidden" id="approveLeaveGroupReviewID<?php echo $row_leaveList['leavedetails_ID'] ;?>" name="approveLeaveGroupID" value="<?php echo $row_leaveList['leaveGroup_ID']; ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                <?php 
                                                echo "//update leave request ajax
                                                    $('#updateLeaveRequestButton". $row_leaveList['leavedetails_ID'] ."').on('click', function() {
                                                        $.ajax({
                                                            method: 'POST',
                                                            url: 'leave_updateLeaveRequestProcess.php',
                                                            data: $('#update-form-".$row_leaveList['leaveGroup_ID']."').serialize(),
                                                            dataType: 'text',
                                                            success: function(response) {
                                                                location.reload(true);
                                                            }
                                                        });
                                                    });

                                                    $('#cancelLeaveRequest". $row_leaveList['leavedetails_ID'] ."').on('click',function(){
                                                        Swal.fire({
                                                        title: 'Are you sure?',
                                                        text: 'Do you want to cancel this leave request?',
                                                        type: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Yes',
                                                        cancelButtonText: 'No'
                                                        }).then((result) => {
                                                        if (result.value) {
                                                            var leavedetailsID = $('#approveLeaveGroupReviewID". $row_leaveList['leavedetails_ID'] ."').val(); 
                                                            
                                                            $.ajax({
                                                                method: 'POST',
                                                                url: 'leave_cancelLeaveProcess.php',
                                                                data: {
                                                                leavedetailsID: leavedetailsID
                                                                },
                                                                dataType: 'html',
                                                                success: function(response) {
                                                                $('#displayResult').html(response);
                                                                $('html, body').animate({ scrollTop: 0 }, 'fast');
                                                                location.reload(true);
                                                                }
                                                            });
                                                        }
                                                        });
                                                        // location.reload(true);
                                                        // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
                                                    });
                                                "
                                                ?>
                                            </script>
                                            <?php
                                            }
                                            echo "</tbody></table>";
                                        } else {
                                            echo "<div class='alert alert-secondary'>No Results Found!</div>";
                                            echo "</tbody></table>";
                                        }
                                    ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
            </div>
        </div>
    </div>
</div>
<!-- END Page -->

<!-- MODALS -->
<div class="modal fade" id="applyLeaveModal">
    <div class="modal-dialog modal-lg">
        <form id='confirmLeaves'>
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
                        <input type="hidden" id="leave_form_row_count" value="1">
                        <table class="table dataTable" id="updateLeaveTable">
                            <tr>
                                <th>Leave Type</th>
                                <th>Date</th>
                                <th>Days</th>
                                <th>Reason</th>
                            </tr>
                            <tr class="leaveData filled first-row-data" data-rowID="#dataRow1" id="dataRow1">
                                <td class="hasInputData">
                                    <select class="form-control col-md-12" name="leave_type[]" id="applyLeaveType">
                                        <!-- Query Leave Types -->
                                        <?php
                                                $query_leaveType = "SELECT * FROM tbl_leave";
                                                $stmt_leaveType = $con->prepare($query_leaveType);
                                                $stmt_leaveType->execute();
                                                while($row_leaveType = $stmt_leaveType->fetch(PDO::FETCH_ASSOC)){
                                                    $userID = $_SESSION['user_id'];
                                                    $query_getEmployeeID = "SELECT employeeID FROM tbl_employees WHERE employeeCode = '$userID'";
                                                    $stmt_getEmployeeID=$con->prepare($query_getEmployeeID);
                                                    $stmt_getEmployeeID->execute();
                                                    while($row_getEmployeeID = $stmt_getEmployeeID->fetch(PDO::FETCH_ASSOC)){
                                                        $employeeID = $row_getEmployeeID['employeeID'];
                                                    }
                                                    
                                                    $query_allowedLeaveType = "SELECT * FROM tbl_leaveinfo WHERE employeeID='$employeeID'";
                                                    $stmt_allowedLeaveType = $con->prepare($query_allowedLeaveType);
                                                    $stmt_allowedLeaveType->execute();
                                                    while($row_allowedLeaveType = $stmt_allowedLeaveType->fetch(PDO::FETCH_ASSOC)){
                                                        if($row_leaveType['leaveID'] == $row_allowedLeaveType['leaveID']){
                                                            echo "<option value='". $row_leaveType['leaveID'] ."'>". $row_leaveType['leaveName'] ."</option>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        <!-- End Query Leave Types-->
                                    </select>
                                </td>
                                <td class="hasInputData">
                                    <div class="col-md-12 input-group input-daterange preferredleaveDate apply-leave-date" data-plugin="datepicker" id="currentDate">
                                        <input onchange="row_check($(this))" type="text" id="applyLeaveDate" name="leave_date[]" class="form-control datepicker" autocomplete="off" />
                                    </div>
                                </td>
                                <td class="hasInputData">
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<i class='fas fa-exclamation-circle' style='color:yellow'></i><?php echo $row_leaveType['leaveName']; ?>">
                                        <select onchange="row_check($(this))" class="form-control col-md-12" name="leave_duration[]" id="applyLeaveDuration">
                                            <option value="1">1</option>
                                            <option value="0.5">0.5</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="hasInputData leaveReason">
                                    <div class="col-md-12 input-group">
                                        <input onchange="row_check($(this))" type="text" id="applyLeaveReason" name="leave_reason[]" class="form-control" autocomplete="off" />
                                    </div>
                                </td>
                                <td class="hasInputData">
                                    <!--<a href="#"><i class="fas fa-minus-circle" style="color:red"></i></a>-->
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-control-label">Approver</label>
                        <select class="form-control col-md-12" data-plugin="selectpicker" id="applyLeaveApprover" name="adminIdApply" data-placeholder="Select Employee">
                            <?php include ('searchAdminList.php');?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="button" class="btn btn-primary" name="applyLeaveButton" id="applyLeaveButton"> <i class="fa fa-check-square-o"></i> Apply Leave</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- END MODALS -->

<!-- FOOTER -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>

<script>
    var date = new Date();
    date.setDate(date.getDate() + 7);
    $('#currentDate').datepicker({
        startDate: date
    });

    $(document).ready(function() {
        $('#showLeaveTable').DataTable();
        
        // showing custom date range
        $('#showDateRange').on('change', function() {
            //        function showDateRange(value) {
            var leaveType = $(this).val();
            if (leaveType == "custom") {
                document.getElementById('fromDateGroup').style.display = 'block';
                document.getElementById('toDateGroup').style.display = 'block';
            } else {
                document.getElementById('fromDateGroup').style.display = 'none';
                document.getElementById('toDateGroup').style.display = 'none';
            }
        });

        $('#showDateRange2').on('change', function() {
            //        function showDateRange(value) {
            var leaveType = $(this).val();
            if (leaveType == "custom") {
                document.getElementById('fromDateGroup2').style.display = 'block';
                document.getElementById('toDateGroup2').style.display = 'block';
            } else {
                document.getElementById('fromDateGroup2').style.display = 'none';
                document.getElementById('toDateGroup2').style.display = 'none';
            }
        });
        // end showing custom date range
        
        //apply leave ajax
        $('#applyLeaveButton').on('click', function() {
            var leaveType = $('#applyLeaveType').val();
            var leaveDate = $('#applyLeaveDate').val();
            var leaveDuration = $('#applyLeaveDuration').val();
            var leaveReason = $('#applyLeaveReason').val();
            var leaveApprover = $('#applyLeaveApprover').val();
            
            var respond = "Leave request sent.";

            $.ajax({
                method: 'POST',
                url: 'leave_applyLeaveProcess.php',
                data: $('#confirmLeaves').serialize(),
                dataType: 'text',
                success: function(response) {
                    $('#displayResult').html(response);
                    // alert("inside");
                    // respond = JSON.stringify(response);
                    location.reload(true);
                    swal('Leave Request Sent', {
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: false
                    }, 'success');

                    
                }
            });

            $('#applyLeaveModal').modal('toggle');
        });


        $('#populateLeaveTable2').on('change',function(){
            var range = $('#showDateRange2').val();
            var from_date = document.getElementById('leave_from_date2').value;
            var to_date = document.getElementById('leave_to_date2').value;
            var status = document.getElementById('showStatus2').value;

            $.ajax({
                 url: "searchLeaveMonitoringTable.php",
                 method: "post",
                 data: {range:range, from_date:from_date, to_date:to_date, status:status},
                 dataType: "text",
                 success:function(search_result) {
                   var search_data = $.trim(search_result);
                   $('#datatable1').html(search_data);
                }
            });
        });

        $('#populateLeaveTable').on('change',function(){
             var employeeID = $('#employeeId').val();
            
             var range = $('#showDateRange').val();
             var from_date = document.getElementById('leave_from_date').value;
             var to_date = document.getElementById('leave_to_date').value;
             var status = document.getElementById('showStatus').value;

             if(range || employeeID){
                $.ajax({
                     url: "searchLeaveTable.php",
                     method: "post",
                     data: {employeeID:employeeID, range:range, from_date:from_date, to_date:to_date, status:status},
                     dataType: "text",
                     success:function(search_result) {
                       var search_data = $.trim(search_result);
                       $('#showLeaveTable').html(search_data);
                  }
                });
             }
        }); 

        //auto add leave input fields
        var leaveRowCtr = 1;

        // bug fix for closing calendar after selection
        $('#currentDate').datepicker({
            autoclose: true
        });
    });
  
    function add_row()
    {
        var rows = parseInt($('#leave_form_row_count').val()) + 1;
        $('#updateLeaveTable').append(
            '<tr id="leave_row_' + rows + '">' +
                $('.first-row-data').html() +
                '<td class="hasInputData">\
                    <a href="#"><i class="fas fa-minus-circle" style="color:red" onclick="delete_row('+rows+')"></i></a>\
                </td>'+
            '</tr>');
        $('#leave_form_row_count').val(rows);

        var date = new Date();
        date.setDate(date.getDate() + 7);
        $('.apply-leave-date').datepicker({
            startDate: date
        });
    }

    function search_table()
    {
        var employeeID = $('#employeeId').val();
            
         var range = $('#showDateRange').val();
         var from_date = document.getElementById('leave_from_date').value;
         var to_date = document.getElementById('leave_to_date').value;
         var status = document.getElementById('showStatus').value;

         if(range || employeeID){
            $.ajax({
                 url: "searchLeaveTable.php",
                 method: "post",
                 data: {employeeID:employeeID, range:range, from_date:from_date, to_date:to_date, status:status},
                 dataType: "text",
                 success:function(search_result) {
                   var search_data = $.trim(search_result);
                   $('#showLeaveTable').html(search_data);
              }
            });
         }
    }

    function delete_row(id)
    {
        $('#leave_row_'+id).remove();
    }

    function row_check(elem)
    {
        var leave_type = $(elem).parent().parent().parent().find('select[name="leave_type[]"]').val();
        var leave_date = $(elem).parent().parent().parent().find('input[name="leave_date[]"]').val();
        var leave_duration = $(elem).parent().parent().parent().find('select[name="leave_duration[]"]').val();
        var leave_reason = $(elem).parent().parent().parent().find('input[name="leave_reason[]"]').val();

        if ($(elem).parent().parent().parent().is(':last-child')) {
            if ($.trim(leave_type) !== "" && $.trim(leave_date) !== "" && $.trim(leave_duration) !== "" && $.trim(leave_reason) !== "") {
                add_row();
            }
        }
    }

    $(document).ready(function(){
            var table = $('#datatable1').DataTable();
    });

    

    $(document).ready(function() {
        var table = $('#example3').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });        
    });

    


</script>