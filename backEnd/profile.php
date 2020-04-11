<?php
error_reporting(0);
$responsObj="";
session_start();
//server connection details
$servername = "localhost";
$server_username = "root";
$server_password = "admin";

//db details
$dbName="user_acc";

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
    fetchUserDetails($conn,$dbName);
}else{
    $responsObj='{"message":"Service not available"}';
    echo $responsObj;
    die();
}


function fetchUserDetails($conn,$dbName){
    if(!checkLogin($conn,$dbName)){
        $responsObj='{"message":"user not logged in"}';
        echo $responsObj;
    }
    else{
            $select_cols = 'first_name,last_name,DOB,email,details';
            $sql = "SELECT $select_cols from users where email='$_SESSION[user_email]'";
            $result = mysqli_query($conn, $sql);
            $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);
            $responsObj = json_encode($resultDict[0]);
            echo $responsObj;
        }
}
function checkLogin($conn,$dbName){
    $sql = "USE $dbName";
    $result = mysqli_query($conn, $sql);
    $sql = "SELECT email from users where email='$_SESSION[user_email]'";
    $result = mysqli_query($conn, $sql);
    $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);
    if (empty($resultDict)){
        return false;
    }
    $sql = "SELECT password from users where email='$_SESSION[user_email]'";
    $result = mysqli_query($conn, $sql);
    $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);
    if ($resultDict[0]["password"] != $_SESSION['user_password']){
        return false;
    }
    return true;
}

mysqli_close($conn);
?>

