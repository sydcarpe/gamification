<?php
require "../databaseInfo.php";
header('Content-Type: application/json');

//erroring checking!
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

//getting quest info
$quest = json_decode(file_get_contents("php://input"), true);
$questID = $quest["questID"];

$getQuestInfo = $conn->prepare("SELECT * FROM quest q INNER JOIN questHabits qh on qh.questID = q.ID INNER JOIN questTasks qt on qt.questID = q.ID WHERE q.ID = ?");
$getQuestInfo->bind_param("i", $questID);



?>