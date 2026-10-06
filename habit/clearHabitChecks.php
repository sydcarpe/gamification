<?php 
require "../databaseInfo.php";

header('Content-Type: application/json');
$user_id;
$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
$yesterday->format('Y-m-d');
$today = new DateTime($today);

$clearHabitChecksSQL = "UPDATE habit h SET completed = false WHERE NOT EXISTS (SELECT * FROM questHabits qh WHERE qh.habitID = h.ID) AND userID = $user_id;";
$clearHabitChecks = $conn->prepare($clearHabitChecksSQL);
$clearHabitChecks->execute();



//to do eventually maybe... if date is > than yesterday set to current streak to 0 
$getAllHabsSQL = "SELECT * FROM habit h WHERE NOT EXISTS (SELECT * FROM questHabits qh WHERE qh.habitID = h.ID) AND userID = $user_id";
$getHabs = $conn->query($getAllHabsSQL);

while ($row = $getHabs->fetch_assoc()){
    $lastCompleted = $row["lastCompletedDate"];
    $habitID = $row["ID"];
    if($lastCompleted < $yesterday){
        //$resetStreakSQL = "UPDATE habit SET currentStreak = 0 WHERE ID = $habitID;";
       // $resetStreak = $conn->prepare($resetStreakSQL);
       // $resetStreak->execute();
    }
}

echo json_encode([
    "success" => true
]);

?>