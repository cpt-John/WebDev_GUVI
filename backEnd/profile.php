<?php
error_reporting(0);
session_start();
?>

<?php 
//server connection details && db details && Create connection
include "server_DB_setup.php" ?>

<?php 
//check server and db connection
include "check_server_DB.php" ?>


<?php
fetchUserDetails($conn,$dbName);

function fetchUserDetails($conn,$dbName){
    if(!$_SESSION['user_logged_in']){
        echo '{"message":"user not logged in"}';
    }
    else{
        $sql ="SELECT first_name,last_name,DOB,email,details from users where email= ? ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            echo '{"message":"failed"}';
        }else{
            mysqli_stmt_bind_param($stmt,"s",$_SESSION['user_email']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result) ;
            $responsObj = json_encode($row);
            echo $responsObj;
        }
        mysqli_free_result($result);
    }

}
mysqli_close($conn);
?>

