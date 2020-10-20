<?php
include '../db/connection.php';

    $q = "";
    $emp = $_POST['employee'];
    $lt = $_POST['leaveType'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    
             // converting one date format into another
    if(!empty($_POST['employee'])){
        $q .= " employeeID='$emp' ";
        
        if(!empty($_POST['leaveType'])){
             $q .= " AND leaveID='$lt'";
             
             if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }else{
                 
             }
        }else{
            if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }
            
        }
    }else{
        if(!empty($_POST['leaveType'])){
             $q .= " leaveID='$lt'";
             
             if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " AND leaveFrom >= '$from' AND leaveTo <= '$to' "; 
             }
        }else{
            if(!empty($_POST['to'])){
                   $from = DateTime::createFromFormat('m/d/Y', $from);      
                   $from = $from->format('Y-m-d');
                  
                   $to = DateTime::createFromFormat('m/d/Y', $to);
                   $to = $to->format('Y-m-d');
   
                   $q .= " leaveFrom >= '$from' AND leaveTo <= '$to' "; 
            }
        }
    }


    // select all data
    $query = "SELECT * FROM tbl_leavedetails INNER JOIN tbl_leave ON tbl_leavedetails.leaveID = tbl_leave.leaveID WHERE $q ";
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






 ?>
