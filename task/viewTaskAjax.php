<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

$task = json_decode(file_get_contents("php://input"), true);

$taskID = $task["taskID"];


$getTaskSQL = "SELECT * FROM Task WHERE ID = $taskID";
$getTask = $conn->query($getTaskSQL);


if ($getTask->num_rows > 0) {
    $row = $getTask->fetch_assoc();

    echo json_encode([
        "success" => true,
        "task" => $row
    ]);   
} else {
    echo json_encode([
        "success" => false
    ]);
}

?>
