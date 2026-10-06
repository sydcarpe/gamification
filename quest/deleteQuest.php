<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

//erroring checking!
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

$quest = json_decode(file_get_contents("php://input"), true);

$questID = $quest["questID"];

//first must delete and tasks
try {
    //first need to get quests
    $getQuestTasksSQL = "SELECT * FROM questTasks qt INNER JOIN quest q on q.ID = qt.questID INNER JOIN task t on t.ID = qt.taskID WHERE q.ID = ?;";
    $getQuestTasks = $conn->prepare($getQuestTasksSQL);
    $getQuestTasks->bind_param("i", $questID);
    $getQuestTasks->execute();

    $getQuestTaskResults = $getQuestTasks->get_result();

    $questTasks = [];

    //deleting questTasks and questHabits
    $deletequestTask = $conn->prepare("DELETE FROM questTasks WHERE questID = ?;");
    $deletequestTask->bind_param("i", $questID);
    $deletequestTask->execute();
    $deletequestTask->close();

    $deleteQuestHabit = $conn->prepare("DELETE FROM questHabits WHERE questID = ? ;");
    $deleteQuestHabit->bind_param("i", $questID);
    $deleteQuestHabit->execute();
    $deleteQuestHabit->close();



    //deleting the quest tasks 
    while ($row = $getQuestTaskResults->fetch_assoc()) {
        $questTasks[] = $row;
        foreach ($questTasks as $task) {
            $deleteTask = $conn->prepare("DELETE FROM task WHERE ID = ?");

            $taskID = $task["ID"];
            $deleteTask->bind_param("i", $taskID);
            $deleteTask->execute();
            $deleteTask->close();
        }
    }


    //now this is the habit deleting part - first getting the all the habits and their IDs
    $getQuestHabits = $conn->prepare("SELECT * FROM questHabits qh INNER JOIN quest q on q.ID = qh.questID INNER JOIN habit h on h.ID = qh.habitID WHERE q.ID = ? ;");
    $getQuestHabits->bind_param("i", $questID);
    $getQuestHabits->execute();
    $getQuestHabitsResult = $getQuestHabits->get_result();

    $questHabits = [];

    //now we are deleting habits 
    while ($row = $getQuestHabitsResult->fetch_assoc()) {
        $questHabits[] = $row;

        foreach ($questHabits as $habit) {
            $deleteHabit = $conn->prepare("DELETE FROM habit WHERE ID = ? "); //deleting habit
            $habitID = $habit["ID"];
            $deleteHabit->bind_param("i", $habitID);
            $deleteHabit->execute();
            $deleteHabit->close();
        }
    }


    //now that those are deleted.... time to delete the quest itself 
    $deleteQuest = $conn->prepare("DELETE FROM quest WHERE id = ?");
    $deleteQuest->bind_param("i", $questID);
    $deleteQuest->execute();

    echo json_encode([
        "success" => true
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}


/*
$deleteHabits = $conn->prepare("DELETE FROM questHabits WHERE questID = ?");
$deleteHabits->bind_param("i", $habitID);
$deleteHabits->execute();

$deleteHabitSQL = ("DELETE FROM habit WHERE ID = ?");
$deleteHabit = $conn->prepare($deleteHabitSQL);
$deleteHabit->bind_param("i", $habitID);
$deleteHabit->execute();
*/
