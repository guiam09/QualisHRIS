<?php
    session_start();
    include '../db/connection.php';
    
    $employeeID = $_POST['employeeID'];
    $range = $_POST['range'];
    $from_date = date_create($_POST['from_date']);
    $from_date = date_format($from_date,'Y-m-d');
    $to_date = date_create($_POST['to_date']);
    $to_date = date_format($to_date,'Y-m-d');;

    //$employeeIDEntry = " AND tbl_leaveGroup.employeeID=".$employeeID;
    $userID = $_SESSION['user_id'];
?>
<table id="showLeaveTable" class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
    <thead>
        <tr>
            <th>Date Filed</th>
            <th>Name</th>
            <th>Leave Type</th>
            <th>Leave Date</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Date Approved</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $status = '';
            if (!empty($_POST['status'])) {
                $status = ' AND tbl_leaveGroup.leaveGroup_status = "'.$_POST['status'].'"';
            }
            if($employeeID!=""){
                if($range == "current"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from  FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) AND WEEK(CURDATE()) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "lastWeek"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 1 AND WEEK(CURDATE()) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "lastTwoWeeks"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 2 AND WEEK(CURDATE()) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "lastMonth"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND MONTH (tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN MONTH( CURDATE() ) - 1 AND MONTH( CURDATE() ) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "custom"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' AND tbl_leaveGroup.leaveGroup_dateFiled BETWEEN '$from_date' AND '$to_date' ".$status." group by tbl_leaveGroup.leaveGroup_ID  ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }else{
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.employeeID = '$employeeID' ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }
            }else{
                if($range == "current"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) AND WEEK(CURDATE()) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "lastWeek"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 1 AND WEEK(CURDATE()) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "lastTwoWeeks"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE WEEK(tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN WEEK( CURDATE() ) - 2 AND WEEK(CURDATE()) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "lastMonth"){
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE MONTH (tbl_leaveGroup.leaveGroup_dateFiled) BETWEEN MONTH( CURDATE() ) - 1 AND MONTH( CURDATE() ) ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }elseif($range == "custom"){
                    $query_leaveList = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID WHERE tbl_leaveGroup.leaveGroup_dateFiled BETWEEN '$from_date' AND '$to_date' ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }else{
                    $query_leaveList = "SELECT *,group_concat(leaveName SEPARATOR '<br/>') as leave_details,group_concat(leavedetails_leaveFrom SEPARATOR '<br/>') as leave_from FROM tbl_leavedetails INNER JOIN tbl_leaveGroup ON tbl_leavedetails.leaveGroup_ID=tbl_leaveGroup.leaveGroup_ID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID=tbl_employees.employeeID INNER JOIN tbl_leave ON tbl_leavedetails.leaveID=tbl_leave.leaveID ".$status." group by tbl_leaveGroup.leaveGroup_ID ORDER BY tbl_leaveGroup.leaveGroup_ID DESC LIMIT 50";
                }
            }
                                                
            $stmt_leaveList = $con->prepare($query_leaveList);
                                                if($stmt_leaveList->execute()){
                                                    while($row_leaveList=$stmt_leaveList->fetch(PDO::FETCH_ASSOC)){
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
                                                                
                                                                <td>". $row_leaveList['leave_details'] ."</td>
                                                                <td>". $row_leaveList['leave_from'] ."</td>
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
                                                                    
                                                                    // if($row_leaveList['employeeID'] != $employeeID){
                                                                        echo "<button type='button' data-target='#review".$row_leaveList['leaveGroup_ID'] ."' data-toggle='modal' ";
                                                                        if($row_leaveList['leaveGroup_status']=="Pending" && $employeeID==$row_leaveList['leaveGroup_approver']){
                                                                            echo "class='btn btn-block btn-success'";
                                                                        }else if($row_leaveList['leaveGroup_status']=="Pending"){
                                                                            echo "class='btn btn-block btn-outline-primary'";
                                                                        }else{
                                                                            echo "class='btn btn-block btn-outline-primary'";
                                                                        }
                                                                        
                                                                        
                                                                    echo    ">Review</button>";
                                                                    // }
                                                                    
                                                                    /*if($row_leaveList['employeeID'] == $employeeID){
                                                                        echo
                                                                        " <button type='button' data-target='#". $viewEditButton.$row_leaveList['leavedetails_ID'] ."' data-toggle='modal' class='btn btn-block btn-success'>View / Edit</button>";
                                                                    }*/
                                                                    
                                                                    /*if($row_leaveList['employeeID'] == $employeeID && $row_leaveList['leaveGroup_status']!="Declined"){
                                                                        echo " <button type='button' id='cancelLeaveRequest".$row_leaveList['leavedetails_ID'] ."' class='btn btn-block btn-danger'>Cancel</button>";
                                                                    }*/
                                                                echo "
                                                                </td>
                                                            </tr>
                                                        ";
                                                        ?>
                                            <!-- MODALS-->
                                            <!-- Review MODAL-->
                                            <div class="modal fade" id="review<?php echo $row_leaveList['leaveGroup_ID'] ?>" tabindex="-1" role="dialog" aria-labelledby="reviewLeaveModalLabel" aria-hidden="true">
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
                                                                            <h6>Hours</h6>
                                                                        </label>
                                                                        <div class="form-control" readonly>
                                                                        <?php 
                                                                            if($row_leaveList['leavedetails_duration'] == 1){
                                                                                echo 8;
                                                                            }elseif($row_leaveList['leavedetails_duration'] == 0.5){
                                                                                echo 4;
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

                $('#closeReview". $row_leaveList['leaveGroup_ID'] ."').click();
                $('#review". $row_leaveList['leaveGroup_ID'] ."').modal('hide');

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
                            closeOnClickOutside: true,
                            closeOnEsc: true,
                            buttons: true
                        });
                        $('#displayResult').html(response); 
                        search_table();
                    }
                });
        // location.reload(true);
        
        } else {
            $('#review". $row_leaveList['leavedetails_ID'] ."').modal('show');
        }
        });
        
        $('#review". $row_leaveList['leavedetails_ID'] ."').modal('toggle');
        // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
        // location.reload(true);
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
            // location.reload(true);
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
        // sessionStorage.message = respond;
        // location.reload(true)
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
                    dataType: 'text',
                    success: function(response) {
                    $('#displayResult').html(response);
                   
                    }
                });
            }
            });
            // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
            // location.reload(true);
        });
        });
        </script>";                              
                                                        
                                                    }//end of while loop
                                                }
                                            ?>
                                            <!-- END TEMPORARY VERSION -->
                                        </tbody>
                                    </table>
