<?php
require "../databaseInfo.php";

header('Content-Type: application/json');


$taskTitle = (trim($_POST['taskTitle'] ?? ''));
$taskDescription = (trim($_POST['taskDesc'] ?? ''));
$questID = $_POST['questID'];

$createTaskSQL = "INSERT INTO task(title, description, userID) VALUES (?,?,?);";
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

echo json_encode([
    "success" => true, 
    "taskID" => $createTask->insert_id
]);

$taskID = $createTask->insert_id; //creating and getting the new taskID
$addTasktoQuest->bind_param("ii", $questID, $taskID); //adding into the quest table

if(!$addTasktoQuest->execute()){
    echo json_encode([
        "success" => false, 
        "error" => "Could not add to Quest" . $addTasktoQuest->error
    ]);
    exit;
}

/*
//getting new data 
echo json_encode([
    "success" => true,
    "taskID" => $createTask->insert_id
]);
*/
$createTask->close();
$addTasktoQuest->close();
$conn->close();

exit;
?>
