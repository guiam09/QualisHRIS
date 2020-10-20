<?php
// login checker for 'customer' access level

// if access level was not 'Admin', redirect him to login page

if(isset($_SESSION['access_level']) && $_SESSION['access_level']=="1"){
    header("Location: {$home_url}admin/index.php?action=logged_in_as_admin");
}

// if $require_login was set and value is 'true'
else if(isset($require_login) && $require_login==true){
    // if user not yet logged in, redirect to login page
    if(!isset($_SESSION['access_level'])){
        header("Location: {$home_url}index.php?action=please_login");
    }
}


// if it was the 'login' or 'register' or 'sign up' page but the customer was already logged in
else if(isset($page_title1) && ($page_title1 != "Employee")){
    // if user not yet logged in, redirect to login page
    if(isset($_SESSION['access_level']) && $_SESSION['access_level']=="2"){
        header("Location: {$home_url}employee/index.php?action=already_logged_in");
    }
}

else{
    // no problem, stay on current page
}
?>
