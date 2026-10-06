<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

$quest = json_decode(file_get_contents("php://input"), true);

$questID = $quest["questID"];


$getQuestSQL = "SELECT * FROM quest WHERE ID = $questID";
$getQuest = $conn->query($getQuestSQL);


if ($getQuest->num_rows > 0) {
    $row = $getQuest->fetch_assoc();

    echo json_encode([
        "success" => true,
        "quest" => $row
    ]);   
} else {
    echo json_encode([
        "success" => false
    ]);
}



?>
