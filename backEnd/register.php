<?php
error_reporting(0);
//login details
session_start();
$_SESSION['user_email']= "";
$_SESSION['user_password']="";
?>


<?php 
//server connection details && db details && Create connection
include "server_DB_setup.php" ?>

<?php 
//check server and db connection
include "check_server_DB.php" ?>


<?php
insertToDb($conn,$dbName,validateDetails($conn,$_REQUEST["regDetails"]));


function validateDetails($conn,$details){
    $jsonObj=json_decode($details,true);
    foreach($jsonObj as $key => $value) {   
        if (!$value){
            echo '{"message":"Some fields are empty"}';
            mysqli_close($conn);
            die();
        }else{
            $jsonObj[$key]=sanitize_input($value);
        }
    }
    if ($jsonObj["password"]!=$jsonObj["c_password"]){
        echo '{"message":"passwords dont match"}';
        mysqli_close($conn);
        die();
    }
    else if (strlen($jsonObj["password"])<5){
        echo '{"message":"passwords too short"}';
        mysqli_close($conn);
        die();
    }
    if (!filter_var($jsonObj["email"], FILTER_VALIDATE_EMAIL)){
        echo '{"message":"invalid Email"}';
        mysqli_close($conn);
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
function insertToDb($conn,$dbName,$jsonObj){

    ?>
    <?php 
    //logout existing user
    include "logout.php" 
    ?>
    <?php

    $register= true;
    //verify existing user
    $sql ="SELECT email from users where email=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo '{"message":"failed"}';
    }else{
    mysqli_stmt_bind_param($stmt, "s",$jsonObj['email']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result) ;
        if ($row){
            echo'{"message":"Email registered already"}';
            $register=false;
        }
    }
    mysqli_free_result($result);

    
    //prepared statement
    if($register){
        $result = $conn->prepare("INSERT INTO users (first_name, last_name, DOB, email, `password`, details)VALUES (?,?,?,?,?,?)");
        $result->bind_param("ssssss",$jsonObj['f_name'] ,$jsonObj['l_name'],$jsonObj['dob'],$jsonObj['email'],$jsonObj['password'],$jsonObj['details']);
        if ($result->execute()){
            echo '{"message":"1"}';
            writeToJSON($conn);
        }else{
            echo '{"message":"0"}';
        }
        $result->close();
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

