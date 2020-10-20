<?php
include '../db/connection.php';

if (!empty($_POST['employeeCode'])){

    $empID = $_POST['employeeCode'];
    // select all data
    $query = "SELECT * FROM tbl_leaveinfo INNER JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID  WHERE employeeCode = '$empID' ";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo "  <thead>
          <tr>
              <th>Leave Type</th>
              <th>Allowed</th>
              <th>Used</th>
              <th>Left</th>
          </tr>
      </thead>";
    if($num>0){

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "        <tr>
                        <td>" . $row['leaveName'] . "</td>
                        <td>" . $row["leaveCount"] .  "</td>
                        <td>" . $row["leaveUsed"] . "</td>
                          <td>" . $row["leaveRemaining"] . "</td>
                      </tr>
                      <input type='hidden' name='empID' value='$empID'></input>
                      ";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
       // echo "no records found";
     }

}




 ?>
