<?php
require "../databaseInfo.php";

header('Content-Type: application/json');


$taskTitle = (trim($_POST['taskTitle'] ?? ''));
$taskDescription = (trim($_POST['taskDesc'] ?? ''));
$questID = $_POST['questID'];

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

$taskID = $createTask->insert_id;


//Adding it to the quest now 
$addTasktoQuestSQL = "INSERT INTO questTasks(questID, taskID) VALUES (?,?);";
$addTasktoQuest = $conn->prepare($addTasktoQuestSQL);

if(!$addTasktoQuest){
    echo json_encode([
        "success" => false, 
        "error" => $conn->error
    ]);
    exit;
}

$addTasktoQuest->bind_param("ii", $questID, $taskID);

if(!$addTasktoQuest->execute()){
    echo json_encode([
        "success" => false, 
        "error" => "Could not add to Quest" . $addTasktoQuest->error
    ]);
    exit;
}

//getting new data 
echo json_encode([
    "success" => true,
    "taskID" => $createTask->insert_id
]);

$createTask->close();
$addTasktoQuest->close();
$conn->close();

exit;
?>
