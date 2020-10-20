<?php
include '../db/connection.php';

if (!empty($_POST['employeeID'])){

    $empID = $_POST['employeeID'];

    $leaveID = '';
    if (!empty($_POST['leaveID'])) {
      $leaveID = ' AND tbl_leave.leaveID = "'.$_POST['leaveID'].'"';
    }

    $query = "SELECT * FROM tbl_leaveinfo JOIN tbl_employees ON tbl_leaveinfo.employeeID = tbl_employees.employeeID 
                                     JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID
                                     JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID
                                      WHERE tbl_leaveinfo.employeeID = '$empID' ".$leaveID." ORDER BY firstName ASC";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          echo "        <tr>

                                                        <td>" . $row["employeeCode"] . "</td>
                                                        <td>" . $row["firstName"] . ' ' . $row['lastName'] . "</td>
                                                        <td>" . $row['positionName']. "</td>
                                                        <td>" . $row['leaveName']. "</td>
                                                        <td>" . $row['allowedLeave']. "</td>
                                                        <td>" . $row['leaveUsed']. "</td>
                                                        <td>" . $row['leaveRemaining']. "</td>
                                                        <td class='actions'>   <a href='#' class='btn btn-default'
                                             data-original-title='Edit' data-toggle='modal' data-target='#editButton".$row['leaveInfoID']."' data-id=" .  $row['leaveInfoID'] . " ><i class='icon fa-edit' aria-hidden='true'></i></a>
                                             <a href='#' class='btn btn-default'
                                             data-original-title='Edit' data-toggle='modal' data-target='#deleteButton".$row['leaveInfoID']."' data-id=" .  $row['leaveInfoID'] . " ><i class='icon fa fa-close' aria-hidden='true'></i></a></td>
                                                    
                                                      </tr>";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
       // echo "no records found";
     }

}




 ?>
