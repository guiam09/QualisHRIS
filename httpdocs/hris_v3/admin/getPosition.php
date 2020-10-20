<!-- <?php
include '../db/connection.php';

if (!empty($_POST['departmentID'])){

    $deptID = $_POST['departmentID'];
    $query = "SELECT * FROM tbl_position WHERE departmentID = '$deptID'";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      echo '<option value="'.$row['positionID'].'">'.$row['positionName'].'</option>';

     }

     }else{
       echo "no records found";
     }

}

 ?> -->


 <?php

 $DB = mysqli_connect("localhost", "root", null, "db_coresyshris");
 if(isset($_POST['department'])) {
   $department = mysqli_real_escape_string($DB, $_POST['department']);

   $Query = mysqli_query($DB, "
    SELECT * FROM tbl_position WHERE departmentID = '$department'
   ");
   while($Fetch = mysqli_fetch_array($Query)) {
     $id = $Fetch['positionID'];
       $name = $Fetch['positionName'];
      ?>
<option value = "<?php echo $id; ?>"><?php echo $name; ?></option>
      <?php
   }
 }

  ?>
