<?php 
require "../databaseInfo.php";

header('Content-Type: application/json');

try{
    //first need to get all habits in the dailies 
    $getDailiesSQL = "SELECT * FROM habit h WHERE NOT EXISTS (SELECT * FROM questHabits qh WHERE qh.habitID = h.ID) AND userID = ? ;";
    $getDailies = $conn->prepare($getDailiesSQL);
    $getDailies->bind_param("i", $user_id);
    $getDailies->execute();
    $getDailiesResult = $getDailies->get_result();

    $dailies=[];
    while($row = $getDailiesResult->fetch_assoc()){
        $dailies[] = $row;
    }

    echo json_encode([
        "success" => true,
        "dailies" => $dailies
    ]);

} catch (Exception $e){
    echo json_encode([
        "success" => false,
        "error" => "Could not load"
    ]);
}

?>