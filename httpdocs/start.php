<?php

session_start();

#Change this section when uploaded
$servername = '107.180.58.58';
$server_username = 'codeXL';
$password = 'Commlinked2019';
$dbname = 'pangalan_db';
#end of section

// Create connection
$con = new mysqli($servername, $server_username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
}


// $con = new mysqli('107.180.58.58:3306', 'codeXL', 'Commlinked2019', 'pangalan_db');

$stmt = $con->prepare("SELECT username FROM user");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($username);
if($num = $stmt->num_rows > 0){
    while($stmt->fetch()){

    echo "<script>
    header('location:running.php?username='.$username)
    </script>";
    
    }
}else{
    echo "s";
}
$stmt->close();
$con->close();

?>