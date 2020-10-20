<?php
    include ('../db/connection.php');
    // include ('../includes/header.php');
    session_start();


$leaveApprover      = isset($_POST['leaveApprover']) ? $_POST['leaveApprover'] : '';
$leaveNotes         = isset($_POST['leaveNotes']) ? $_POST['leaveNotes'] : '';
$leaveGroupID       = $_POST['leaveGroupID'];
$leaveDateApproved  = date('Y-m-d');
$leaveStatus        = "Approved";

$query_checkLeaveStatus = "SELECT tbl_leaveGroup.employeeID, tbl_leavedetails.leaveID, tbl_leaveGroup.leaveGroup_ID,tbl_leaveGroup.leaveGroup_status, tbl_leavedetails.leavedetails_duration FROM tbl_leaveGroup INNER JOIN tbl_leavedetails ON tbl_leaveGroup.leaveGroup_ID=tbl_leavedetails.leaveGroup_ID WHERE tbl_leaveGroup.leaveGroup_ID=:leavedetailsID";
$stmt_checkLeaveStatus = $con->prepare($query_checkLeaveStatus);
$stmt_checkLeaveStatus->bindParam(':leavedetailsID',$leaveGroupID);

if($stmt_checkLeaveStatus->execute()){
    $retractedPending = false;
    while($row_checkLeaveStatus=$stmt_checkLeaveStatus->fetch(PDO::FETCH_ASSOC)){
        $leaveDuration = $row_checkLeaveStatus['leavedetails_duration'];
        $leaveID = $row_checkLeaveStatus['leaveID'];
        $employeeID = $row_checkLeaveStatus['employeeID'];
        $leaveGroupID = $row_checkLeaveStatus['leaveGroup_ID'];
        $old_status = $row_checkLeaveStatus['leaveGroup_status'];

        if ($old_status == "Retracted Pending") {
            $retractedPending = true;
            $leavedetailsID = $_POST['leaveGroupID'];
            $query_leaveInfo = "SELECT * FROM tbl_leaveinfo WHERE leaveID=:leaveID AND employeeID=:employeeID";
            $stmt_leaveInfo = $con->prepare($query_leaveInfo);
            
            $stmt_leaveInfo->bindParam(':leaveID',$leaveID);
            $stmt_leaveInfo->bindParam(':employeeID',$employeeID);
            
            if($stmt_leaveInfo->execute()){
                while($row_leaveInfo = $stmt_leaveInfo->fetch(PDO::FETCH_ASSOC)){
                    $updateLeaveRemaining = $row_leaveInfo['leaveRemaining'] + $leaveDuration;
                    $updateLeaveUsed = $row_leaveInfo['leaveUsed'] - $leaveDuration;
                    
                    $query_approveInfo = "UPDATE tbl_leaveinfo SET leaveRemaining=:leaveRemaining, leaveUsed=:leaveUsed WHERE employeeID=:employeeID AND leaveID=:leaveID";
                    $stmt_approveInfo = $con->prepare($query_approveInfo);
                    
                    $stmt_approveInfo->bindParam(':leaveRemaining',$updateLeaveRemaining);
                    $stmt_approveInfo->bindParam(':leaveUsed',$updateLeaveUsed);
                    $stmt_approveInfo->bindParam(':employeeID',$employeeID);
                    $stmt_approveInfo->bindParam(':leaveID',$leaveID);
                    
                    $stmt_approveInfo->execute();
                }
                $query_removeLeaveGroup = "Update tbl_leaveGroup set leaveGroup_status = 'Cancelled' WHERE leaveGroup_ID = :leaveGroupID";
                $stmt_removeLeaveGroup = $con->prepare($query_removeLeaveGroup);
                
                $stmt_removeLeaveGroup->bindParam(':leaveGroupID',$leaveGroupID);
                $stmt_removeLeaveGroup->execute();
            }
        }
    }
    if($retractedPending) {
        die();
    }
}

