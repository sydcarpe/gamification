<?php 
$servername = "localhost";
$username = "root";
$password = "password123";
$dbname = "life_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

//Grabbing the user (ME)
session_start();
$user_id = 1; //hard coding for now :0

$getUserInfoSQL = "SELECT * FROM User WHERE ID = $user_id;";
$getUserInfo = $conn->query($getUserInfoSQL);
if($getUserInfo->num_rows > 0){
    //outout for the table
    if($row = $getUserInfo->fetch_assoc()){
        $f_Name = $row["fname"];
        $l_Name = $row["lname"];
        $user_Desc = $row["userDesc"];
        $totalPoints = $row["totalpoints"];
        $userLevel = $row["level"];
    } 
}

//frequently used SQL statements

//updating the total points! 
$updatingUserPoints = $conn->prepare("UPDATE user SET totalPoints = ?");



?>

