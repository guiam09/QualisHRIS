<?php


$servername = '107.180.58.58';
$server_username = 'codeXL';
$password = 'Commlinked2019';
$dbname = 'pangalan_db';
#end of section

// Create connection
$con = new mysqli($servername, $server_username, $password, $dbname);

$stmt = $con->prepare('SELECT branch, branch_code FROM `temp_vismin_bh` WHERE branch_code NOT IN (select branch_code FROM branch) ORDER BY `temp_vismin_bh`.`branch`  ASC');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($branch, $code);
if($stmt->num_rows > 0){
    while($stmt->fetch()){
        
        $con1 = new mysqli($servername, $server_username, $password, $dbname);
        
        $stmt1 = $con1->prepare('UPDATE `branch` SET `branch_code`=? WHERE `branch_name`=?');
        $stmt1->bind_param('ss', $code, $branch);
        $stmt1->execute();
        $stmt1->close();
        $con1->close();
    }
}
?>