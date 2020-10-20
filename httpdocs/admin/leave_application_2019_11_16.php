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
            <a class="nav-link active" id="custom-content-below-leave-monitoring-tab" data-toggle="pill" href="#custom-content-below-leave-monitoring" role="tab" aria-controls="custom-content-below-leaveApproval" aria-selected="true">Monitoring</a>
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
        <!--<li class="nav-item">-->
        <!--    <a class="nav-link" id="custom-content-below-leaveConfiguration-tab" data-toggle="pill" href="#custom-content-below-leaveConfiguration" role="tab" aria-controls="custom-content-below-leaveConfiguration" aria-selected="false">Leave Configuration</a>-->
        <!--</li>-->
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
                                                    <select class="form-control col-md-12" data-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Select Range" tab-index="-1" width="auto" id="showDateRange">
                                                        <option value=""></option>
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
                                            <?php
                                            // echo $userID;
                                            if($userAccountLevel != 15 && $userDepartmentID > 2){
                                                $query_leaveList = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID  INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leavedetails.employeeID = '$userID' ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                                            }else{
                                                $query_leaveList = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID  INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                                            }
                                                $stmt_leaveList = $con->prepare($query_leaveList);
                                                if($stmt_leaveList->execute()){

                                                    while($row_leaveList=$stmt_leaveList->fetch(PDO::FETCH_ASSOC)){
                                                        continue;
                                                        echo "
                                                            <tr ";
                                                            if($row_leaveList['leaveGroup_approver'] == $userID){
                                                             echo "class='employeeApprover' hidden";
                                                            }
                                                            
                                                        echo ">
                                                                
                                                                <td>". $row_leaveList['leaveGroup_dateFiled'] ."</td>
                                        <td>";
                                        
                                        $query_getName = "SELECT * FROm tbl_employees WHERE employeeID=:employeeID";
                                        $stmt_getName = $con->prepare($query_getName);
                                        
                                        $stmt_getName->bindParam(':employeeID',$row_leaveList['employeeID']);
                                        if($stmt_getName->execute()){
                                            while($row_getName = $stmt_getName->fetch(PDO::FETCH_ASSOC)){
                                                echo $row_getName['lastName'] .", ". $row_getName['firstName'];
                                            }
                                        }
                                        
                                        echo "</td>
                                                                
                                                                <td>". $row_leaveList['leaveName'] ."</td>
                                                                <td>". $row_leaveList['leavedetails_leaveFrom'] ."</td>
                                                                <td>". $row_leaveList['leaveGroup_duration'] ." day</td>
                                                                <td>". $row_leaveList['leaveGroup_status'] ."</td>";

                                                                if(isset($row_leaveList['leaveGroup_dateApproved'])){
                                                                echo "<td>". $row_leaveList['leaveGroup_dateApproved'] ."</td>";
                                                                }else{
                                                                echo "<td>----------------------</td>";
                                                                }
                                                                
                                                                if($row_leaveList['leaveGroup_status'] == 'Approved' || $row_leaveList['leaveGroup_status'] == 'Declined'){
                                                                    $viewEditButton = "view";
                                                                }else{
                                                                    $viewEditButton = "edit";
                                                                }
                                                                echo
                                                                "<td>
                                                                    <!--<a href='#'><i class='fas fa-book' style='color:grey'></i></a>-->";
                                                                    $userID = $_SESSION['user_id'];
                                                                    $query_getEmployeeID = "SELECT employeeID FROM tbl_employees WHERE employeeCode = '$userID'";
                                                                    $stmt_getEmployeeID=$con->prepare($query_getEmployeeID);
                                                                    $stmt_getEmployeeID->execute();
                                                                    while($row_getEmployeeID = $stmt_getEmployeeID->fetch(PDO::FETCH_ASSOC)){
                                                                        $employeeID = $row_getEmployeeID['employeeID'];
                                                                    }
                                                                    
                                                                    if($row_leaveList['employeeID'] != $employeeID){
                                                                        echo "<button type='button' data-target='#review".$row_leaveList['leavedetails_ID'] ."' data-toggle='modal' ";
                                                                        if($row_leaveList['leaveGroup_status']=="Pending" && $employeeID==$row_leaveList['leaveGroup_approver']){
                                                                            echo "class='btn btn-block btn-success'";
                                                                        }else if($row_leaveList['leaveGroup_status']=="Pending"){
                                                                            echo "class='btn btn-block btn-outline-primary'";
                                                                        }else{
                                                                            echo "class='btn btn-block btn-outline-primary'";
                                                                        }
                                                                        
                                                                        
                                                                    echo    ">Review</button>";
                                                                    }
                                                                    
                                                                    if($row_leaveList['employeeID'] == $employeeID){
                                                                        echo
                                                                        " <button type='button' data-target='#". $viewEditButton.$row_leaveList['leavedetails_ID'] ."' data-toggle='modal' class='btn btn-block btn-success'>View / Edit</button>";
                                                                    }
                                                                    
                                                                    if($row_leaveList['employeeID'] == $employeeID && $row_leaveList['leaveGroup_status']!="Declined"){
                                                                        echo " <button type='button' id='cancelLeaveRequest".$row_leaveList['leavedetails_ID'] ."' class='btn btn-block btn-danger'>Cancel</button>";
                                                                    }
                                                                echo "
                                                                </td>
                                                            </tr>
                                                        ";
                                                        ?>
                                                        <!-- MODALS-->
                                                        <!-- Review MODAL-->
                                                        <div class="modal fade" id="review<?php echo $row_leaveList['leavedetails_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="reviewLeaveModalLabel" aria-hidden="true">
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
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leaveName']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveDate" class="col-sm-12 control-label">
                                                                                        <h6>Date</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_leaveFrom']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveDuration" class="col-sm-12 control-label">
                                                                                        <h6>Hours</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_duration']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveReason" class="col-sm-12 control-label">
                                                                                        <h6>Reason</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_reason']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveApprover" class="col-sm-12 control-label">
                                                                                        <h6>Approver</h6>
                                                                                    </label>
                                                                                    
                                                                                    <?php
                                                                                        if($row_leaveList['leaveGroup_status']=="Pending"){
                                                                                    ?>
                                                                                        <select class="form-control col-md-12" data-plugin="selectpicker" id="adminIdReview<?php echo $row_leaveList['leavedetails_ID'] ;?>" name="adminId" data-placeholder="Select Employee">
                                                                                            <?php include ('searchAdminList.php');?>
                                                                                        </select>
                                                                                    <?php
                                                                                        }else{
                                                                                            $approverID = $row_leaveList['leaveGroup_approver'];
                                                                                            $query_getApprover = "SELECT lastName, firstName, middleName FROM tbl_employees WHERE employeeID=:approverID";
                                                                                            $stmt_getApprover = $con->prepare($query_getApprover);
                                                                                            
                                                                                            $stmt_getApprover->bindParam(':approverID', $approverID);
                                                                                            if($stmt_getApprover->execute()){
                                                                                                while($row_approverName = $stmt_getApprover->fetch(PDO::FETCH_ASSOC)){
                                                                                    ?>
                                                                                        <div class="form-control" readonly><?php echo $row_approverName['lastName'].", ".$row_approverName['firstName']." ".$row_approverName['middleName']; ?></div>
                                                                                    <?php 
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveNotes" class="col-sm-12 control-label">
                                                                                        <h6>Notes</h6>
                                                                                    </label>
                                                                                    <input type="hidden" id="approveLeaveGroupReviewID<?php echo $row_leaveList['leavedetails_ID'] ;?>" name="approveLeaveGroupID" value="<?php echo $row_leaveList['leaveGroup_ID']; ?>" />
                                                                                    <?php
                                                                                        if($row_leaveList['leaveGroup_status']=="Pending"){
                                                                                    ?>
                                                                                        <textarea class="form-control" id="leaveNotesReview<?php echo $row_leaveList['leavedetails_ID'] ;?>" name="leaveNotes"></textarea>
                                                                                    <?php
                                                                                        }else{
                                                                                    ?>
                                                                                        <textarea class="form-control" readonly><?php echo $row_leaveList['leaveGroup_notes']; ?></textarea>
                                                                                    <?php 
                                                                                        }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" id="closeReview<?php echo $row_leaveList['leavedetails_ID'] ;?>" class="btn btn-default btn-outline" data-dismiss="modal"> Cancel</button>
                                                                        <?php
                                                                        // echo $employeeID;
                                                                        // echo "<br>";
                                                                        // echo $row_leaveList['leaveGroup_approver'];
                                                                        if($employeeID == $row_leaveList['leaveGroup_approver'] && ($row_leaveList['leaveGroup_status'] == 'Pending' || $row_leaveList['leaveGroup_status'] == 'Retracted Pending')){
                                                                        echo
                                                                        '<button type="button" class="btn btn-danger" name="declineLeaveButton" id="declineLeaveButton'.$row_leaveList['leavedetails_ID'].'"> <i class="fa fa-close"></i> Decline</button>
                                                                        <button type="button" class="btn btn-primary" name="approveLeaveButton" id="approveLeaveButton'.$row_leaveList['leavedetails_ID'].'"> <i class="fa fa-check-square-o"></i> Approve</button>
                                                                        ';
                                                                            
                                                                        }
                                                                        
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- View MODAL-->
                                                        <div class="modal fade" id="view<?php echo $row_leaveList['leavedetails_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
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
                                                                                    <label for="showLeaveType" class="col-sm-12 control-label">
                                                                                        <h6>Leave Type</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leaveName']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                        <h6>Date</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_leaveFrom']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDuration" class="col-sm-12 control-label">
                                                                                        <h6>Days</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly>
                                                                                    <?php 
                                                                                        if($row_leaveList['leavedetails_duration'] == 1){
                                                                                            echo 1;
                                                                                        }elseif($row_leaveList['leavedetails_duration'] == 0.5){
                                                                                            echo 0.5;
                                                                                        }
                                                                                    ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveReason" class="col-sm-12 control-label">
                                                                                        <h6>Reason</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_reason']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- EDIT MODAL -->
                                                        <div class="modal fade" id="edit<?php echo $row_leaveList['leavedetails_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
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
                                                                                    <label for="editLeaveType" class="col-sm-12 control-label">
                                                                                        <h6>Leave Type</h6>
                                                                                    </label>
                                                                                    <select class="form-control col-md-12" data-plugin="selectpicker" id="leaveTypeUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveType1">
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
                                                                                                        echo "<option value='". $row_leaveType['leaveID'] ."'>". $row_leaveType['leaveName'] ."</option>";
                                                                                                    }
                                                                                                }
                                                                                                // ----------------
                                                                                                //echo "<option value='". $row_leaveType['leaveID'] ."'";
                                                                                                    if($row_leaveType['leaveID'] == $row_leaveList['leaveID']){
                                                                                                        echo " selected";
                                                                                                    }
                                                                                                
                                                                                                echo ">". $row_leaveType['leaveName'] ."</option>";
                                                                                            }
                                                                                                    
                                                                                                    $orginalDateFormat=$row_leaveList['leavedetails_leaveFrom'];
                                                                                                    $newDateFormat = date("m-d-Y", strtotime($orginalDateFormat));
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                        <h6>Date</h6>
                                                                                    </label>
                                                                                    <div class="col-md-12 input-group input-daterange preferredleaveDate currentDate" data-plugin="datepicker">
                                                                                        <input type="text" id="leaveDateUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveDate1" class="form-control datepicker" autocomplete="off" value="<?php echo $newDateFormat; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDuration" class="col-sm-12 control-label">
                                                                                        <h6>Days</h6>
                                                                                    </label>
                                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<i class='fas fa-exclamation-circle' style='color:yellow'></i><?php echo $row_leaveType['leaveName']; ?>">
                                                                                        <select class="form-control col-md-12" data-plugin="selectpicker" id="leaveDurationUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveDuration1">
                                                                                            <option value="1" <?php if($row_leaveList['leavedetails_duration']==1){echo 'selected';} ?>>1</option>
                                                                                            <option value="0.5" <?php if($row_leaveList['leavedetails_duration']==0.5){echo 'selected';} ?>>0.5</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveReason" class="col-sm-12 control-label">
                                                                                        <h6>Reason</h6>
                                                                                    </label>
                                                                                    <div class="col-md-12 input-group">
                                                                                        <input type="text" id="leaveReasonUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveReason1" class="form-control" autocomplete="off" value="<?php echo $row_leaveList['leavedetails_reason'] ;?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveApprover" class="col-sm-12 control-label">
                                                                                        <h6>Approver</h6>
                                                                                    </label>
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

                                            <?php
        //approve leave ajax
        echo "
        <script type='text/javascript'>
        $(document).ready(function() {
        $('#approveLeaveButton". $row_leaveList['leavedetails_ID'] ."').on('click', function() {

        Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to accept this leave request?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
        
        var leaveApprover = $('#adminIdReview" . $row_leaveList['leavedetails_ID'] . "').val();
        var leaveNotes = $('#leaveNotesReview". $row_leaveList['leavedetails_ID'] ."').val();
        var leaveGroupID = $('#approveLeaveGroupReviewID". $row_leaveList['leavedetails_ID'] ."').val();

        // $('#closeReview". $row_leaveList['leavedetails_ID'] ."').click();
        // $('#review". $row_leaveList['leavedetails_ID'] ."').modal('hide');

        //alert(leaveNotes + leaveApprover);
        $.ajax({
        method: 'POST',
        url: 'leave_approveLeaveProcess.php',
        data: {
        leaveNotes:leaveNotes,
        leaveApprover:leaveApprover,
        leaveGroupID:leaveGroupID
        },
        dataType: 'text',
        success: function(response) {
        $('#displayResult').html(response);
        swal('Leave Request Approved', {
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: false
        }, 'success');
             $('#displayResult').html(response);
             
        }
        });
        // location.reload(true);
        
        } else {
            $('#review". $row_leaveList['leavedetails_ID'] ."').modal('show');
        }
        });
        
        $('#review". $row_leaveList['leavedetails_ID'] ."').modal('toggle');
        // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
        
        });
        
        
        //update leave request ajax
        $('#updateLeaveRequestButton". $row_leaveList['leavedetails_ID'] ."').on('click', function() {
            var leaveType = $('#leaveTypeUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveDate = $('#leaveDateUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveDuration = $('#leaveDurationUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveReason = $('#leaveReasonUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveApprover = $('#adminIdUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leavedetailsID = $('#leavedetailsIdUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            //alert(leaveType + leaveDate + leaveDuration + leaveReason + leaveApprover);
            $.ajax({
                method: 'POST',
                url: 'leave_updateLeaveRequestProcess.php',
                data: {
                    leaveType: leaveType,
                    leaveDate: leaveDate,
                    leaveDuration: leaveDuration,
                    leaveReason: leaveReason,
                    leaveApprover: leaveApprover,
                    leavedetailsID: leavedetailsID
                },
                dataType: 'text',
                success: function(response) {
                    $('#displayResult').html(response);
                    
                    // $('#showAttendanceUpdatesModal').modal('hide');
                    //   document.getElementById('modifyAttendance').submit();
                }
            });
            $('#edit". $row_leaveList['leavedetails_ID'] ."').modal('toggle');
            // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
            location.reload(true);
        });
        
        
        $('#declineLeaveButton". $row_leaveList['leavedetails_ID'] ."').on('click', function() {

        Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to decline this leave request?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
        
        var leaveApprover = $('#adminIdReview" . $row_leaveList['leavedetails_ID'] . "').val();
        var leaveNotes = $('#leaveNotesReview". $row_leaveList['leavedetails_ID'] ."').val();
        var leaveGroupID = $('#approveLeaveGroupReviewID". $row_leaveList['leavedetails_ID'] ."').val();

        // $('#closeReview". $row_leaveList['leavedetails_ID'] ."').click();
        // $('#review". $row_leaveList['leavedetails_ID'] ."').modal('hide');

        //alert(leaveNotes + leaveApprover);
        $.ajax({
        method: 'POST',
        url: 'leave_declineLeaveProcess.php',
        data: {
        leaveNotes: leaveNotes,
        leaveApprover: leaveApprover,
        leaveGroupID: leaveGroupID
        },
        dataType: 'text',
        success: function(response) {
        $('#displayResult').html(response);
       
        }
        });
        //location.reload();
        respond='Leave request declined.';
        } else {
            $('#review". $row_leaveList['leavedetails_ID'] ."').modal('show');
        }
        });
        $('#review". $row_leaveList['leavedetails_ID'] ."').modal('toggle');
        // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
        sessionStorage.message = respond;
        location.reload(true)
        });
        
        $('#cancelLeaveRequest". $row_leaveList['leavedetails_ID'] ."').on('click',function(){
            Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to cancel this leave request?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6'
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
        });
        </script>";                              
                                                        
                                                    }//end of while loop
                                                }
                                            ?>
                                            <!-- END TEMPORARY VERSION -->
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
                                                    <select class="form-control col-md-12" data-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Select Range" tab-index="-1" width="auto" id="showDateRange2">
                                                        <option value=""></option>
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
                                        <button type="button" data-toggle="modal" data-target="#applyLeaveModal" class="btn btn-block btn-info ">File Leave</button>
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
                                        $query_leaveList = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID  INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leavedetails.employeeID = '$user' ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";

                                        $stmt = $con->prepare($query_leaveList);
                                        $stmt->execute();
                                        $num = $stmt->rowCount();

                                        if($num>0){
    
                                            while ($row_leaveList = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                if($row_leaveList['leaveGroup_status'] == 'Approved' || $row_leaveList['leaveGroup_status'] == 'Declined'){
                                                                    $viewEditButton = "view";
                                                                }else{
                                                                    $viewEditButton = "edit";
                                                                }
                                                echo " <tr>
                                                       <td>". $row_leaveList['leaveGroup_dateFiled'] ."</td>
                                                       <td>". $row_leaveList['leaveName'] ."</td>
                                                        <td>". $row_leaveList['leavedetails_leaveFrom'] ."</td>
                                                        <td>". $row_leaveList['leaveGroup_duration'] ." day</td>
                                                        <td>". $row_leaveList['leaveGroup_status'] ."</td>";
                                                        if(isset($row_leaveList['leaveGroup_dateApproved'])){
                                                        echo "<td>". $row_leaveList['leaveGroup_dateApproved'] ."</td>";
                                                        }else{
                                                        echo "<td>----------------------</td>";
                                                        }
                                                        echo " <td><button type='button' id='cancelLeaveRequest".$row_leaveList['leavedetails_ID'] ."' class='btn btn-block btn-danger'>Cancel</button>";
echo
                                                                        " <button type='button' data-target='#". $viewEditButton.$row_leaveList['leavedetails_ID'] ."' data-toggle='modal' class='btn btn-block btn-success'>View / Edit</button></td>";
                                                 echo"   </tr>";
                                             ?>

                                            <!-- MODALS-->
                                                        <!-- Review MODAL-->
                                                        <div class="modal fade" id="review<?php echo $row_leaveList['leavedetails_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="reviewLeaveModalLabel" aria-hidden="true">
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
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leaveName']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveDate" class="col-sm-12 control-label">
                                                                                        <h6>Date</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_leaveFrom']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveDuration" class="col-sm-12 control-label">
                                                                                        <h6>Hours</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_duration']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveReason" class="col-sm-12 control-label">
                                                                                        <h6>Reason</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_reason']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveApprover" class="col-sm-12 control-label">
                                                                                        <h6>Approver</h6>
                                                                                    </label>
                                                                                    
                                                                                    <?php
                                                                                        if($row_leaveList['leaveGroup_status']=="Pending"){
                                                                                    ?>
                                                                                        <select class="form-control col-md-12" data-plugin="selectpicker" id="adminIdReview<?php echo $row_leaveList['leavedetails_ID'] ;?>" name="adminId" data-placeholder="Select Employee">
                                                                                            <?php include ('searchAdminList.php');?>
                                                                                        </select>
                                                                                    <?php
                                                                                        }else{
                                                                                            $approverID = $row_leaveList['leaveGroup_approver'];
                                                                                            $query_getApprover = "SELECT lastName, firstName, middleName FROM tbl_employees WHERE employeeID=:approverID";
                                                                                            $stmt_getApprover = $con->prepare($query_getApprover);
                                                                                            
                                                                                            $stmt_getApprover->bindParam(':approverID', $approverID);
                                                                                            if($stmt_getApprover->execute()){
                                                                                                while($row_approverName = $stmt_getApprover->fetch(PDO::FETCH_ASSOC)){
                                                                                    ?>
                                                                                        <div class="form-control" readonly><?php echo $row_approverName['lastName'].", ".$row_approverName['firstName']." ".$row_approverName['middleName']; ?></div>
                                                                                    <?php 
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="reviewLeaveNotes" class="col-sm-12 control-label">
                                                                                        <h6>Notes</h6>
                                                                                    </label>
                                                                                    <input type="hidden" id="approveLeaveGroupReviewID<?php echo $row_leaveList['leavedetails_ID'] ;?>" name="approveLeaveGroupID" value="<?php echo $row_leaveList['leaveGroup_ID']; ?>" />
                                                                                    <?php
                                                                                        if($row_leaveList['leaveGroup_status']=="Pending"){
                                                                                    ?>
                                                                                        <textarea class="form-control" id="leaveNotesReview<?php echo $row_leaveList['leavedetails_ID'] ;?>" name="leaveNotes"></textarea>
                                                                                    <?php
                                                                                        }else{
                                                                                    ?>
                                                                                        <textarea class="form-control" readonly><?php echo $row_leaveList['leaveGroup_notes']; ?></textarea>
                                                                                    <?php 
                                                                                        }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" id="closeReview<?php echo $row_leaveList['leavedetails_ID'] ;?>" class="btn btn-default btn-outline" data-dismiss="modal"> Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- View MODAL-->
                                                        <div class="modal fade" id="view<?php echo $row_leaveList['leavedetails_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
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
                                                                                    <label for="showLeaveType" class="col-sm-12 control-label">
                                                                                        <h6>Leave Type</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leaveName']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                        <h6>Date</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_leaveFrom']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDuration" class="col-sm-12 control-label">
                                                                                        <h6>Days</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly>
                                                                                    <?php 
                                                                                        if($row_leaveList['leavedetails_duration'] == 1){
                                                                                            echo 1;
                                                                                        }elseif($row_leaveList['leavedetails_duration'] == 0.5){
                                                                                            echo 0.5;
                                                                                        }
                                                                                    ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveReason" class="col-sm-12 control-label">
                                                                                        <h6>Reason</h6>
                                                                                    </label>
                                                                                    <div class="form-control" readonly><?php echo $row_leaveList['leavedetails_reason']; ?></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- EDIT MODAL -->
                                                        <div class="modal fade" id="edit<?php echo $row_leaveList['leavedetails_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
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
                                                                                    <label for="editLeaveType" class="col-sm-12 control-label">
                                                                                        <h6>Leave Type</h6>
                                                                                    </label>
                                                                                    <select class="form-control col-md-12" data-plugin="selectpicker" id="leaveTypeUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveType1">
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
                                                                                                        echo "<option value='". $row_leaveType['leaveID'] ."'>". $row_leaveType['leaveName'] ."</option>";
                                                                                                    }
                                                                                                }
                                                                                                // ----------------
                                                                                                //echo "<option value='". $row_leaveType['leaveID'] ."'";
                                                                                                    if($row_leaveType['leaveID'] == $row_leaveList['leaveID']){
                                                                                                        echo " selected";
                                                                                                    }
                                                                                                
                                                                                                echo ">". $row_leaveType['leaveName'] ."</option>";
                                                                                            }
                                                                                                    
                                                                                                    $orginalDateFormat=$row_leaveList['leavedetails_leaveFrom'];
                                                                                                    $newDateFormat = date("m-d-Y", strtotime($orginalDateFormat));
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                                        <h6>Date</h6>
                                                                                    </label>
                                                                                    <div class="col-md-12 input-group input-daterange preferredleaveDate currentDate" data-plugin="datepicker">
                                                                                        <input type="text" id="leaveDateUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveDate1" class="form-control datepicker" autocomplete="off" value="<?php echo $newDateFormat; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveDuration" class="col-sm-12 control-label">
                                                                                        <h6>Days</h6>
                                                                                    </label>
                                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<i class='fas fa-exclamation-circle' style='color:yellow'></i><?php echo $row_leaveType['leaveName']; ?>">
                                                                                        <select class="form-control col-md-12" data-plugin="selectpicker" id="leaveDurationUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveDuration1">
                                                                                            <option value="1" <?php if($row_leaveList['leavedetails_duration']==1){echo 'selected';} ?>>1</option>
                                                                                            <option value="0.5" <?php if($row_leaveList['leavedetails_duration']==0.5){echo 'selected';} ?>>0.5</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveReason" class="col-sm-12 control-label">
                                                                                        <h6>Reason</h6>
                                                                                    </label>
                                                                                    <div class="col-md-12 input-group">
                                                                                        <input type="text" id="leaveReasonUpdate<?php echo $row_leaveList['leavedetails_ID'] ?>" name="leaveReason1" class="form-control" autocomplete="off" value="<?php echo $row_leaveList['leavedetails_reason'] ;?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="showLeaveApprover" class="col-sm-12 control-label">
                                                                                        <h6>Approver</h6>
                                                                                    </label>
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

                                            <?php echo "
        <script type='text/javascript'>
        $(document).ready(function() {
        $('#approveLeaveButton". $row_leaveList['leavedetails_ID'] ."').on('click', function() {

        Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to accept this leave request?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
        
        var leaveApprover = $('#adminIdReview" . $row_leaveList['leavedetails_ID'] . "').val();
        var leaveNotes = $('#leaveNotesReview". $row_leaveList['leavedetails_ID'] ."').val();
        var leaveGroupID = $('#approveLeaveGroupReviewID". $row_leaveList['leavedetails_ID'] ."').val();

        // $('#closeReview". $row_leaveList['leavedetails_ID'] ."').click();
        // $('#review". $row_leaveList['leavedetails_ID'] ."').modal('hide');

        //alert(leaveNotes + leaveApprover);
        $.ajax({
        method: 'POST',
        url: 'leave_approveLeaveProcess.php',
        data: {
        leaveNotes:leaveNotes,
        leaveApprover:leaveApprover,
        leaveGroupID:leaveGroupID
        },
        dataType: 'text',
        success: function(response) {
        $('#displayResult').html(response);
        swal('Leave Request Approved', {
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: false
        }, 'success');
             $('#displayResult').html(response);
             
        }
        });
        // location.reload(true);
        
        } else {
            $('#review". $row_leaveList['leavedetails_ID'] ."').modal('show');
        }
        });
        
        $('#review". $row_leaveList['leavedetails_ID'] ."').modal('toggle');
        // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
        
        });
        
        
        //update leave request ajax
        $('#updateLeaveRequestButton". $row_leaveList['leavedetails_ID'] ."').on('click', function() {
            var leaveType = $('#leaveTypeUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveDate = $('#leaveDateUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveDuration = $('#leaveDurationUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveReason = $('#leaveReasonUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leaveApprover = $('#adminIdUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            var leavedetailsID = $('#leavedetailsIdUpdate". $row_leaveList['leavedetails_ID'] ."').val();
            //alert(leaveType + leaveDate + leaveDuration + leaveReason + leaveApprover);
            $.ajax({
                method: 'POST',
                url: 'leave_updateLeaveRequestProcess.php',
                data: {
                    leaveType: leaveType,
                    leaveDate: leaveDate,
                    leaveDuration: leaveDuration,
                    leaveReason: leaveReason,
                    leaveApprover: leaveApprover,
                    leavedetailsID: leavedetailsID
                },
                dataType: 'text',
                success: function(response) {
                    $('#displayResult').html(response);
                    
                    // $('#showAttendanceUpdatesModal').modal('hide');
                    //   document.getElementById('modifyAttendance').submit();
                }
            });
            $('#edit". $row_leaveList['leavedetails_ID'] ."').modal('toggle');
            // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
            location.reload(true);
        });
        
        
        $('#declineLeaveButton". $row_leaveList['leavedetails_ID'] ."').on('click', function() {

        Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to decline this leave request?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
        }).then((result) => {
        if (result.value) {
        
        var leaveApprover = $('#adminIdReview" . $row_leaveList['leavedetails_ID'] . "').val();
        var leaveNotes = $('#leaveNotesReview". $row_leaveList['leavedetails_ID'] ."').val();
        var leaveGroupID = $('#approveLeaveGroupReviewID". $row_leaveList['leavedetails_ID'] ."').val();

        // $('#closeReview". $row_leaveList['leavedetails_ID'] ."').click();
        // $('#review". $row_leaveList['leavedetails_ID'] ."').modal('hide');

        //alert(leaveNotes + leaveApprover);
        $.ajax({
        method: 'POST',
        url: 'leave_declineLeaveProcess.php',
        data: {
        leaveNotes: leaveNotes,
        leaveApprover: leaveApprover,
        leaveGroupID: leaveGroupID
        },
        dataType: 'text',
        success: function(response) {
        $('#displayResult').html(response);
       
        }
        });
        //location.reload();
        respond='Leave request declined.';
        } else {
            $('#review". $row_leaveList['leavedetails_ID'] ."').modal('show');
        }
        });
        $('#review". $row_leaveList['leavedetails_ID'] ."').modal('toggle');
        // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
        sessionStorage.message = respond;
        location.reload(true)
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
        });
        </script>";  }  
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

        <!-- Leave Configuration Tab -->
        <div class="tab-pane fade" id="custom-content-below-leaveConfiguration" role="tabpanel" aria-labelledby="custom-content-below-leaveConfiguration-tab">
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
                                    <select onchange="row_check($(this))" class="form-control col-md-12" name="leave_type[]" data-plugin="selectpicker" id="applyLeaveType">
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
    // script for apply leave date limit

    var date = new Date();
    date.setDate(date.getDate() + 7);
    $('#currentDate').datepicker({
        startDate: date
    });
    // end script for apply leave date limit

    // disable dates recorded in database
        // var array = ["2019-10-10","2019-10-20","2019-10-30"];
        // $('#currentDate').datepicker({
        //     beforeShowDay: function(date){
        //         var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        //         return [ array.indexOf(string) == -1 ];
        //     }
        // });
    // 

    $(document).ready(function() {
        
        // $('.date-range').datepicker({
        //   todayBtn:'linked',
        //   autoclose: true
        // });
        
        
        // alert(localStorage.message);
        // if(sessionStorage.message)
        // {
        //     var messageToast = sessionStorage.message;
        //     // alert(messageToast);
        //     toastr.success(messageToast, "Success!");
        //     // Toaster.show(localStorage.message);
        //     sessionStorage.clear();
        // }
        
        // $('#showLeaveTable').destroy();
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
        
        // // approval request to user
        // $('#userApprovalRequest').('click',function(){
            
        // });
        
        //apply leave ajax
        $('#applyLeaveButton').on('click', function() {
            var leaveType = $('#applyLeaveType').val();
            var leaveDate = $('#applyLeaveDate').val();
            var leaveDuration = $('#applyLeaveDuration').val();
            var leaveReason = $('#applyLeaveReason').val();
            var leaveApprover = $('#applyLeaveApprover').val();
            
            var respond = "Leave request sent.";
            //alert(leaveType +" "+ leaveDate +" "+ leaveDuration +" "+ leaveReason +" "+ leaveApprover);
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
            // sessionStorage.message = respond;
            // location.reload();
            // $("#showLeaveTable").load("leave_application.php #showLeaveTable");
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
                // alert(from_date);
            //  var from_date $('#from_date').val();
            //  var from_date = $('#from_date').toISOString();
            //  var to_date = $('#to_date').val();
             
            //  alert(to_date);
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
             }else{
             }
        }); 
        
        //    });


        //auto add leave input fields
        var leaveRowCtr = 1;

        //        priority verseion

        //        $('.leaveData').on('change keyup', function() {
        // var rowID = $(this).attr('data-rowID');
        // $(rowID).addClass('filled');
        // var rowFilled = $(rowID).hasClass('filled');
        // var leaveDate = $(rowID).children('.leaveReason').val();
        // alert(leaveDate);
        //            end priority version

        //            other version

        //            if (($(rowID).not('.filled'))) {
        // //check if leaveDate has value
        // if ($(this).children('.leaveDate').val().length > 0) {
        // alert($(this).children('.leaveDate').val());
        // }
        // // if ($(rowID).children().length == $(rowID).children('.hasInputData').length) {
        // // alert('Assumed all have data');
        // // $(this).addClass('filled');
        // // }
        // } else {
        // alert(rowFilled);
        //
        // }
        //        });
        //end auto add leave row data


        // adding new input fields
        // var leaveCtr = 1;// var leaveID = "#updateLeaveTable";
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

        // bug fix for closing calendar after selection
        $('#currentDate').datepicker({
            autoclose: true
        });
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

    $(document).ready(function() {
            var table = $('#datatable1').DataTable();
            // Setup - add a text input to each footer cell
            /*$('#datatable1 thead tr').clone(true).appendTo( '#datatable1 thead' );
            $('#datatable1 thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                if(title != "") {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
             
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table.column(i).search() !== this.value ) {
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    });
                }
            });*/
         
            
        } );

    

    $(document).ready(function() {
            var table = $('#example3').DataTable( {
                orderCellsTop: true,
                fixedHeader: true
            } );
            // Setup - add a text input to each footer cell
            /*$('#example3 thead tr').clone(true).appendTo( '#example3 thead' );
            $('#example3 thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                if(title != "") {
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
             
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table.column(i).search() !== this.value ) {
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    });
                }
            });*/
         
            
        } );

    


</script>
