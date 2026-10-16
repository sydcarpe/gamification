<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

$habit = json_decode(file_get_contents("php://input"), true);

$habitID = $habit["habitID"];
//$questID = $quest["questID"] ?? null;

//if it is in a quest, delete from that quest! But first, must see if there are!
$habitInQuestSQL = "SELECT h.ID FROM habit h INNER JOIN questHabits qh on h.ID = qh.habitID WHERE h.ID = ?;";
$habitInQuest = $conn->prepare($habitInQuestSQL);
$habitInQuest->bind_param("i", $habitID);
$habitInQuest->execute();
$habitInQuest->store_result();

//deleting from the quest if the row is not empty
if ($habitInQuest->num_rows > 0) {
    $deleteHabitFromQuest = $conn->prepare("DELETE FROM questHabits WHERE habitID = ?;");
    $deleteHabitFromQuest->bind_param("i", $habitID);
    $deleteHabitFromQuest->execute();
}



//first must delete from dailies
$deleteDailies = $conn->prepare("DELETE FROM Dailies WHERE habitID = ?");
$deleteDailies->bind_param("i", $habitID);
$deleteDailies->execute();

$deleteHabitSQL = ("DELETE FROM habit WHERE ID = ?");
$deleteHabit = $conn->prepare($deleteHabitSQL);
$deleteHabit->bind_param("i", $habitID);
$deleteHabit->execute();
