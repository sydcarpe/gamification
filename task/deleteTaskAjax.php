<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

$task = json_decode(file_get_contents("php://input"), true);

$taskID = $task["taskID"];

//deleting task from quest - if there is one! 
$taskInQuestSQL = "SELECT t.ID FROM task t INNER JOIN questTasks qt on t.ID = qt.taskID WHERE t.ID = ?; ";
$taskInQuest = $conn->prepare($taskInQuestSQL);
$taskInQuest->bind_param("i", $taskID);
$taskInQuest->execute();
$taskInQuest->store_result();

if($taskInQuest->num_rows > 0){
    $deleteTaskFromQuest = $conn->prepare("DELETE FROM questTasks WHERE taskID = ?;");
    $deleteTaskFromQuest->bind_param("i", $taskID);
    $deleteTaskFromQuest->execute();
}


$deleteTaskSQL = ("DELETE FROM task WHERE ID = ?");
$deleteTask = $conn->prepare($deleteTaskSQL);
$deleteTask->bind_param("i", $taskID);
$deleteTask->execute();

?>
