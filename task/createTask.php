<?php
require "../databaseInfo.php";

header('Content-Type: application/json');


$taskTitle = (trim($_POST['taskTitle'] ?? ''));
$taskDescription = (trim($_POST['taskDesc'] ?? ''));

$createTaskSQL = "INSERT INTO Task(title, description, userID) VALUES (?,?,?);";
$createTask = $conn->prepare($createTaskSQL);
if(!$createTask){
    echo json_encode([
        "success" =>false,
        "error" => $conn->error
    ]);
    exit;
}
$createTask->bind_param("ssi", $taskTitle, $taskDescription, $user_id);

if(!$createTask->execute()){
    echo json_encode([
        "success" => false,
        "error" => "Execute fail:" . $createTask->error
    ]);
    exit;
}


//getting new data 
echo json_encode([
    "success" => true,
    "taskID" => $createTask->insert_id
]);

$createTask->close();
$conn->close();

exit;
?>
