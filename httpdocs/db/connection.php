<?php
// used to connect to the database
$host = "182.50.133.90:3306";
$db_name = "db_coresyshris";
$username = "qualis";
$password = "9Sq3xb0^";

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}

// show error
catch(PDOException $exception){
    echo "Connection error: " . $exception->getMessage();
}
?>
