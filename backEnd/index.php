<?php
error_reporting(0);
//login details
session_start();
$_SESSION['user_email']= "";
$_SESSION['user_password']="";
$_SESSION['user_logged_in']= false;
if( $_REQUEST["email"] && $_REQUEST["password"] ) {
    $_SESSION['user_email']= $_REQUEST['email'];
    $_SESSION['user_password']= $_REQUEST['password'];
}else{
    $responsObj='{"message":"Login Failed fill all fields"}';
    echo $responsObj;
    die( );
}
?>

<?php 
//server connection details && db details && Create connection
include "server_DB_setup.php" ?>

<?php 
//check server and db connection
include "check_server_DB.php" ?>


<?php
fetchUser($conn,$dbName, $_SESSION['user_email'],$_SESSION['user_password']);

function fetchUser($conn,$dbName,$email,$password){

    $sql ="SELECT email,password from users where email= ? ;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo '{"message":"failed"}';
    }else{
    mysqli_stmt_bind_param($stmt, "s",$email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result) ;
    if ($row['password']==$password){
        loggIn();
    }else{
        echo '{"message":"username or password wrong"}';
        }
    }
    mysqli_free_result($result);
    
} 

function loggIn(){
    $_SESSION['user_logged_in']= true;
    echo '{"message":"1"}';
}

mysqli_close($conn);
?>

