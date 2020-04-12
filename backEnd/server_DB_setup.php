<?php
error_reporting(0);
//server connection details
$servername = "localhost";
$server_username = "root";
$server_password = "admin";

//db details
$dbName="user_acc";

// Create connection
$conn = new mysqli($servername, $server_username, $server_password);


//setup is only for first time

// function setupDB($conn,$dbName){
//     //create db
//     $sql = "CREATE DATABASE  IF NOT EXISTS $dbName";
//     $result = mysqli_query($conn, $sql);
//     $sql = "USE $dbName";
//     $result = mysqli_query($conn, $sql);
//     //cerate table
//     $sql = "CREATE TABLE users(
//     id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//     first_name VARCHAR(30) NOT NULL,
//     last_name VARCHAR(30) NOT NULL,
//     DOB DATE NOT NULL,
//     email VARCHAR(70) NOT NULL UNIQUE,
//     `password` VARCHAR(70) NOT NULL ,
//     details VARCHAR(150) 
//     )";
//     mysqli_query($conn, $sql);
// }
?>