 <?php
include '../db/connection.php';

    $id = $_POST['leaveID'];
    $query = "SELECT * FROM tbl_leave WHERE leaveID = '$id'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      echo '  <input type="number" class="form-control"  maxlength="50" name="leaveCredits" id="leaveCredits"
                    value='.$row['leaveCount'].' ?>';

     }

     }else{
       echo "no records found";
     }



 ?> 