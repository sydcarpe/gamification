<?php
require "../databaseInfo.php";

header('Content-Type: application/json');
$quest = json_decode(file_get_contents("php://input"), true);

//$questID = $quest["questID"];

try {
    //first need to get quests
    $getQuestTasksSQL = "SELECT * FROM questTasks qt INNER JOIN quest q on q.ID = qt.questID INNER JOIN task t on t.ID = qt.taskID WHERE q.userID = ? AND q.questID = ?;";
    $getQuestTasks = $conn->prepare($getQuestTasksSQL);
    $getQuestTasks->bind_param("ii", $user_id, $questID);
    $getQuestTasks->execute();
    $getQuestTaskResults = $getQuestTasks->get_result();

    $questTasks = [];
    while ($row = $getQuestTaskResults->fetch_assoc()) {
        $questTasks[] = $row;
    }

    echo json_encode([
        "success" => true,
        "questTasks" => $questTasks
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => "Could not load"
    ]);
}
