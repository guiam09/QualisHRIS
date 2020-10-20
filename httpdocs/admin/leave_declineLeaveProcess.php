<?php
    include ('../db/connection.php');
    // include ('../includes/header.php');
    session_start();

     $leaveApprover     = isset($_POST['leaveApprover']) ? $_POST['leaveApprover'] : '';
     $leaveNotes        = isset($_POST['leaveNotes']) ? $_POST['leaveNotes'] : '';
     $leaveGroupID      = $_POST['leaveGroupID'];
     $leaveDateApproved = date('Y-m-d');
     $leaveStatus       = "Declined";
    
    $query_returnPendingLeave = "SELECT leavedetails_duration, leaveID, employeeID FROM tbl_leavedetails WHERE leaveGroup_ID=:leaveGroupID";
    $stmt_returnPendingLeave = $con->prepare($query_returnPendingLeave);
    
    $stmt_returnPendingLeave->bindParam(':leaveGroupID',$leaveGroupID);
    
    $query_checkLeaveStatus = "SELECT tbl_leaveGroup.employeeID, tbl_leavedetails.leaveID, tbl_leaveGroup.leaveGroup_ID,tbl_leaveGroup.leaveGroup_status, tbl_leaveGroup.leaveGroup_duration FROM tbl_leaveGroup INNER JOIN tbl_leavedetails ON tbl_leaveGroup.leaveGroup_ID=tbl_leavedetails.leaveGroup_ID WHERE tbl_leaveGroup.leaveGroup_ID=:leavedetailsID";
    $stmt_checkLeaveStatus = $con->prepare($query_checkLeaveStatus);
    $stmt_checkLeaveStatus->bindParam(':leavedetailsID',$leaveGroupID);

    if($stmt_checkLeaveStatus->execute()){
        while($row_checkLeaveStatus=$stmt_checkLeaveStatus->fetch(PDO::FETCH_ASSOC)){
            
             $leaveDuration = $row_checkLeaveStatus['leaveGroup_duration'];
             $leaveID = $row_checkLeaveStatus['leaveID'];
             $employeeID = $row_checkLeaveStatus['employeeID'];
             $leaveGroupID = $row_checkLeaveStatus['leaveGroup_ID'];
            $old_status = $row_checkLeaveStatus['leaveGroup_status'];
        }

        if ($old_status == "Retracted Pending") {
            $query_removeLeaveGroup = "UPDATE tbl_leaveGroup set leaveGroup_status = 'Retracted Declined' WHERE leaveGroup_ID = :leaveGroupID";
            $stmt_removeLeaveGroup = $con->prepare($query_removeLeaveGroup);
                
            $stmt_removeLeaveGroup->bindParam(':leaveGroupID',$leaveGroupID);
            $stmt_removeLeaveGroup->execute();

            echo '<div class="alert alert-success">Leave Request has been declined.</div>'; 
            die();
        }
    }

    if($stmt_returnPendingLeave->execute()){
        while($row_returnPendingLeave=$stmt_returnPendingLeave->fetch(PDO::FETCH_ASSOC)){
            $duration = $row_returnPendingLeave['leavedetails_duration'];
            $leaveID = $row_returnPendingLeave['leaveID'];
            $employeeID = $row_returnPendingLeave['employeeID'];
        }
        
        $query_getLeaveInfo = "SELECT pendingLeave FROM tbl_leaveinfo WHERE leaveID=:leaveID AND employeeID=:employeeID";
        $stmt_getLeaveInfo = $con->prepare($query_getLeaveInfo);
        
        $stmt_getLeaveInfo->bindParam(':leaveID',$leaveID);
        $stmt_getLeaveInfo->bindParam(':employeeID',$employeeID);
        
        if($stmt_getLeaveInfo->execute()){
            while($row_getLeaveInfo=$stmt_getLeaveInfo->fetch(PDO::FETCH_ASSOC)){
                $pendingLeave = $row_getLeaveInfo['pendingLeave'];
            }
            
             $newPendingLeave = $pendingLeave - $duration;
            
            $query_updateLeaveInfo = "UPDATE tbl_leaveinfo SET pendingLeave=:pendingLeave WHERE leaveID=:leaveID AND employeeID=:employeeID";
            $stmt_updateLeaveInfo = $con->prepare($query_updateLeaveInfo);
            
            $stmt_updateLeaveInfo->bindParam(':leaveID',$leaveID);
            $stmt_updateLeaveInfo->bindParam(':employeeID',$employeeID);
            $stmt_updateLeaveInfo->bindParam(':pendingLeave',$newPendingLeave);
            
            if($stmt_getLeaveInfo->execute()){
                $query_declineLeave = "UPDATE tbl_leaveGroup SET leaveGroup_approver=:leaveApprover, leaveGroup_notes=:leaveNotes, leaveGroup_status=:leaveStatus, leaveGroup_dateApproved=:leaveDateApproved WHERE leaveGroup_ID=:leaveGroupID";
                $stmt_declineLeave=$con->prepare($query_declineLeave);
                
                $stmt_declineLeave->bindParam(':leaveApprover', $leaveApprover);
                $stmt_declineLeave->bindParam(':leaveNotes', $leaveNotes);
                $stmt_declineLeave->bindParam(':leaveGroupID', $leaveGroupID);
                $stmt_declineLeave->bindParam(':leaveDateApproved', $leaveDateApproved);
                $stmt_declineLeave->bindParam(':leaveStatus', $leaveStatus);
                
                if($stmt_declineLeave->execute()){
                    echo '<div class="alert alert-success">Leave Request has been declined.</div>';    
                }else{
                    echo 'Error. Failed to execute.';
                }
            }else{
                echo "Error. Failed to execute";
            }
        }
        
    }else{
        echo "Error. Get duration";
    }
    
    

?>