 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
 <!--<script src="bootstrap-4.0.0/dist/js/bootstrap.js"></script>-->
 <style>
 .swal2-container {
  z-index: 2000;
}

.modal {
    z-index:1950;
}
 </style>
<?php
    include '../db/connection.php';
    session_start();

    

    $userID = $_SESSION['user_id'];
    //get employee name
    $query_getName = "SELECT * FROM tbl_employees WHERE employeeCode='$userID'";
    $stmt_getName = $con->prepare($query_getName);
    $stmt_getName->execute();
    while($row_getName = $stmt_getName->fetch(PDO::FETCH_ASSOC)){
        $employeeName = $row_getName['firstName'] . " " . $row_getName['lastName'];
    }
    
    // show ajax table
    if (isset($_POST['employeeID'])){
        $employeeID = $_POST['employeeID'];
        $range = $_POST['range'];
        $from_date = date_create($_POST['from_date']);
        $from_date = date_format($from_date,'Y/m/d');
        $to_date = date_create($_POST['to_date']);
        $to_date = date_format($to_date,'Y/m/d');
        ?>
        <div class='row' id="result">
            <div class='col-md-9' id='displayResult'></div>
            <div class='col-md-3'>
                <form method="POST" action="exportAttendanceCSV.php">
                    <input type='text' hidden name='employeeID' value='<?php echo $employeeID ;?>'>
                    <input type='text' hidden name='range' value='<?php echo $range ;?>'>
                    <input type='text' hidden name='from_date' value='<?php echo $from_date ;?>'>
                    <input type='text' hidden name='to_date' value='<?php echo $to_date ;?>'>
                    <button type="submit" name="exportAttendanceRecords" value="printCsv" title='Download CSV file' class="btn btn-block btn-default"><i class="fas fa-download"></i></button>
                </form>
            </div>
        </div>
        <br/>
        <table>
        <thead>
                                                <tr>
                                                    <th width='15%'>Date</th>
                                                    <th width='10%'></th>
                                                    <th width='20%'>Time-In</th>
                                                    <th width='20%'>Time-Out</th>
                                                    <th width='10%'>Location</th>
                                                    <th width='15%'>Hours Rendered</th>
                                                </tr>
                                            </thead>
        
        <?php 
        
        
        
        
        // //select attendance records

        $query_attendanceRecords="SELECT * FROM tbl_attendance WHERE employeeID = '$employeeID' ORDER BY attendanceDate DESC, attendance_timeIn DESC ";
        $stmt_attendanceRecords = $con->prepare($query_attendanceRecords);
        $stmt_attendanceRecords->execute();
        $num_attendanceRecords = $stmt_attendanceRecords->rowCount();
        
        if($num_attendanceRecords == 0){
            echo "  <tr><td align='center' colspan='6'> No Records Found </td></tr>";
        }else{
            if($range == "currentWeek"){
                $query_range="SELECT * FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) AND WEEK(CURDATE()) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "lastWeek"){
                $query_range="SELECT * FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) - 1 AND WEEK(CURDATE()) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "lastTwoWeeks"){
                $query_range="SELECT * FROM tbl_attendance WHERE employeeID='$employeeID' AND WEEK(attendanceDate) BETWEEN WEEK( CURDATE() ) - 2 AND WEEK(CURDATE()) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "lastMonth"){
                $query_range="SELECT * FROM tbl_attendance WHERE employeeID='$employeeID' AND MONTH (attendanceDate) BETWEEN MONTH( CURDATE() ) - 1 AND MONTH( CURDATE() ) ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }elseif($range == "custom"){
                $query_range="SELECT * FROM tbl_attendance WHERE employeeID='$employeeID' AND attendanceDate BETWEEN '$from_date' AND '$to_date' ORDER BY attendanceDate DESC, attendance_timeIn DESC";
            }else{
                $query_range=$query_attendanceRecords;
            }
              
                $stmt_range = $con->prepare($query_range);
                $stmt_range->execute();
                
                //hold query to be exported 
                ?>
                <!--<input type='text'  value='<?php echo $query_range; ?>'>-->
                <?php
                // end hold query
                
                $num_range = $stmt_range->rowCount();
                if($num_range == 0){
                    echo "  <tr><td align='center' colspan='6'> No Records Found </td></tr>";
                }else{
                    $holdDate="";
                    $rowCounter= 1;
                    $totalRenderedHours=0;
                    $overAllTotalHours=0;
                    
                    while($row_range = $stmt_range->fetch(PDO::FETCH_ASSOC)){
                        $attendanceID = $row_range["attendanceID"];
                        $currentDate = $row_range["attendanceDate"];
                        $timeInTime=strtotime($row_range["attendance_timeIn"]);
                        $formattedTimeIn=date('h:i:s A', $timeInTime);
                        
                        if(isset($row_range["attendance_timeOut"])){
                            $timeOutTime=strtotime($row_range["attendance_timeOut"]);
                            $formattedTimeOut=date('h:i:s A', $timeOutTime);
                                            
                        } else {
                            $formattedTimeOut = " - - - - - - - - - - ";
                        }
                        
                        if($holdDate!=$currentDate && $rowCounter!=1){
                                            echo "
                                                <tr>
                                                    <td width='15%'></td>
                                                    <td width='5%'></td>
                                                    <td width='20%'></td>
                                                    <td width='20%'></td>
                                                    <strong><th width='15%'>TOTAL</th></strong>
                                                    <strong><th width='15%'>" . $totalRenderedHours . "</th></strong>
                                                </tr>
                                            ";
                                            $overAllTotalHours = $overAllTotalHours + $totalRenderedHours;
                                            $totalRenderedHours = 0;
                                        }
                        
                        
                    echo 
                        "<tr ";
                        
                        if ($row_range['attendance_voided'] == 'VOID')    {
                            
                            echo ' style = \'color:red\'';
                            }
                            echo ">"; 
                                                if($holdDate!=$currentDate){
                                                    // $overAllTotalHours = $overAllTotalHours + $totalRenderedHours;
                                                    // $totalRenderedHours = 0;
                                                    echo "<td width='15%'>" . $row_range["attendanceDate"] . "</td>";
                                                }else{
                                                    echo "<td width='15%'>                 </td>";
                                                }
                                                echo "
                                                 
                                                <td width='10%'><a href='#editAttendanceModal". $attendanceID ."' data-toggle='modal' ";
                                                // hiding edit bytton for voided record
                                                if ($row_range['attendance_voided'] == 'VOID')    {
                                                    echo ' hidden';
                                                }
                                                
                                                
                                                echo "><i class='far fa-edit' ></i></a> <a ";
                                                if($row_range['attendance_timesModified'] == 0){
                                                    echo "hidden";
                                                    
                                                }
                                                
                                                echo " href='#showAttendanceUpdatesModal". $attendanceID ."' data-toggle='modal'><i class='fas fa-book' ></i></a></td>
                                                <td  width='20%'>" . $formattedTimeIn . "</td>
                                                <td  width='20%'>" . $formattedTimeOut . "</td>
                                                <td  width='10%'>" . $row_range["attendance_location"] . "</td>
                                                <td width='15%'>" . $row_range["hourWorked"] . "</td>";    
            
                                            echo "</tr>";
                                        
                                        if ($row_range['attendance_voided'] != 'VOID')    {
                                            $totalRenderedHours = $totalRenderedHours + $row_range["hourWorked"];
                                        }
                                        
                                        if($rowCounter==$num_range){
                                            $overAllTotalHours = $overAllTotalHours + $totalRenderedHours;
                                            echo "
                                                <tr>
                                                    <td width='15%'></td>
                                                    <td width='10%'></td>
                                                    <td width='20%'></td>
                                                    <td width='20%'></td>
                                                    <strong><th width='10%'>TOTAL</th></strong>
                                                    <strong><th width='15%'>" . $totalRenderedHours . "</th></strong>
                                                </tr>
                                            ";
                                        }
                                        
                                        $holdDate=$currentDate;
                                        $rowCounter+=1;
                                        
                                        
                                        ?>
                                        
                                        <!--Modals-->
                                        <div class="modal fade" id="editAttendanceModal<?php echo $attendanceID ?>" tabindex="-1" role="dialog" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"> Update Time Entry</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <form class="form-horizontal" method="POST" id="modifyAttendance" name="modifyAttendance" action="attendance.php">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="showAttendanceDate" class="col-sm-12 control-label"><h6>Date:</h6></label>
                                                                        <div class="form-control" readonly name='timeEntryDate'><?php echo $row_range['attendanceDate']; ?></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="modifyTimeInField" class="col-sm-12 control-label"><h6>Time-In</h6></label>
                                                                        <input type="time" class="form-control" name='modifyTimeInField' id='modifyTimeIn<?php echo $row_range['attendanceID']; ?>' value='<?php echo $row_range['attendance_timeIn'];?>' />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="modifyTimeOutField" class="col-sm-12 control-label"><h6>Time-Out</h6></label>
                                                                        <input type="time" class="form-control" name='modifyTimeOutField' id='modifyTimeOut<?php echo $row_range['attendanceID']; ?>' value='<?php echo $row_range['attendance_timeOut'];?>' />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <input type='text' name="attendanceEditID" value="<?php echo $row_range['attendanceID']?>" hidden />
                                                                        <label for="modifyLocationField" class="col-sm-12 control-label"><h6>Location</h6></label>
                                                                        <select class="form-control col-md-12" data-plugin="selectpicker"  id="modifyLocation<?php echo $row_range['attendanceID']; ?>" name="modifyLocationField" data-placeholder="" width="auto">
                                                                            <option value="Office" <?php if($row_range['attendance_location'] == "Office"){ echo "selected"; } ?>>Office</option>
                                                                            <option value="WR" <?php if($row_range['attendance_location'] == "WR"){ echo "selected"; } ?>>WR</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="modifyLocationField" class="col-sm-12 control-label"><h6>Reason</h6></label>
                                                                        <input type='text' name="attendanceEditReason" id="attendanceEditReason<?php echo $row_range['attendanceID']; ?>" class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type='hidden' name='hiddenAttendanceIdValue' id='hiddenAttendanceId<?php echo $row_range['attendanceID']; ?>' value='<?php echo $row_range['attendanceID']; ?>'>
                                                            <button type="button" class="btn btn-secondary" id="closeButton<?php echo $row_range['attendanceID']; ?>" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger" name="void" value="void" id="voidButton<?php echo $row_range['attendanceID']; ?>">Void</button>
                                                            <button type="submit" class="btn btn-primary" name="save" value="save" id="saveButton" onclick="updateData<?php echo $row_range['attendanceID']; ?>()">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                 
                                        <div class="modal fade" id="showAttendanceUpdatesModal<?php echo $attendanceID ?>" tabindex="-1" role="dialog" aria-labelledby="showAttendanceUpdatesModalLabel" aria-hidden="true" >
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" id="exampleModalLabel">Time Entry Journal</h2>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <h5>Employee Name</h5>
                                                                    <h4 name=employeeName><?php echo $employeeName; ?></h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <h5>Attendance Entry</h5>
                                                                    <h4 name=date><?php echo $row_range['attendanceDate'] ?></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                                        <h5 class="col-md-2">Activity</h5>
                                                                        <h5 class="col-md-2">From</h5>
                                                                        <h5 class="col-md-2">To</h5>
                                                                        <h5 class="col-md-2">Date</h5>
                                                                        <h5 class="col-md-2">Time</h5>
                                                                        <h5 class="col-md-2">Updated By</h5>
                                                            <?php
                                                                $attendanceID = $row_range['attendanceID'];
                                                                $query_displayUpdatesTable = "SELECT * FROM tbl_attendanceModification WHERE attendanceID='$attendanceID' ORDER BY attendanceModification_modificationDate DESC, attendanceModification_modificationTime DESC";
                                                                $stmt_displayUpdatesTable = $con->prepare($query_displayUpdatesTable);
                                                                $stmt_displayUpdatesTable->execute();
                                                                // $num = $stmt_displayUpdatesTable->rowCount();
                                                                while($row_displayUpdatesTable = $stmt_displayUpdatesTable->fetch(PDO::FETCH_ASSOC)){
                                                          
                                                                    // $originalTime = $row_displayUpdatesTable['attendanceModification_modificationTime'];
                                                                    // $formattedTime = date('h:i:s A', strtotime($originalTime));
                                                                    echo "
                                                                            <div class='col-md-2'>". $row_displayUpdatesTable['attendanceModification_activity'] ." <i class='fas fa-sticky-note' title='Reason: ". $row_displayUpdatesTable['attendanceModification_reason'] ."'></i></div>
                                                                            <div class='col-md-2'>". $row_displayUpdatesTable['attendanceModification_fromValue'] ."</div>
                                                                            <div class='col-md-2'>". $row_displayUpdatesTable['attendanceModification_toValue'] ."</div>
                                                                            <div class='col-md-2'>". $row_displayUpdatesTable['attendanceModification_modificationDate'] ."</div>
                                                                            <div class='col-md-2'>". $row_displayUpdatesTable['attendanceModification_modificationTime'] ."</div>
                                                                            <div class='col-md-2'>". $row_displayUpdatesTable['attendanceModification_updatedBy'] ."</div>
                                                                    ";
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modals -->
                                        
                                        <?php 
                                        // script for updating and void attendance records
                                        
                                        // updating attendance records 
                                        echo "<script type='text/javascript'>
                                            
                                            function updateData" . $row_range['attendanceID'] . "(){
                                                    var modifyTimeInValue = $('#modifyTimeIn" . $row_range['attendanceID'] . "').val();
                                                    var modifyTimeOutValue = $('#modifyTimeOut" . $row_range['attendanceID'] . "').val();
                                                    var modifyLocationValue = $('#modifyLocation" . $row_range['attendanceID'] . "').val();
                                                    var attendanceID = $('#hiddenAttendanceId" . $row_range['attendanceID'] . "').val();
                                                    var reason = $('#attendanceEditReason". $row_range['attendanceID'] ."').val();
                                                    $.ajax({
                                                        method: 'POST',
                                                        url: 'attendance_modificationProcess.php',
                                                        data: {modifyTimeInValue:modifyTimeInValue, modifyTimeOutValue:modifyTimeOutValue, modifyLocationValue:modifyLocationValue, attendanceID:attendanceID, reason:reason},
                                                        dataType:'text',
                                                        success: function(response) {
                                                            $('#displayResult').html(response);
                                                            document.getElementById('closeButton" . $row_range['attendanceID'] . "').click();
                                                            document.getElementById('search').click();
                                                            swal('Done!','You have succesfully updated attendance journal!','success');
                                                            
                                                            // $('#showAttendanceUpdatesModal". $attendanceID ."').modal('hide');
                                                        //   document.getElementById('modifyAttendance').submit();
                                                        }
                                                })   
                                            }                                    
                                        
                                            $('#voidButton" . $row_range['attendanceID'] . "').on('click', function(){
                                                Swal.fire({
                                                    title:'This action will delete the time entry selected.',
                                                    text:'Do you wish to continue?',
                                                    type:'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Yes',
                                                    cancelButtonText: 'No'
                                                }).then((result) => {
                                                    if(result.value) { 
                                                        var attendanceID = $('#hiddenAttendanceId" . $row_range['attendanceID'] . "').val();
                                                        var reason = $('#attendanceEditReason". $row_range['attendanceID'] ."').val();
                                                        $.ajax({
                                                            method: 'POST',
                                                            url: 'attendance_voidProcess.php',
                                                            data: {attendanceID:attendanceID, reason:reason},
                                                            dataType:'text',
                                                            success: function(response) {
                                                                $('#displayResult').html(response);
                                                                document.getElementById('closeButton" . $row_range['attendanceID'] . "').click();
                                                                document.getElementById('search').click();
                                                                swal('Done!','You have succesfully voided the entry!','success');
                                                                // document.getElementById('modifyAttendance').submit();
                                                            }
                                                        })
                                                    }else{
                                                        
                                                    }
                                                })
                                            });   
                                        </script>
                                        ";
                                        //end script for updating and void attendance records
                                    }
                                    echo "
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                                <tr>
                                                <td width='15%'></td>
                                                <td width='5%'></td>
                                                <td width='15%'></td>
                                                <td width='15%'></td>
                                                <h2><th width='20%'>OVERALL TOTAL</th></h2>
                                                <h1><th width='15%'>". $overAllTotalHours ."</th></h1>
                                            </tr>
                                            </tfoot>
                                        
                                        ";
                                    echo "</table>";
                    }
                }
                
            }
?>