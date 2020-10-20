<?php
    include ('../db/connection.php');
    // include ('../includes/header.php');
    session_start();

    $userID = $_SESSION['user_id'];
    $newTimeIn = $_POST['modifyTimeInValue'];
    $newTimeOut = $_POST['modifyTimeOutValue'];
    $newLocation = $_POST['modifyLocationValue'];
    $attendanceID = $_POST['attendanceID'];
    $reason = $_POST['reason'];
    
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
        $oldTimeIn = $row_oldValues['attendance_timeIn'];
        $oldTimeOut = $row_oldValues['attendance_timeOut'];
        $oldLocation = $row_oldValues['attendance_location'];
        $timesModified = $row_oldValues['attendance_timesModified'];
    }
    if($oldTimeIn != $newTimeIn || $oldTimeOut != $newTimeOut){
        if($oldTimeIn == $newTimeIn){
            echo "No changes made.";
        }else {
            // // date_default_timezone_set("Hongkong");
            // // 
            // $newTimeIn = new DateTime($newTimeIn);
            // $oldTimeOut = new DateTime($oldTimeOut);
            // // $date_in = new DateTime($date_in);
            // // $date_out = new DateTime($date_out);
            // // $dateInterval = $date_in->diff($date_out);
            // $timeInterval = $newTimeIn->diff($oldTimeOut);
            // // $days = $dateInterval->format('%d');
            // $hrs = $timeInterval->format('%h');
            // $mins = $timeInterval->format('%i');
            // $mins = $mins/60;
            // $int = $hrs + $mins;
                
            // $decimalDigits = $int - floor($int);
            // if(($decimalDigits)<0.5){
            //     $int = floor($int);
            // } else if(($decimalDigits)>=0.5){
            //     $int = (floor($int))+0.5;
            // }
            
            
            // if($int>=0.5){
            
                $query_updateTimeIn = "INSERT INTO tbl_attendanceModification 
                    SET attendanceID=:attendanceID, attendanceModification_timesModified=:timesModified, attendanceModification_activity=:modificationActivity, attendanceModification_fromValue=:fromValue, attendanceModification_toValue=:toValue, attendanceModification_modificationDate=:modificationDate, attendanceModification_modificationTime=:modificationTime, attendanceModification_updatedBy=:updatedBy,
                        attendanceModification_reason=:reason";
                $stmt_updateTimeIn = $con->prepare($query_updateTimeIn);
                
                $timesModified = $timesModified + 1;           
                $modificationActivity = 'Time-in Update';
                $modificationDate = date('Y-m-d');
                $modificationTime = date('H:i:s');
                
                $stmt_updateTimeIn->bindParam(':attendanceID', $attendanceID);
                $stmt_updateTimeIn->bindParam(':timesModified', $timesModified);
                $stmt_updateTimeIn->bindParam(':modificationActivity', $modificationActivity);
                $stmt_updateTimeIn->bindParam(':fromValue', $oldTimeIn);
                $stmt_updateTimeIn->bindParam(':toValue', $newTimeIn);
                $stmt_updateTimeIn->bindParam(':modificationDate', $modificationDate);
                $stmt_updateTimeIn->bindParam(':modificationTime', $modificationTime);
                $stmt_updateTimeIn->bindParam(':updatedBy', $updatedBy);
                $stmt_updateTimeIn->bindParam(':reason', $reason);
                
                if($stmt_updateTimeIn->execute()){
        
                    
                    
                    $query_updateTableTimeIn="UPDATE tbl_attendance
                        SET attendance_timeIn=:newTimeIn, attendance_timesModified=:timesModified WHERE attendanceID=:attendanceID";
                    $stmt_updateTableTimeIn = $con->prepare($query_updateTableTimeIn);
                    // , hourWorked=:hourRendered
                    $stmt_updateTableTimeIn->bindParam(':attendanceID', $attendanceID);
                    $stmt_updateTableTimeIn->bindParam(':newTimeIn', $newTimeIn);
                    // $stmt_updateTableTimeIn->bindParam(':hourRendered', $int);
                    $stmt_updateTableTimeIn->bindParam(':timesModified', $timesModified);
                    
                    if($stmt_updateTableTimeIn->execute()){
                        echo "Time-in update Success.";
                    } else{
                        echo "Time-in update failed.";
                    }
                } else {
                    echo "Time-in update failed.";
                }
            // }else{
            //     echo "Unable to update time-in. Updated time-in would result in less than 30 mins of rendered hours.";
            // }
        }
        // time out update
        
        if($oldTimeOut == $newTimeOut){
            echo "No changes made.";
        }else {
            $query_updateTimeOut = "INSERT INTO tbl_attendanceModification 
                SET attendanceID=:attendanceID, attendanceModification_timesModified=:timesModified, attendanceModification_activity=:modificationActivity, attendanceModification_fromValue=:fromValue, attendanceModification_toValue=:toValue, attendanceModification_modificationDate=:modificationDate, attendanceModification_modificationTime=:modificationTime, attendanceModification_updatedBy=:updatedBy,
                    attendanceModification_reason=:reason";
                
            $stmt_updateTimeOut = $con->prepare($query_updateTimeOut);
            
            $timesModified = $timesModified + 1;           
            $modificationActivity = 'Time-Out Update';
            date_default_timezone_set("Hongkong");
            $modificationDate = date('Y-m-d');
            $modificationTime = date('H:i:s');
            
            $stmt_updateTimeOut->bindParam(':attendanceID', $attendanceID);
            $stmt_updateTimeOut->bindParam(':timesModified', $timesModified);
            $stmt_updateTimeOut->bindParam(':modificationActivity', $modificationActivity);
            $stmt_updateTimeOut->bindParam(':fromValue', $oldTimeOut);
            $stmt_updateTimeOut->bindParam(':toValue', $newTimeOut);
            $stmt_updateTimeOut->bindParam(':modificationDate', $modificationDate);
            $stmt_updateTimeOut->bindParam(':modificationTime', $modificationTime);
            $stmt_updateTimeOut->bindParam(':updatedBy', $updatedBy);
            $stmt_updateTimeOut->bindParam(':reason',$reason);
            
            if($stmt_updateTimeOut->execute()){
                $query_updateTableTimeOut="UPDATE tbl_attendance
                    SET attendance_timeout=:newTimeOut, attendance_timesModified=:timesModified WHERE attendanceID=:attendanceID";
                $stmt_updateTableTimeOut = $con->prepare($query_updateTableTimeOut);
                
                $stmt_updateTableTimeOut->bindParam(':attendanceID', $attendanceID);
                $stmt_updateTableTimeOut->bindParam(':newTimeOut', $newTimeOut);
                $stmt_updateTableTimeOut->bindParam(':timesModified', $timesModified);
                
                if($stmt_updateTableTimeOut->execute()){
                    echo "Time-Out update Success.";
                } else{
                    echo "Time-Out update failed.";
                }
            } else {
                echo "Time-Out update failed.";
            }
        }
        
        $updatedTimeIn = new DateTime($newTimeIn);
        $updatedTimeOut = new DateTime($newTimeOut);
        
        $totalTimeInterval = $updatedTimeIn->diff($updatedTimeOut);
        
        //$days = $totalTimeInterval->format('%a');
        $hrs = $totalTimeInterval->format('%h');
        $mins = $totalTimeInterval->format('%i');
        $mins = $mins/60;                     
        //$days = $days*24;
        //$hoursRendered = $days + $hrs + $mins;
        $hoursRendered = $hrs + $mins;
        
        $decimalDigits = $hoursRendered - floor($hoursRendered);
        if(($decimalDigits)<0.5){
           $hoursRendered = floor($hoursRendered);
        } else if(($decimalDigits)>=0.5){
            $hoursRendered = (floor($hoursRendered))+0.5;
        }
        
        
        
        $query_updateHourRendered = "UPDATE tbl_attendance SET hourWorked=:hoursRendered WHERE attendanceID=:attendanceID";
        $stmt_updateHourRendered = $con->prepare($query_updateHourRendered);
        
        $stmt_updateHourRendered->bindParam(':attendanceID', $attendanceID);
        $stmt_updateHourRendered->bindParam(':hoursRendered', $hoursRendered);
        
        $stmt_updateHourRendered->execute();
        
    }
    // end of time out update
    
    // location update
    
        if($oldLocation == $newLocation){
        echo "No changes made.";
    }else {
        $query_updateLocation = "INSERT INTO tbl_attendanceModification 
            SET attendanceID=:attendanceID, attendanceModification_timesModified=:timesModified, attendanceModification_activity=:modificationActivity, attendanceModification_fromValue=:fromValue, attendanceModification_toValue=:toValue, attendanceModification_modificationDate=:modificationDate, attendanceModification_modificationTime=:modificationTime, attendanceModification_updatedBy=:updatedBy,
                attendanceModification_reason=:reason";
                
        $stmt_updateLocation = $con->prepare($query_updateLocation);
        
        $timesModified = $timesModified + 1;           
        $modificationActivity = 'Location Update';
        date_default_timezone_set("Hongkong");
        $modificationDate = date('Y-m-d');
        $modificationTime = date('H:i:s');
        
        $stmt_updateLocation->bindParam(':attendanceID', $attendanceID);
        $stmt_updateLocation->bindParam(':timesModified', $timesModified);
        $stmt_updateLocation->bindParam(':modificationActivity', $modificationActivity);
        $stmt_updateLocation->bindParam(':fromValue', $oldLocation);
        $stmt_updateLocation->bindParam(':toValue', $newLocation);
        $stmt_updateLocation->bindParam(':modificationDate', $modificationDate);
        $stmt_updateLocation->bindParam(':modificationTime', $modificationTime);
        $stmt_updateLocation->bindParam(':updatedBy', $updatedBy);
        $stmt_updateLocation->bindParam(':reason', $reason);
        
        if($stmt_updateLocation->execute()){
            $query_updateTableLocation="UPDATE tbl_attendance
                SET attendance_location=:newLocation, attendance_timesModified=:timesModified WHERE attendanceID=:attendanceID";
            $stmt_updateTableLocation = $con->prepare($query_updateTableLocation);
            
            $stmt_updateTableLocation->bindParam(':attendanceID', $attendanceID);
            $stmt_updateTableLocation->bindParam(':newLocation', $newLocation);
            $stmt_updateTableLocation->bindParam(':timesModified', $timesModified);
            
            if($stmt_updateTableLocation->execute()){
                echo "Location update Success.";
            } else{
                echo "Location update failed.";
            }
        } else {
            echo "Location update failed.";
        }
    }
    
    
    
    // end of location update
?>
