<?php
include '../db/connection.php';

if (!empty($_POST['employeeCode'])){

    $empID = $_POST['employeeCode'];
    // select all data
    $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leave ON tbl_leavedetails.leaveID = tbl_leave.leaveID WHERE employeeCode = '$empID' ";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo "    <thead>
          <tr>
              <th>Date Filed</th>
              <th>Leave Type</th>

              <th>Duration</th>
              <th>From</th>
              <th>To</th>
                <th>Reason</th>
              <th>Status</th>
          </tr>
      </thead>";
    if($num>0){

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "        <tr>

                        <td>" . date('F d, Y',strtotime($row['dateFiled'])) . "</td>
                          <td>" . $row['leaveName'] . "</td>
                            <td>" . $row['duration'] . ' Days' . "</td>
                        <td>" . date('F d, Y',strtotime($row["leaveFrom"])) . "</td>
                        <td>" . date('F d, Y',strtotime($row["leaveTo"]))  . "</td>

                        <td>" . $row['reason'] . "</td>
                        <td>" . $row["leaveStatus"] . "</td>
                      </tr>
                          <input type='hidden' name='empID' value='$empID'></input>";



     }
      // echo "  <tr><td> No Records Found </td></tr>";
     }else{
       // echo "no records found";
     }

}




 ?>
