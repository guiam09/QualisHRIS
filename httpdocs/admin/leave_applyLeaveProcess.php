<?php
    try {
        include ('../db/connection.php');
        // include ('../includes/header.php');
        session_start();
        
        $con->beginTransaction();

        $userID = $_SESSION['user_id'];
        $query_getEmployee = "SELECT employeeID FROM tbl_employees WHERE employeeCode='$userID'";
        $stmt_getEmployee = $con->prepare($query_getEmployee);
        $stmt_getEmployee->execute();
        while($row_getEmployee = $stmt_getEmployee->fetch(PDO::FETCH_ASSOC)){
            $userID=$row_getEmployee['employeeID'];
        }
        
        $leaveApprover = $_POST['adminIdApply'];
        
        $dateFiled = date('Y-m-d');
        $status = 'Pending';
        
        $query_leavegroup = "INSERT INTO tbl_leaveGroup SET employeeID = :employeeID, leaveGroup_dateFiled=:dateFiled, leaveGroup_duration = :leaveDuration, leaveGroup_status = :status, leaveGroup_approver = :leaveApprover";
        $stmt_leavegroup = $con->prepare($query_leavegroup);
        
        //compute the total leave duration
        $totalLeaveDuration = 0;

        foreach($_POST['leave_duration'] as $leaveDuration) {
            $totalLeaveDuration += $leaveDuration;
        }

        $stmt_leavegroup->bindparam(':employeeID',$userID);
        $stmt_leavegroup->bindparam(':dateFiled',$dateFiled);
        $stmt_leavegroup->bindparam(':leaveDuration', $totalLeaveDuration);
        $stmt_leavegroup->bindparam(':status',$status);
        $stmt_leavegroup->bindparam(':leaveApprover',$leaveApprover);

        if($stmt_leavegroup->execute()){

            $query_newLeaveGroupID = "SELECT leaveGroup_ID FROM tbl_leaveGroup WHERE employeeID = :employeeID AND leaveGroup_dateFiled = :dateFiled ORDER BY leaveGroup_ID ASC";
            $stmt_newLeaveGroupID = $con->prepare($query_newLeaveGroupID);
            
            // $dateOnly = date('Y-m-d');
            $stmt_newLeaveGroupID->bindparam(':employeeID',$userID);
            $stmt_newLeaveGroupID->bindparam(':dateFiled',$dateFiled);
            
            if($stmt_newLeaveGroupID->execute()){

                foreach ($_POST['leave_type'] as $key => $leaveType) {
                    $leaveDate = $_POST['leave_date'][$key];
                    $leaveDate = strtotime($leaveDate);
                    $leaveDate = date("Y-m-d",$leaveDate);

                    $leaveDuration = $_POST['leave_duration'][$key];
                    $leaveReason = $_POST['leave_reason'][$key];

                    while($row_newLeaveGroupID = $stmt_newLeaveGroupID->fetch(PDO::FETCH_ASSOC)){
                        $newLeaveGroupID=$row_newLeaveGroupID['leaveGroup_ID'];
                    }
                    
                    $query_leavedetails = "INSERT INTO tbl_leavedetails SET employeeID = :employeeID, leaveID=:leaveID, leaveGroup_ID=:leaveGroupID, leavedetails_leaveFrom=:leaveDate,leavedetails_duration =:leaveDuration, leavedetails_reason=:leaveReason, leavedetails_dateFiled=:dateFiled";
                    $stmt_leavedetails = $con->prepare($query_leavedetails);
                    
                    $stmt_leavedetails->bindparam(':employeeID',$userID);
                    $stmt_leavedetails->bindparam(':leaveID',$leaveType);
                    $stmt_leavedetails->bindparam(':leaveGroupID',$newLeaveGroupID);
                    $stmt_leavedetails->bindparam(':leaveDate',$leaveDate);
                    $stmt_leavedetails->bindparam(':leaveDuration',$leaveDuration);
                    $stmt_leavedetails->bindparam(':leaveReason',$leaveReason);
                    $stmt_leavedetails->bindparam(':dateFiled',$dateFiled);
                    
                    if($stmt_leavedetails->execute()){ 
                        $query_getLastLeaveInfo = "SELECT * FROM tbl_leaveinfo WHERE employeeID=:employeeID AND leaveID=:leaveID";
                        $stmt_getLastLeaveInfo = $con->prepare($query_getLastLeaveInfo);
                             
                        $stmt_getLastLeaveInfo->bindParam(':leaveID', $leaveType);
                        $stmt_getLastLeaveInfo->bindParam(':employeeID', $userID);
                             
                        if($stmt_getLastLeaveInfo->execute()) {
                            while($row_getLastLeaveInfo = $stmt_getLastLeaveInfo->fetch(PDO::FETCH_ASSOC)){
                                $lastPendingRequest = $row_getLastLeaveInfo['pendingLeave'];
                            }
                            $newPendingRequest = $lastPendingRequest + $leaveDuration;
                             
                            $query_updateLeaveInfo = "UPDATE tbl_leaveinfo SET pendingLeave=:pendingLeave WHERE employeeID=:employee AND leaveID=:leaveID";
                            $stmt_updateLeaveInfo = $con->prepare($query_updateLeaveInfo);
                             
                            $stmt_updateLeaveInfo->bindParam(':pendingLeave',$newPendingRequest);
                            $stmt_updateLeaveInfo->bindParam(':employee',$userID);
                            $stmt_updateLeaveInfo->bindParam(':leaveID',$leaveType);
                             
                            if($stmt_updateLeaveInfo->execute()){

                            }else{
                                throw new Exception('Failed to execute.');
                            }
                        }
                         
                    }else{
                        throw new Exception('Failed to execute.');
                    }
                }
            }else{
                throw new Exception('Failed to execute.');
            }
        }

        $con->commit();
        echo '<div class="alert alert-success">Leave Request has been sent.</div>';

    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
?>
