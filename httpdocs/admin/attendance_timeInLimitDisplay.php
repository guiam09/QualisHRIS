<thead>
                                            <tr>
                                                <th width="90%">Max Time-in Count</th>
                                                <!--<th width="60%">Employees</th>-->
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $query_getPossibleAttendanceCount = "SELECT * FROM tbl_attendancedetails";
                                                $stmt_getPossibleAttendanceCount = $con->prepare($query_getPossibleAttendanceCount);
                                                $stmt_getPossibleAttendanceCount->execute();
                                                
                                                while($row_getPossibleAttendanceCount = $stmt_getPossibleAttendanceCount->fetch(PDO::FETCH_ASSOC)){
                                                    echo "<td width='80%'>". $row_getPossibleAttendanceCount['attendanceDetails_maxtimein'] ."</td>
                                                    <td>";
                                                    echo"</td>
                                                    <td width='20%'><button data-toggle='modal' data-target='#editTimeInLimit' type='button' class='btn btn-primary' name='edit' id='edit'><i class='fas fa-edit'></i> Edit </button></td>
                                                    ";
                                                    
                                                ?>
                                                
<!--MODAL-->
    <div class="modal fade" id="editTimeInLimit">
        <div class="modal-dialog modal-sm">
            <form method="post" action="process_timeInLimit.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="card-title">Update Time-in Limit</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
                    </div>
                    <div class="modal-body ">
                        <div class='row'>
                            <div class='col-md-12'>
                                <h5>Update Max Time-in</h5>
                                <input class='form-control' autocomplete='off' type='text' id='updatedLimit'name='updatedLimit' value='<?php echo $row_getPossibleAttendanceCount['attendanceDetails_maxtimein']; ?>' />
                            </div>
                        </div>                                                
                    </div>
                    <div class="modal-footer">
                        <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="button" class="btn btn-primary"  name="updateLimit" onclick="updateMaxTimeIn()" > <i class="fa fa-check-square-o" ></i> Update Time-in Limit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- END MODAL -->
                                                
                                            <?php
                                                }
                                            ?>
                                            </tbody>
                                            
                                            
<script>
function updateMaxTimeIn()   {
    $('#editTimeInLimit').modal('hide');
    Swal.fire({
        title:'Do you wish to continue?',
        text:'You are about to change the maximum time in count.',
        type:'warning',
        icon:'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if(result.value){
            var updatedLimit = $('#updatedLimit').val();
            $.ajax({
                method: 'POST',
                url: 'process_timeInLimit.php',
                data: {updatedLimit:updatedLimit},
                dataType:'text',
                success: function(response) {
                    $('#displayResult').html(response);
                                                                
                    // document.getElementById('modifyAttendance').submit();
                }
            })
        
        } else {
            $('#editTimeInLimit').modal('show');
        }
    });
}
</script>