<?php
// core configuration
include_once "configuration.php";

// destroy session, it will remove ALL session settings
session_destroy();

//redirect to login page
//header("Location: {$home_url}index.php");
header("Location: http://qualisph.com/index.php");
exit();
?>
