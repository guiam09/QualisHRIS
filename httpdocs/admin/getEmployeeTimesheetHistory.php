
<div class="panel-group" id="viewing">
    <?php
        $userID = $_SESSION['user_id'];
        $showTimesheetQuery = "
            SELECT * FROM tbl_weeklyutilization WHERE employeeCode = '$userID' GROUP BY weekly_timesheetCode ORDER BY weekly_endDate DESC
            ";
                            
        $stmtView=$con->prepare($showTimesheetQuery);
        $stmtView->execute();
        $numView=$stmtView->rowCount();
        if($numView>0) {
        while($row_viewTable=$stmtView->fetch(PDO::FETCH_ASSOC)){
                                    
        $timesheetCode = $row_viewTable['weekly_timesheetCode'];
        $status = $row_viewTable['weekly_status'];
        $approval = $row_viewTable['weekly_approval'];
        $endDate = $row_viewTable['weekly_endDate'];
                                    
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <tr>
                    <td><a href="#view<?php $timesheetCode ;?>" data-toggle="collapse" data-parent="#viewing"><?php echo $endDate; ?></a></td>
                    <td><?php echo $status; ?></td>
                    <td><?php echo $approval; ?></td>
                    <td></td>
                </tr> 
            </div>
            <div id="view<?php $timesheetCode ;?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php 
                        // $queryTimesheetDisplay = "
                        //         SELECT * FROM tbl_weeklyutilization
                        //         INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID
                        //         INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID
                        //         INNER JOIN tbl_location ON tbl_weeklyutilization.location_ID = tbl_location.location_ID
                        //         WHERE employeeCode = '$userID' AND timesheetCode= '$timesheetCode';
                        // ";    
                        
                        // $stmtDisplayTimesheet=$con->prepare($queryTimesheetDisplay);
                        // $stmtDisplayTimesheet->execute();
                    ?>
                    
                 
                </div>
            </div>
        </div>
        
<?php
        
    }
    
} else {
    echo "No Records Found.";
}
                            
?>
</div>
