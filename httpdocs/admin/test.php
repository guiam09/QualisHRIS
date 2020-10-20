<?php
include ('../db/connection.php');
include_once ('../includes/configuration.php');


echo $birthDate = $_POST['birthDate'];
$dateHired = $_POST['dateHired'];

echo "<br>";
echo "<br>";
 $birthDate = DateTime::createFromFormat('m/d/Y', $birthDate);
$birthDate = $birthDate->format('Y-m-d');
  
   $dateHired = DateTime::createFromFormat('m/d/Y', $dateHired);
 $dateHired = $dateHired->format('Y-m-d');
   
   echo $birthDate;
   echo $dateHired;

?>