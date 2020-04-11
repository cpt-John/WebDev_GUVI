<?php
error_reporting(0);
$responsObj="";
//login details
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

insertToDb($conn,$dbName,validateDetails($_REQUEST["regDetails"]));


function validateDetails($details){
    $jsonObj=json_decode($details,true);
    foreach($jsonObj as $key => $value) {   
        if (!$value){
            $responsObj='{"message":"Some fields are empty"}';
            echo $responsObj;
            die();
        }else{
            $jsonObj[$key]=sanitize_input($value);
        }
    }
    if ($jsonObj["password"]!=$jsonObj["c_password"]){
        $responsObj='{"message":"passwords dont match"}';
        echo $responsObj;
        die();
    }
    else if (strlen($jsonObj["password"])<5){
        $responsObj='{"message":"passwords too short"}';
        echo $responsObj;
        die();
    }
    return $jsonObj;
}
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
function insertToDb($conn,$dbName,$jsonObj){
    $sql = "select schema_name from information_schema.schemata where schema_name = '$dbName';";
    $result = mysqli_query($conn,$sql);
    $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);

    if(isset($resultDict[0]["schema_name"])!=$dbName){
    $responsObj='{"message":"Service not available"}';
    echo $responsObj;
    die();
    }
    if(!checkLogin($conn,$dbName)){
        $responsObj='{"message":"user not logged in"}';
        echo $responsObj;
        die();
    }
    $sql = "USE $dbName";
    $result = mysqli_query($conn, $sql);
    $sql = "UPDATE users SET first_name ='$jsonObj[f_name]', last_name='$jsonObj[l_name]', DOB ='$jsonObj[dob]',
                             `password`='$jsonObj[password]', details='$jsonObj[details]' WHERE email='$_SESSION[user_email]'";
    $result = mysqli_query($conn,$sql);
    if ($result){
        $responsObj='{"message":"1"}';
        echo $responsObj;
        writeToJSON($conn);
    }else{
        $responsObj='{"message":"0"}';
        echo $responsObj;
   }
   mysqli_close($conn);
}
function writeToJSON($conn){
    $result = mysqli_query($conn,"SELECT * FROM users");
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
    }
    $fp = fopen('server.json', 'w');
    fwrite($fp, json_encode($rows));
    fclose($fp);
}
?>

