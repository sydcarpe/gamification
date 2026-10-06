<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

//preping the quest tasks 
$getQuestTasks = $conn->prepare(" SELECT * FROM task t  INNER JOIN questTasks qt on qt.taskID = t.ID WHERE qt.questID = ?");
$getQuestHabits = $conn->prepare("SELECT * FROM habit h INNER JOIN questHabits qh on qh.habitID = h.ID WHERE qh.questID = ?");

try {
    //first need to get quests
    $getQuestsSQL = "SELECT * FROM quest WHERE userID = ?";
    $getQuest = $conn->prepare($getQuestsSQL);
    $getQuest->bind_param("i", $user_id);
    $getQuest->execute();
    $getQuestResults = $getQuest->get_result();

    $quests = [];
    while ($row = $getQuestResults->fetch_assoc()) {
       // $quests[] = $row;
        $questID = $row['id'];

        //getting the tasks
        $getQuestTasks->bind_param("i", $questID);
        $getQuestTasks->execute();
        $getQuestTasksResults = $getQuestTasks->get_result();

        //getting the habits
        $getQuestHabits->bind_param("i", $questID);
        $getQuestHabits->execute();
        $getQuestHabitsResult = $getQuestHabits->get_result();

        $questTasks = []; //getting the questTask per row

        while ($row2 = $getQuestTasksResults->fetch_assoc()) {
            $questTasks[] = $row2;
        }


        //getting the habits now 
        $questHabits = [];
        while ($row3 = $getQuestHabitsResult->fetch_assoc()){
            $questHabits[] = $row3;
        }

        $row["habits"] = $questHabits;
        $row["tasks"] = $questTasks;
        $quests[] = $row;

        
    }


    echo json_encode([
        "success" => true,
        "quests" => $quests
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => "Could not load"
    ]);
}


function getInProgressQuest($conn){
    $getInProgress = $conn->query("SELECT * FROM quest WHERE status='In Progress'");
    $quests =[];
    while($row = $getInProgress->fetch_assoc()){
        $quests[] = $row;
    } 
    return $quests;

}