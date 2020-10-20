<?php
// used to connect to the database
$host = "localhost";
$db_name = "db_coresyshris";
$username = "coresys";
$password = "adminpassword123";

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}

// show error
catch(PDOException $exception){
    echo "Connection error: " . $exception->getMessage();
}
?>
