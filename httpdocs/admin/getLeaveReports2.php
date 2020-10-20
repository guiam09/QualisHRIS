<?php
include '../db/connection.php';
session_start();
if (isset($_POST['employeeID'])){

    $empID = $_POST['employeeID'];
    // select all data
    $session = $_SESSION['user_id'];
                              $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leave ON tbl_leavedetails.leaveID = tbl_leave.leaveID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID = tbl_employees.employeeID 
                              WHERE approval = '$session' AND leaveStatus = 'Pending' AND tbl_leavedetails.employeeID = '$empID'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "        <tr>

                                                    <td>" .date('F d, Y',strtotime($row['dateFiled'])) . "</td>
                                                  <td>" . $row["firstName"] . ' ' . $row['lastName']  . "</td>
                                                  <td>" . $row['leaveName'] . "</td>
                                                  <td>" . $row['duration'] . '  Days' . "</td>
                                                  <td>" . date('F d, Y',strtotime($row['leaveFrom'])) . "</td>
                                                    <td>" .date('F d, Y',strtotime($row['leaveTo'])) . "</td>

                                                  <td>" . $row['leaveStatus'] . "</td>
                                                  <td>" . $row['reason'] . "</td>

                                                  <td class='actions'>
                                    <a href='#' class='btn btn-info'
                                       data-original-title='Edit' data-toggle='modal' data-target='#approveButton".$row['leaveDetailsID']."' data-id=" .  $row['leaveDetailsID'] . " >Approve</a>
                                    <a href='#' class='btn btn-danger'
                                       data-original-title='Remove' data-toggle='modal' data-target='#declineButton".$row['leaveDetailsID']."' data-id=" .  $row['leaveDetailsID'] . ">Decline</a>
                                  </td>

                                                </tr>";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
        echo "  <tr><td align='center' colspan='9'> No Records Found </td></tr>";
     }

}elseif (isset($_POST['to'])){
    
      $from = $_POST['from'];
      $to = $_POST['to'];
      
         // converting one date format into another
   $from = DateTime::createFromFormat('m/d/Y', $from);
   $from = $from->format('Y-m-d');
  
   $to = DateTime::createFromFormat('m/d/Y', $to);
   $to = $to->format('Y-m-d');
   
   
    // select all data
    $session = $_SESSION['user_id'];
                              $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leave ON tbl_leavedetails.leaveID = tbl_leave.leaveID INNER JOIN tbl_employees ON tbl_leavedetails.employeeID = tbl_employees.employeeID 
                              WHERE approval = '$session' AND leaveStatus = 'Pending' AND leaveFrom >= '$from' AND leaveTo <= '$to'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "        <tr>

                                                    <td>" .date('F d, Y',strtotime($row['dateFiled'])) . "</td>
                                                  <td>" . $row["firstName"] . ' ' . $row['lastName']  . "</td>
                                                  <td>" . $row['leaveName'] . "</td>
                                                  <td>" . $row['duration'] . '  Days' . "</td>
                                                  <td>" . date('F d, Y',strtotime($row['leaveFrom'])) . "</td>
                                                    <td>" .date('F d, Y',strtotime($row['leaveTo'])) . "</td>

                                                  <td>" . $row['leaveStatus'] . "</td>
                                                  <td>" . $row['reason'] . "</td>

                                                  <td class='actions'>
                                    <a href='#' class='btn btn-info'
                                       data-original-title='Edit' data-toggle='modal' data-target='#approveButton".$row['leaveDetailsID']."' data-id=" .  $row['leaveDetailsID'] . " >Approve</a>
                                    <a href='#' class='btn btn-danger'
                                       data-original-title='Remove' data-toggle='modal' data-target='#declineButton".$row['leaveDetailsID']."' data-id=" .  $row['leaveDetailsID'] . ">Decline</a>
                                  </td>

                                                </tr>";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
        echo "  <tr><td align='center' colspan='9'> No Records Found </td></tr>";
     }
    
}




 ?>
