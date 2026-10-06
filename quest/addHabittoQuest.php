<?php
require "../databaseInfo.php";

header('Content-Type: application/json');

//getting habit info and the questID to add into the quest
$questID = $_POST['questID'];

$habitTitle = (trim($_POST['habitTitle'] ?? ''));
$habitDesc = (trim($_POST['habitDesc'] ?? ''));

$createHabitSQL = "INSERT INTO habit(title, description, userID) VALUES (?,?,?);";
$createHabit = $conn->prepare($createHabitSQL);
if(!$createHabit){
    echo json_encode([
        "success" =>false,
        "error" => $conn->error
    ]);
    exit;
}
$createHabit->bind_param("ssi", $habitTitle, $habitDesc, $user_id);

if(!$createHabit->execute()){
    echo json_encode([
        "success" => false,
        "error" => "Execute fail:" . $createHabit->error
    ]);
    exit;
}

//removing this for now
/*
$addDailySQL = "INSERT INTO dailies(habitID, userID) VALUES(?,?)";
$addDaily = $conn->prepare($addDailySQL);
if(!$addDaily){
    echo json_encode([
        "success"=> false, 
        "error" => $conn->error
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "habitID" => $createHabit->insert_id
]);

$habitID = $createHabit->insert_id;

$addDaily->bind_param("ii", $habitID, $user_id);

if(!$addDaily->execute()){
    echo json_encode([
        "success" => false, 
        "error" => "failed in daily" . $addDaily->error
    ]);
    exit;
}
    */

//adding the habit to quest
$addQuestHabitSQL = "INSERT INTO questHabits(questID, habitID) VALUES(?,?)";
$addQuestHabit = $conn->prepare($addQuestHabitSQL);

if(!$addQuestHabit){
    echo json_encode([
        "success"=> false, 
        "error" => $conn->error
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "habitID" => $createHabit->insert_id
]);

$habitID = $createHabit->insert_id;

$addQuestHabit->bind_param("ii", $questID, $habitID);

if(!$addQuestHabit->execute()){
    echo json_encode([
        "success" => false, 
        "error" => "failed in quest" . $addQuestHabit->error
    ]);
    exit;
}

$createHabit->close();
$addQuestHabit->close();
$conn->close();

exit;
?>
