<?php
include "../databaseInfo.php";

header("Content-Type: application/json");

$task = json_decode(file_get_contents("php://input"), true);


$taskID = $task["id"];
$completed = $task["completed"];

//getting the task points. they make it too hard! 
$getTaskInfo = $conn->query("SELECT * FROM task WHERE ID = $taskID");
if($row=$getTaskInfo->fetch_assoc()){
    $pointsWorth = $row["points"];
}

if($completed){
    //if true console write something
    $totalPoints = $totalPoints + $pointsWorth;
} else if (!$completed){
    $totalPoints = $totalPoints - $pointsWorth;
}

echo json_encode([
    "success" => true, 
    "id" => $taskID,
    "completed" => $completed,
    "totalPoints" => $totalPoints
]);



$updateCheckbox = $conn->prepare("UPDATE Task SET completed = ? WHERE ID = ?");
$updateCheckbox->bind_param("ii", $completed, $taskID); //Bools come in as 0 or 1 
$updateCheckbox->execute();

$updateUser = $conn->prepare("UPDATE user SET totalPoints = ? WHERE ID = ?");
$updateUser->bind_param("ii", $totalPoints, $user_id);
$updateUser->execute();

?>