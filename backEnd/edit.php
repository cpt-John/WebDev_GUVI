<?php
error_reporting(0);
//login details
session_start();
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
    return $jsonObj;
}
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function insertToDb($conn,$dbName,$jsonObj){
    
    if(!$_SESSION['user_logged_in']){
        echo '{"message":"user not logged in"}';
        mysqli_close($conn);
        die();
    }

    $sql ="UPDATE users SET first_name =?, last_name=?, DOB =?,`password`= ?, details= ? WHERE email= ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo '{"message":"failed"}';
    }else{
        mysqli_stmt_bind_param($stmt,"ssssss",$jsonObj['f_name'],$jsonObj['l_name'],$jsonObj['dob'],
                                $jsonObj['password'],$jsonObj['details'],$_SESSION['user_email']);
        if (mysqli_stmt_execute($stmt)){
            echo '{"message":"1"}';
            writeToJSON($conn);
        }else{
            echo '{"message":"someting went wrong"}';
        }
    }
    mysqli_free_result($result);
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

