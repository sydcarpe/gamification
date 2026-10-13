<?php
require "../databaseInfo.php";
header('Content-Type: application/json');

//erroring checking!
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

//getting quest info
//$quest = json_decode(file_get_contents("php://input"), true);
$questID = $_POST["questID"];
$updatedQuestTitle = $_POST["questTitleUpdate"];
$updatedQuestDesc = $_POST["questDescUpdate"];

//$getQuestInfo = $conn->prepare("SELECT * FROM quest q INNER JOIN questHabits qh on qh.questID = q.ID INNER JOIN questTasks qt on qt.questID = q.ID WHERE q.ID = ?");

//updating quest info 
$updateQuestSQL = $conn->prepare("UPDATE quest SET title = ?, description = ? WHERE id = ?;");

//if it failed.. 
if(!$updateQuestSQL){
    echo json_encode([
        "success" => false, 
        "error" => $conn->error
    ]); 
    exit;
}

$updateQuestSQL->bind_param("ssi", $updatedQuestTitle ,$updatedQuestDesc, $questID);
//if this fails
if(!$updateQuestSQL->execute()){
    echo json_encode([
        "success" => false, 
        "error" => $conn->error
    ]);
    exit;
}

echo json_encode([
    "success" => true, 
    "questID" => $questID
]);

$conn->close();



?>