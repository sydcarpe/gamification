<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

$habit = json_decode(file_get_contents("php://input"), true);

$habitID = $habit["habitID"];


$getHabitSQL = "SELECT * FROM habit WHERE ID = $habitID";
$getHabit = $conn->query($getHabitSQL);


if ($getHabit->num_rows > 0) {
    $row = $getHabit->fetch_assoc();

    echo json_encode([
        "success" => true,
        "habit" => $row
    ]);   
} else {
    echo json_encode([
        "success" => false
    ]);
}

?>
