<?php
error_reporting(0);
$responsObj="";
//login details
session_start();
$_SESSION['user_email']= "";
$_SESSION['user_password']="";


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
    if (!filter_var($jsonObj["email"], FILTER_VALIDATE_EMAIL)){
        $responsObj='{"message":"invalid Email"}';
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
function insertToDb($conn,$dbName,$jsonObj){
    $sql = "select schema_name from information_schema.schemata where schema_name = '$dbName';";
    $result = mysqli_query($conn,$sql);
    $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);

    if(isset($resultDict[0]["schema_name"])!=$dbName){
    $responsObj='{"message":"Service not available"}';
    echo $responsObj;
    die();
    }
    $sql = "USE $dbName";
    $result = mysqli_query($conn, $sql);

    //verify existing user
    $tempMail =$jsonObj['email'];
    $sql = "SELECT email from users where email='$tempMail'";
    $result = mysqli_query($conn, $sql);
    $resultDict = mysqli_fetch_all($result,MYSQLI_ASSOC);
    if($resultDict){
        if ($resultDict[0]["email"] == $jsonObj["email"]){
            $responsObj='{"message":"Email registered already"}';
            echo $responsObj;
            die();
    }}
    //prepared statement
    $result = $conn->prepare("INSERT INTO users (first_name, last_name, DOB, email, `password`, details)VALUES (?,?,?,?,?,?)");
    $result->bind_param("ssssss",$jsonObj['f_name'] ,$jsonObj['l_name'],$jsonObj['dob'],$jsonObj['email'],$jsonObj['password'],$jsonObj['details']);
    
    // $fromJson = "'$jsonObj[f_name]' ,'$jsonObj[l_name]','$jsonObj[dob]','$jsonObj[email]','$jsonObj[password]','$jsonObj[details]'";
    // $sql = "INSERT INTO users (first_name, last_name, DOB, email, `password`, details)VALUES ($fromJson)";
    // $result = mysqli_query($conn,$sql);
    if ($result->execute()){
        $responsObj='{"message":"1"}';
        echo $responsObj;
        writeToJSON($conn);
    }else{
        $responsObj='{"message":"0"}';
        echo $responsObj;
   }
   $result->close();
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