$query_approveLeave = "UPDATE tbl_leaveGroup SET leaveGroup_approver=:leaveApprover, leaveGroup_notes=:leaveNotes, leaveGroup_status=:leaveStatus, leaveGroup_dateApproved=:leaveDateApproved WHERE leaveGroup_ID=:leaveGroupID";
$stmt_approveLeave=$con->prepare($query_approveLeave);

$stmt_approveLeave->bindParam(':leaveApprover', $leaveApprover);
$stmt_approveLeave->bindParam(':leaveNotes', $leaveNotes);
$stmt_approveLeave->bindParam(':leaveGroupID', $leaveGroupID);
$stmt_approveLeave->bindParam(':leaveDateApproved', $leaveDateApproved);
$stmt_approveLeave->bindParam(':leaveStatus', $leaveStatus);

if($stmt_approveLeave->execute()){
    //echo '<div class="panel-title text-success">Leave Request has been approved.</div>';
    
    $query_getEmployeeID = "SELECT employeeID, leaveID, leavedetails_duration FROM tbl_leavedetails WHERE leaveGroup_ID=:leaveGroupID";
    $stmt_getEmployeeID = $con->prepare($query_getEmployeeID);
    
    $stmt_getEmployeeID->bindParam(':leaveGroupID', $leaveGroupID);
    
    if($stmt_getEmployeeID->execute()){
        $duration = 0;
        while($row_getEmployeeID=$stmt_getEmployeeID->fetch(PDO::FETCH_ASSOC)){
            $employeeID = $row_getEmployeeID['employeeID'];
            $leaveID = $row_getEmployeeID['leaveID'];
            $duration = $row_getEmployeeID['leavedetails_duration'];
        
            $query_getLeaveInfoValues = "SELECT * FROM tbl_leaveinfo WHERE employeeID=:employeeID AND leaveID=:leaveID";
            $stmt_getLeaveInfoValues = $con->prepare($query_getLeaveInfoValues);
            
            $stmt_getLeaveInfoValues->bindParam(':employeeID',$employeeID);
            $stmt_getLeaveInfoValues->bindParam(':leaveID',$leaveID);
            
            if($stmt_getLeaveInfoValues->execute()){
                while($row_getLeaveInfoValues=$stmt_getLeaveInfoValues->fetch(PDO::FETCH_ASSOC)){
                    $leaveRemaining = $row_getLeaveInfoValues['leaveRemaining'];
                    $pendingLeaves = $row_getLeaveInfoValues['pendingLeave'];
                    $leaveUsed = $row_getLeaveInfoValues['leaveUsed'];
                }
                $newLeaveRemaining = $leaveRemaining - $duration;
                $newPendingLeaves = $pendingLeaves - $duration;
                $newLeaveUsed = $leaveUsed + $duration;
                
                $query_updateLeaveInfo = "UPDATE tbl_leaveinfo SET leaveUsed=:leaveUsed, leaveRemaining=:leaveRemaining, pendingLeave=:pendingLeave WHERE employeeID=:employeeID AND leaveID=:leaveID";
                $stmt_updateLeaveInfo=$con->prepare($query_updateLeaveInfo);
                
                $stmt_updateLeaveInfo->bindParam(':leaveUsed',$newLeaveUsed);
                $stmt_updateLeaveInfo->bindParam(':leaveRemaining',$newLeaveRemaining);
                $stmt_updateLeaveInfo->bindParam(':pendingLeave',$newPendingLeaves);
                $stmt_updateLeaveInfo->bindParam(':employeeID',$employeeID);
                $stmt_updateLeaveInfo->bindParam(':leaveID',$leaveID);
                
                $stmt_updateLeaveInfo->execute();
            }
        }

        echo '<div class="alert alert-success">Leave Request has been approved.</div>';
    }
    
}else{
    echo 'Error. Failed to execute.';
}
?>
