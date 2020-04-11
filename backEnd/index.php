<?php
error_reporting(0);
$responsObj="";
//login details
session_start();
$_SESSION['user_email']= "";
$_SESSION['user_password']="";
if( $_REQUEST["email"] && $_REQUEST["password"] ) {
    $_SESSION['user_email']= $_REQUEST['email'];
    $_SESSION['user_password']= $_REQUEST['password'];
}else{
    $responsObj='{"message":"Login Failed fill all fields"}';
    echo $responsObj;
    die( );
}


//server connection details && db details && Create connection
?>
<?php include "server_DB_setup.php" ?>
<?php


// Create connection
$conn = new mysqli($servername, $server_username, $server_password);

// Check connection
if (mysqli_connect_errno()) {
    $responsObj='{"message":"Service not available"}';
    echo $responsObj;
    die();
}


//check db exists
$sql = "select schema_name from information_schema.schemata where schema_name = '$dbName';";
$result = mysqli_query($conn,$sql);
$resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);

if(isset($resultDict[0]["schema_name"])==$dbName){
    fetchUser($conn,$dbName, $_SESSION['user_email'],$_SESSION['user_password']);
}else{
    setupDB($conn,$dbName);
}


function fetchUser($conn,$dbName,$email,$password){
    $sql = "USE $dbName";
    $result = mysqli_query($conn, $sql);
    $sql = "SELECT * from users where email='$email'";
    $result = mysqli_query($conn, $sql);
    $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);
    if (empty($resultDict)){
        $responsObj='{"message":"not registered"}';
        echo $responsObj;
    }else{
        $sql = "SELECT password from users where email='$email'";
        $result = mysqli_query($conn, $sql);
        $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);
        if ($resultDict[0]["password"] != $password){
            $responsObj='{"message":"wrong password"}';
            echo $responsObj;
        }else{
           loggIn();
        }
    }
} 

function loggIn(){
    $responsObj='{}';
    echo $responsObj;
}


//setup is only for first time

function setupDB($conn,$dbName){
    //create db
    $sql = "CREATE DATABASE  IF NOT EXISTS $dbName";
    $result = mysqli_query($conn, $sql);
    $sql = "USE $dbName";
    $result = mysqli_query($conn, $sql);
    //cerate table
    $sql = "CREATE TABLE users(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    DOB DATE NOT NULL,
    email VARCHAR(70) NOT NULL UNIQUE,
    `password` VARCHAR(70) NOT NULL ,
    details VARCHAR(150) 
    )";
    mysqli_query($conn, $sql);
}

mysqli_close($conn);
?>

