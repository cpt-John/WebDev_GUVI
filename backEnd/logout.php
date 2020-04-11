<?php
error_reporting(0);
session_start();
$_SESSION['user_email']= "";
$_SESSION['user_password']="";
echo "{message:'logged out'}";
?>