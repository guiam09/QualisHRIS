<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(isset($_POST['addLeave'])){


  echo $_POST['leaveName'];
}else {
  echo "fuck";
}
 ?>
