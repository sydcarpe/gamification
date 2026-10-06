<?php
require "../databaseInfo.php";

header('Content-Type: application/json');
$user_id;

$deleteCompletedTasksSQL = "DELETE FROM task t WHERE NOT EXISTS (SELECT * FROM questTasks qt WHERE qt.taskID = t.ID) AND completed = true AND userID = $user_id;";
$deleteCompletedTasks = $conn->prepare($deleteCompletedTasksSQL);
$deleteCompletedTasks->execute();

echo json_encode([
    "success" => true
]);

?>