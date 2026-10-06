<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

$habit = json_decode(file_get_contents("php://input"), true);

$habitID = $habit["habitID"];

//first must delete from dailies
$deleteDailies = $conn->prepare("DELETE FROM Dailies WHERE habitID = ?");
$deleteDailies->bind_param("i", $habitID);
$deleteDailies->execute();

$deleteHabitSQL = ("DELETE FROM habit WHERE ID = ?");
$deleteHabit = $conn->prepare($deleteHabitSQL);
$deleteHabit->bind_param("i", $habitID);
$deleteHabit->execute();

?>
