<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

$task = json_decode(file_get_contents("php://input"), true);

$taskID = $task["taskID"];


$deleteTaskSQL = ("DELETE FROM Task WHERE ID = ?");
$deleteTask = $conn->prepare($deleteTaskSQL);
$deleteTask->bind_param("i", $taskID);
$deleteTask->execute();

?>
