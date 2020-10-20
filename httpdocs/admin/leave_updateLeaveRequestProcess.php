<?php
    include ('../db/connection.php');
    // include ('../includes/header.php');
    session_start();
    
    $userID = $_SESSION['user_id'];
    
    $duration = 0;
    foreach($_POST['leave_delail_id'] as $key=>$leave_delail_id) {
        $leaveType = $_POST['leaveType'][$key];
        $leaveDate = strtotime($_POST['leaveDate'][$key]);
        $leaveDate = date('Y-m-d',$leaveDate);
        $leaveDuration = $_POST['leaveDuration'][$key];
        $leaveReason = $_POST['leaveReason'][$key];
        $leaveID = $leave_delail_id;
        
        $query_updateLeaveRequest = "UPDATE tbl_leavedetails SET leaveID = :leaveType, leavedetails_leaveFrom = :leaveDate, leavedetails_duration = :leaveDuration, leavedetails_reason = :leaveReason WHERE leavedetails_ID = :leaveID";
        $stmt_updateLeaveRequest = $con->prepare($query_updateLeaveRequest);
        
        $stmt_updateLeaveRequest->bindparam(':leaveType',$leaveType);
        $stmt_updateLeaveRequest->bindparam(':leaveDate',$leaveDate);
        $stmt_updateLeaveRequest->bindparam(':leaveDuration',$leaveDuration);
        $stmt_updateLeaveRequest->bindparam(':leaveReason',$leaveReason);
        $stmt_updateLeaveRequest->bindparam(':leaveID',$leaveID);
        $stmt_updateLeaveRequest->execute();

        $duration += $leaveDuration;
        
        $query_updateLeaveRequest = "UPDATE tbl_leaveGroup SET leaveGroup_duration = ".$duration." WHERE leaveGroup_ID = ".$_POST['leave_group_id'];

        $stmt_updateLeaveRequest = $con->prepare($query_updateLeaveRequest);
        $stmt_updateLeaveRequest->execute();
    }

    echo "<div class='panel-title text-success'>Leave Request Updated.</div>";

?>