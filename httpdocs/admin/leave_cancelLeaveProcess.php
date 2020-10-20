<?php
    include ('../db/connection.php');
    // include ('../includes/header.php');
    session_start();
      
    $leavedetailsID = $_POST['leavedetailsID'];
    
    $query_checkLeaveStatus = "SELECT tbl_leaveGroup.employeeID, tbl_leavedetails.leaveID, tbl_leaveGroup.leaveGroup_ID,tbl_leaveGroup.leaveGroup_status, tbl_leaveGroup.leaveGroup_duration FROM tbl_leaveGroup INNER JOIN tbl_leavedetails ON tbl_leaveGroup.leaveGroup_ID=tbl_leavedetails.leaveGroup_ID WHERE tbl_leaveGroup.leaveGroup_ID=:leavedetailsID";
    $stmt_checkLeaveStatus = $con->prepare($query_checkLeaveStatus);
    
    $stmt_checkLeaveStatus->bindParam(':leavedetailsID',$leavedetailsID);
    
    if($stmt_checkLeaveStatus->execute()){
        while($row_checkLeaveStatus=$stmt_checkLeaveStatus->fetch(PDO::FETCH_ASSOC)){
            
             $leaveDuration = $row_checkLeaveStatus['leaveGroup_duration'];
             $leaveID = $row_checkLeaveStatus['leaveID'];
             $employeeID = $row_checkLeaveStatus['employeeID'];
             $leaveGroupID = $row_checkLeaveStatus['leaveGroup_ID'];
            
            if($row_checkLeaveStatus['leaveGroup_status']=="Approved"){
                $leaveStatus = "Approved";

            }elseif($row_checkLeaveStatus['leaveGroup_status']=="Pending"){
                $leaveStatus = "Pending";
            }
        }
        
        if ($leaveStatus == "Approved") {
            $query_removeLeaveGroup = "UPDATE tbl_leaveGroup set leaveGroup_status = 'Retracted Pending' WHERE leaveGroup_ID = :leaveGroupID";
            $stmt_removeLeaveGroup = $con->prepare($query_removeLeaveGroup);
                
            $stmt_removeLeaveGroup->bindParam(':leaveGroupID',$leaveGroupID);
            $stmt_removeLeaveGroup->execute();
            die("canceled");
        }
        
        $query_leaveInfo = "SELECT * FROM tbl_leaveinfo WHERE leaveID=:leaveID AND employeeID=:employeeID";
        $stmt_leaveInfo = $con->prepare($query_leaveInfo);
        
        $stmt_leaveInfo->bindParam(':leaveID',$leaveID);
        $stmt_leaveInfo->bindParam(':employeeID',$employeeID);
        
        if($stmt_leaveInfo->execute()){
            while($row_leaveInfo = $stmt_leaveInfo->fetch(PDO::FETCH_ASSOC)){
                if($leaveStatus == "Pending"){
                    $updatePendingLeave = $row_leaveInfo['pendingLeave'] - $leaveDuration;
                    
                    $query_pendingInfo = "UPDATE tbl_leaveinfo SET pendingLeave=:pendingLeave WHERE employeeID=:employeeID AND leaveID=:leaveID";
                    $stmt_pendingInfo = $con->prepare($query_pendingInfo);
                    
                    $stmt_pendingInfo->bindParam(':pendingLeave',$updatePendingLeave);
                    $stmt_pendingInfo->bindParam(':employeeID',$employeeID);
                    $stmt_pendingInfo->bindParam(':leaveID',$leaveID);
                    
                    if($stmt_pendingInfo->execute()){
                        // echo "Success";
                    }else{
                        echo "Error. Failed to approve.";
                    }
                    
                }elseif($leaveStatus == "Approved"){
                    $updateLeaveRemaining = $row_leaveInfo['leaveRemaining'] + $leaveDuration;
                    $updateLeaveUsed = $row_leaveInfo['leaveUsed'] - $leaveDuration;
                    
                    $query_approveInfo = "UPDATE tbl_leaveinfo SET leaveRemaining=:leaveRemaining, leaveUsed=:leaveUsed WHERE employeeID=:employeeID AND leaveID=:leaveID";
                    $stmt_approveInfo = $con->prepare($query_approveInfo);
                    
                    $stmt_approveInfo->bindParam(':leaveRemaining',$updateLeaveRemaining);
                    $stmt_approveInfo->bindParam(':leaveUsed',$updateLeaveUsed);
                    $stmt_approveInfo->bindParam(':employeeID',$employeeID);
                    $stmt_approveInfo->bindParam(':leaveID',$leaveID);
                    
                    if($stmt_approveInfo->execute()){
                        // echo "Success";
                    }else{
                        echo "Error. Failed to Approve";
                    }

                }else{
                    echo "Error. Decline condition";
                }
            }

            if ($leaveStatus == "Pending") {
                $query_cancelLeave = "DELETE FROM tbl_leavedetails WHERE leavedetails_ID = :leavedetailsID";
                $stmt_cancelLeave = $con->prepare($query_cancelLeave);
                
                $stmt_cancelLeave->bindParam(':leavedetailsID',$leavedetailsID);
                
                if($stmt_cancelLeave->execute()){
                    $query_removeLeaveGroup = "DELETE FROM  tbl_leaveGroup WHERE leaveGroup_ID = :leaveGroupID";
                    $stmt_removeLeaveGroup = $con->prepare($query_removeLeaveGroup);
                    
                    $stmt_removeLeaveGroup->bindParam(':leaveGroupID',$leaveGroupID);
                    if($stmt_removeLeaveGroup->execute()){
                        echo "<div class='alert alert-success'>Leave request deleted.</div>";
                    }else{
                        echo "Error. Failed to execute.";
                    }
                }
            } else {
                $query_removeLeaveGroup = "Update tbl_leaveGroup set leaveGroup_status = 'Cancelled' WHERE leaveGroup_ID = :leaveGroupID";
                $stmt_removeLeaveGroup = $con->prepare($query_removeLeaveGroup);
                
                $stmt_removeLeaveGroup->bindParam(':leaveGroupID',$leaveGroupID);
                if($stmt_removeLeaveGroup->execute()){
                    echo "<div class='alert alert-success'>Leave request deleted.</div>";
                }else{
                    echo "Error. Failed to execute.";
                }
            }

            
        }
        
    }else{
        echo "Error. Failed to execute1";
    }
    
?>