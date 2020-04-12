
<?php
error_reporting(0);
// Create connection
$conn = new mysqli($servername, $server_username, $server_password);

// Check connection
if (mysqli_connect_errno()) {
    echo '{"message":"Service not available"}';
    die();
}


//check db exists 
$sql ="SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ? ";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt,$sql)){
    echo '{"message":"failed"}';
}else{
    mysqli_stmt_bind_param($stmt,"s",$dbName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result) ;
    if(!$row){
        $responsObj='{"message":"Service not available"}';
        echo $responsObj;
        mysqli_close($conn);
        die();
    }else{
        $sql = "USE $dbName";
        $result = mysqli_query($conn, $sql);
    }
    mysqli_free_result($result);
}

?>