<?php 
require "../databaseInfo.php";

header('Content-Type: application/json');

try{
    $getTasksSQL = "SELECT * FROM task t WHERE NOT EXISTS (SELECT * FROM questTasks qt WHERE qt.taskID = t.ID) AND userID = ?";
    $getTasks = $conn->prepare($getTasksSQL);
    $getTasks->bind_param("i", $user_id);
    $getTasks->execute();
    $getTaskResult = $getTasks->get_result();

    $tasks=[];
    while($row = $getTaskResult->fetch_assoc()){
        $tasks[] = $row;
    }

    echo json_encode([
        "success" => true,
        "tasks" => $tasks
    ]);
} catch (Exception $e){
    echo json_encode([
        "success" => false,
        "error" => "Could not load"
    ]);
}

?>