<?php


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


// $stmt2 = $con->prepare("SELECT username FROM user");
// $stmt2 = $con->prepare("SELECT a.first_name, a.last_name, a.username, a.email, t.password FROM user a join temp_account_tbl t on a.username = t.username inner join branch n on n.branch_id = a.branch_id inner join area e on e.area_id = n.branch_area_id inner join region r on r.region_id = e.region_id inner JOIN bbg b on b.bbg_id = r.bbg_id where a.role_id = 6 and b.bbg_id = 2");
// $stmt2 = $con->prepare("SELECT user.username, temp_account_tbl.password,user.email FROM user inner join temp_account_tbl on user.username = temp_account_tbl.username inner join bbg on user.bbg_id = bbg.bbg_id where user.role_id = 8 and bbg.bbg_id = 3 
// UNION ALL 
// SELECT user.username, temp_account_tbl.password,user.email FROM user inner join temp_account_tbl on user.username = temp_account_tbl.username inner join region on user.region_id = region.region_id inner join bbg on bbg.bbg_id = region.bbg_id where user.role_id = 4 and bbg.bbg_id = 3 UNION ALL 
// SELECT user.username, temp_account_tbl.password,user.email FROM user inner join temp_account_tbl on user.username = temp_account_tbl.username inner join bbg on user.bbg_id = bbg.bbg_id where user.role_id = 8 and bbg.bbg_id = 3 
// UNION ALL 
// SELECT user.username, temp_account_tbl.password,user.email FROM user inner join temp_account_tbl on user.username = temp_account_tbl.username inner join area on user.area_id = area.area_id inner join region on area.region_id = region.region_id inner join bbg on bbg.bbg_id = region.bbg_id where user.role_id = 5 and bbg.bbg_id = 3");
// $stmt2 = $con->prepare("SELECT user.username, temp_account_tbl.password,user.email FROM user inner join temp_account_tbl on user.username = temp_account_tbl.username inner join branch on user.branch_id = branch.branch_id inner join area on branch.branch_area_id = area.area_id WHERE area.area_id = 74");
$stmt2 = $con->prepare("SELECT u.username,u.first_name,c.password,u.email FROM `user` u join temp_account_tbl c on u.username = c.username JOIN bbg b on u.bbg_id = b.bbg_id where b.bbg_id = 4
UNION ALL
SELECT u.username,u.first_name,c.password,u.email FROM `user` u join temp_account_tbl c on u.username = c.username JOIN region r on u.region_id = r.region_id JOIN bbg b on r.bbg_id = b.bbg_id where b.bbg_id = 4
UNION ALL
SELECT u.username,u.first_name,c.password,u.email FROM `user` u join temp_account_tbl c on u.username = c.username JOIN area a on u.area_id = a.area_id JOIN region r on a.region_id = r.region_id JOIN bbg b on r.bbg_id = b.bbg_id where b.bbg_id = 4
UNION ALL
SELECT u.username,u.first_name,c.password,u.email FROM `user` u join temp_account_tbl c on u.username = c.username JOIN branch n on u.branch_id = n.branch_id JOIN area a on n.branch_area_id = a.area_id JOIN region r on a.region_id = r.region_id JOIN bbg b on r.bbg_id = b.bbg_id where b.bbg_id = 4");
 
/* $stmt2 = $con->prepare("SELECT u.username,s.first_name, u.password, s.email FROM temp_account_tbl u join user s on u.username = s.username  where u.username = 'peters.garcia' or u.username = 'elenithd.valdez' or u.username = 'libertyc.medes' or u.username = 'michellem.talosig' or u.username = 'rodelf.chavez' or u.username = 'jerryc.rosete' or u.username = 'paulinef.manes' or u.username = 'beatrizm.mindoro' or u.username = 'arnoldj.baltazar' or u.username = 'amorlitar.luya' or u.username = 'edoviniab.espino' or u.username = 'marisola.alon' or u.username = 'majoras.caisido' or u.username = 'dariusjosef.fano' or u.username = 'jemelynmarieh.afable' or u.username = 'crispinm.buenaventura' or u.username = 'roschelleu.iman' or u.username = 'edgardop.esguerra' or u.username = 'senenp.forbes' or u.username = 'amorp.enriquez' or u.username = 'mariarogelettep.ricohermoso' or u.username = 'ninarachelp.saguin' or u.username = 'francesmariec.peralta' or u.username = 'fannyr.halili' or u.username = 'corazonr.rempillo' or u.username = 'joaos.molit' or u.username = 'emilyf.geronimo' or u.username = 'ma.belindac.buenafe' or u.username = 'randym.marbella' or u.username = 'nancyl.valdez' or u.username = 'rositad.ubana' or u.username = 'magnot.salvadora'");*/
// $stmt2 = $con->prepare("SELECT user.username, user.first_name, password, email FROM `user` JOIN temp_account_tbl ON user.username = temp_account_tbl.username JOIN branch ON branch.branch_id = user.branch_id JOIN area ON area.area_id = branch.branch_area_id WHERE area.area_id = 49 AND role_id = 6");

$stmt2->execute();
$stmt2->store_result();
$stmt2->bind_result($username, $firstName, $password, $email);
if($stmt2->num_rows > 0){
    while($stmt2->fetch()){
        
        
    $headers = 
	'From: admin@codexl.ph' . "\r\n" .
    'Reply-To: ' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	// send email
    	
    require_once "phpmailer/PHPMailerAutoload.php";
    
    //PHPMailer Object
    $mail = new PHPMailer;
    
    //From email address and name
    $mail->From = "admin@codexl.ph";
    $mail->FromName = "CodeXL";
    
    $mail->addAddress($email); //Recipient name is optional
    
    //Send HTML or Plain Text email
    $mail->isHTML(true);
    
    $mail->Subject = "ABIC Portal Account Information";
    $message = "<h2>Good Day!</h2> <br>
    Hello <b>$firstName</b>! Your username is <b>$username</b> and your account password is <b>$password</b>. Kindly change your password in your account profile. <br>
    <h3>To access the portal, please refer to the guides below:</h3>
    1. If you are using a <b>desktop/PC/laptop</b>, you can visit the portal by going to https://www.abicportal.com/ <br>
    2. If you are using an <b>Android phone</b>, you can download the portal from play store. Just search for <em>ABIC Portal</em>. <br>
    3. If you are using an <b>iOS device(iphone, ipad)</b>, please use your browser(<em>safari, chrome </em>) and go to https://www.abicportal.com/ <br>
    <h3>For the tutorials on how to use the portal, please refer to the video links below.</h3>
    For <b>Purchasing</b>: https://youtu.be/lvl3fH21FcI <br>
    For <b>Generating Reports</b>: https://youtu.be/RGP6qVPBQq4 <br>
    <b>ABIC Portal link</b>: https://www.abicportal.com/ <br>
    <h3>Note:</h3>
    1. If you are a <b>BBG/Region/Area/Branch Head</b>, you dont need to register anymore. Kindly use the username and password we have given to you thru email. <br>
    2. <b>SSA/SSO/SSH and other bank employees</b> have to register here https://www.abicportal.com/Register . After their registration, Their branch manager must accept their application request inside the portal so they can access their portal. If they are not accepted, an error message 'Invalid Username or Password' will appear. <br>
    3. For the branch managers: To accept the application request of a banker, please follow the steps below: <br>
    <b>Step 1:</b> Log-in to your account <br>
    <b>Step 2:</b> At the left sidebar, click 'Banker Request' <br>
    <b>Step 3:</b> Select the user you want to accept and click the 'Approve' button<br>
    <b>Sidenote:</b> if you are using a mobile phone and you can't see an 'approve' button, please check if there is a green plus sign beside the username, click that dropdown to show the 'approve' button <br>

    
    <h3>Some Frequently Asked Questions (FAQs)</h3>
    <b>Q:</b> Invalid Username or Password <br>
    <b>A:</b> Please double check your login credentials. Passwords are case-sensitive. If you still cannot access the portal, email us so we can reset your password. <br>
    <b>Q:</b> I forgot my password <br>
    <b>A:</b> Please email us with your username and branch and we will send a newly generated password<br><br>
    
    <h3>If you have any other questions or concerns, please don't hesitate to contact us.</h3>

    
    <h3>Thank you and have a good day.</h3>
    ";
    
    $mail->Body = $message;
    // $mail->send();
    if($mail->send()) {
      echo "Message sent!";
    } else {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }

    
    
    
    
    
    
    }
}else{
    echo "s";
}
$stmt2->close();
$con->close();


// $con = new mysqli('107.180.58.58:3306', 'codeXL', 'Commlinked2019', 'pangalan_db');





?>