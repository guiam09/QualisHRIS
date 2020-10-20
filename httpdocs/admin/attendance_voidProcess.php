<?php
    include '../db/connection.php';
    session_start();

    if(isset($_POST['attendanceID'])){
    
        $userID = $_SESSION['user_id'];
        $attendanceID = $_POST['attendanceID'];
        $activity = "VOID";
        date_default_timezone_set("Hongkong");
        $dateModified = date('Y-m-d');
        $timeModified = date('H:i:s');
        
        //get updated by name
        $query_getName = "SELECT * FROM tbl_employees WHERE employeeCode='$userID'";
        $stmt_getName = $con->prepare($query_getName);
        $stmt_getName->execute();
        while($row_getName = $stmt_getName->fetch(PDO::FETCH_ASSOC)){
            $updatedBy = $row_getName['firstName'] . " " . $row_getName['lastName'];
        }
        
        // select old values
        $query_oldValues = "SELECT * FROM tbl_attendance WHERE attendanceID = '$attendanceID'";
        $stmt_oldValues = $con->prepare($query_oldValues);
        $stmt_oldValues->execute();
     
        while($row_oldValues = $stmt_oldValues->fetch(PDO::FETCH_ASSOC)){
            $timesModified = $row_oldValues['attendance_timesModified'];
        }
        
        
        
        $query_void = "INSERT INTO tbl_attendanceModification 
                SET attendanceID=:attendanceID, attendanceModification_timesModified=:timesModified, attendanceModification_activity=:modificationActivity, attendanceModification_modificationDate=:modificationDate, attendanceModification_modificationTime=:modificationTime, attendanceModification_updatedBy=:updatedBy";
        $stmt_void = $con->prepare($query_void);
        
        $timesModified = $timesModified+1;
        
        $stmt_void->bindParam(':attendanceID', $attendanceID);
        $stmt_void->bindParam(':timesModified', $timesModified);
        $stmt_void->bindParam(':modificationActivity', $activity);
        $stmt_void->bindParam(':modificationDate', $dateModified);
        $stmt_void->bindParam(':modificationTime', $timeModified);
        $stmt_void->bindParam(':updatedBy', $updatedBy);
        
        if($stmt_void->execute()){
            $query_removeData = "UPDATE tbl_attendance SET attendance_voided='$activity' WHERE attendanceID='$attendanceID'";
            $stmt_removeData = $con->prepare($query_removeData);
            if($stmt_removeData->execute()){
                echo "Time entry voided.";
            }
            
        }
    }
?>