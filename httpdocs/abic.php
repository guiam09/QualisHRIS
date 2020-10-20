<?php

$servername = '107.180.58.58';
$server_username = 'codeXL';
$password = 'Commlinked2019';
$dbname = 'pangalan_db';
#end of section

/*
// Create connection
$con = new mysqli($servername, $server_username, $password, $dbname);

//check users if they exist
$stmt = $con->prepare('SELECT tbh.branch, tbh.branch_code, tbh.branch_manager, tbh.email FROM temp_vismin_bh tbh WHERE tbh.branch_manager NOT IN (SELECT u.first_name FROM user u)');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($branch, $code, $manager, $email);
if($stmt->num_rows > 0){ //not existing
    while($stmt->fetch()){
        
        //get branch id using branch code
        $con1 = new mysqli($servername, $server_username, $password, $dbname);
        
        $stmt1 = $con1->prepare('SELECT `branch_id` FROM `branch` WHERE `branch_code`=?');
        $stmt1->bind_param('s', $code);
        $stmt1->execute();
        $stmt1->store_result();
        $stmt1->bind_result($branchID);
        $stmt1->fetch();
        $stmt1->close();
        $con1->close();
        
        //insert users
        $con1 =new mysqli($servername, $server_username, $password, $dbname);
        
        $stmt1 = $con1->prepare('INSERT INTO `user`(`username`, `first_name`, `pass`, `email`, `role_id`, `branch_id`, `area_id`, `region_id`, `bbg_id`, `status`, `date_added`, `active`, `profile_pic`) VALUES (?, ?, "$2a$10$mbRxl042qGxTwTdBB3JxNeKeWQEwuyPDcvapMv.No6VnQ1IFq52yy", ?, 6, ?, 1, 1, 1, "ACTIVE", "2019-05-03 00:00:00", 1, "/user_pic/default.jpg")');
        
        $username = strtolower(str_replace(" ", "", $manager));
        
        $stmt1->bind_param('sssi', $username, $manager, $email, $branchID);
        $stmt1->execute();
        $stmt1->close();
        $con1->close();
        
        //get user id
        $con1 = new mysqli($servername, $server_username, $password, $dbname);
        
        $stmt1 = $con1->prepare('SELECT id FROM `user` ORDER BY id DESC LIMIT 1');
        $stmt1->execute();
        $stmt1->store_result();
        $stmt1->bind_result($userID);
        $stmt1->fetch();
        $stmt1->close();
        $con1->close();
        
        //insert to user role (6)
        $con1 = new mysqli($servername, $server_username, $password, $dbname);
        
        $stmt1 = $con1->prepare('INSERT INTO `user_role`(`user_id`, `role_id`) VALUES (?, 6)');
        $stmt1->bind_param('i', $userID);
        $stmt1->execute();
        $stmt1->close();
        $con1->close();
    }
}
$stmt->close();
$con->close();
*/

$con = new mysqli($servername, $server_username, $password, $dbname);

$stmt = $con->prepare('SELECT tbh.`id`, tbh.`branch`, tbh.`branch_code`, tbh.`branch_manager`, tbh.`email` FROM temp_vismin_bh tbh JOIN branch b ON b.branch_code=tbh.branch_code WHERE b.branch_manager=1');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($idx, $branchx, $codex, $managerx, $emailx);
if($stmt->num_rows > 0){
    
    $i = 0;
    
    while($stmt->fetch()){
        
        //get ID of manager using name
        $con1 =new mysqli($servername, $server_username, $password, $dbname);
        
        $usernamex = strtolower(str_replace(" ", "", $managerx));
        
        $stmt1 = $con1->prepare('SELECT id FROM `user` WHERE username=?');
        $stmt1->bind_param('s', $usernamex);
        $stmt1->execute();
        $stmt1->store_result();
        $stmt1->bind_result($userIDx);
        $stmt1->fetch();
        $stmt1->close();
        $con1->close();
        
        //update manager (branch) using branch code
        $con1 = new mysqli($servername, $server_username, $password, $dbname);
        $stmt1 = $con1->prepare('UPDATE `branch` SET `branch_manager`=? WHERE `branch_code`=?');
        $stmt1->bind_param('is', $userIDx, $codex);
        $stmt1->execute();
        $stmt1->close();
        $con1->close();
        
        //update branch_id (user) using user_id and branch_code
        $con1 = new mysqli($servername, $server_username, $password, $dbname);
        $stmt1 = $con1->prepare('UPDATE `user` SET `branch_id`=(SELECT `branch_id` FROM `branch` WHERE `branch_code`=?) WHERE `id`=?');
        $stmt1->bind_param('si', $codex, $userIDx);
        $stmt1->execute();
        $stmt1->close();
        $con1->close();
        
        $i++;
        
    }   
}
$stmt->close();
$con->close();

echo $i;
?>