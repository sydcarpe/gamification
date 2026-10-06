<?php 

//currently not being used by anything... will this be important later? not sure 
require "databaseInfo.php";
//Grabbing the user (ME)
session_start();
$user_id = 1; //hard coding for now :0

$getUserInfoSQL = "SELECT * FROM User WHERE ID = $user_id;";
$getUserInfo = $conn->query($getUserInfoSQL);
if($getUserInfo->num_rows > 0){
    //outout for the table
    if($row = $getUserInfo->fetch_assoc()){
        $totalPoints = $row["totalpoints"];
    } 
}

echo json_encode([
    "success"=> true,
    "totalPoints" => $totalPoints
]);

exit;
?>

