<?php
include "../databaseInfo.php";

header("Content-Type: application/json");

$habit = json_decode(file_get_contents("php://input"), true);
//getting users total points


$habitID = $habit["id"];
$completed = $habit["completed"];
//today's date to update the checkbox
$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
//$yesterday->format('Y-m-d');
$today = new DateTime($today);


//getting the longeststreak and such 
$getHabitInfoSQL = "SELECT * FROM habit WHERE ID = $habitID;";
$getHabitInfo = $conn->query($getHabitInfoSQL);


//getting the streaks and dates
if ($row = $getHabitInfo->fetch_assoc()) {
    $longestStreak = $row['longestStreak'];
    $currentStreak = $row['currentStreak'];
    $tempDate = $row['lastCompletedDate'];
    $pointsWorth = $row['points'];

    if ($tempDate == null) {
        $tempDate = '1900-01-01';
    }
    $lastCompletedDate = new DateTime($tempDate);
}




//ignoring when setting to 0
if ($completed == false) {
    //do nada because it is NOT true
    $lastCompletedDate = $lastCompletedDate->format('Y-m-d');
}



if ($completed == true) {
    //add to the points but only once! If I check it off twice in a day bad bad bad
    if ($lastCompletedDate == $today) {
        //do nothing? Leave it as is 
    }

    //if the last time completed was yesterday... then 
    if ($lastCompletedDate == $yesterday) {
        $currentStreak++;
        $totalPoints = $totalPoints + $pointsWorth; //addin points

        if ($currentStreak > $longestStreak) {
            $longestStreak = $currentStreak;
        }
        $lastCompletedDate = $today;
    } else if ($lastCompletedDate < $yesterday) {
        //adding points
        $totalPoints = $totalPoints + $pointsWorth;

        $currentStreak = 0;
        $lastCompletedDate = $today;
        $currentStreak++;
    }



    $lastCompletedDate = $lastCompletedDate->format('Y-m-d');
}

$yesterday = $yesterday->format('Y-m-d');

echo json_encode([
    "success" => true,
    "id" => $habitID,
    "completed" => $completed,
    "longestStreak" => $longestStreak,
    "currentStreak" => $currentStreak,
    "lastCompletedDate" => $lastCompletedDate,
    "totalPoints" => $totalPoints,
    "yesterday" => $yesterday
]);


$updatePoints = $conn->prepare("UPDATE user SET totalPoints = $totalPoints");
$updatePoints->execute();

$updateCheckbox = $conn->prepare("UPDATE habit SET completed = ?, longestStreak = ?, currentStreak = ?, lastCompletedDate = ? WHERE ID = ?");
$updateCheckbox->bind_param("iiisi", $completed, $longestStreak, $currentStreak, $lastCompletedDate, $habitID); //Bools come in as 0 or 1 
$updateCheckbox->execute();


