<?php 
require "../databaseInfo.php";
header("Content-Type : application/json");
$quest = json_decode(file_get_contents("php://input"), true);

//ignore the errors... it's working as expected


$questID = $quest["id"];
$newStatus = $quest["newStatus"];
//$currentStatus = $quest["status"];
$getCurrentStatus= $conn->prepare("SELECT status, totalXP FROM quest WHERE ID = $questID");
$getCurrentStatus->execute();
$currentStatusResults = $getCurrentStatus->get_result();
$getstatus = $currentStatusResults->fetch_assoc();
$currentStatus = $getstatus["status"];
$xpAmount = $getstatus["totalXP"];


//getting points 
if($currentStatus === "Complete" ){
    $totalPoints = $totalPoints - $xpAmount;
} elseif ($newStatus === "Complete") {
    $totalPoints = $totalPoints + $xpAmount;
}
//updating user total points 
$updatingUserPoints->bind_param("i", $totalPoints);
$updatingUserPoints->execute();


//this part will be logic to update the status and the points after the quest is done 

echo json_encode([
    "success" => true, 
    "id" => $questID,
    "status" => $newStatus,
    "currentStatus" => $currentStatus,
    "totalPoints" => $totalPoints
]);



$updateStatus = $conn->prepare("UPDATE quest SET status = ? WHERE id = ?;");
$updateStatus->bind_param("si", $newStatus, $questID);
$updateStatus->execute();

?>