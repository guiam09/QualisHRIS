<?php
// used to connect to the database

// $host = "182.50.133.90:3306"; // production database
// $db_name = "db_coresyshris"; // production database
// $username = "qualis"; // production username
// $password = "9Sq3xb0^"; // production password


$host = "localhost"; // development database
$db_name = "db_coresyshris"; // development database
$username = "root"; // development database
$password = ""; // development database

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}

// show error
catch(PDOException $exception){
    echo "Connection error: " . $exception->getMessage();
}
?>
