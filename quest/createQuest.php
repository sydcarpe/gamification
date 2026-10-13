<?php 
require "../databaseInfo.php";

header('Content-Type: application/json');

$questTitle = (trim($_POST['questTitle']));
$questDesc = (trim($_POST['questDescription']));


$createQuestSQL = "INSERT INTO quest(title, description, userID) VALUES(?,?,?);";
$createQuest = $conn->prepare($createQuestSQL);

if(!$createQuest){
    echo json_encode([
        "success" => false,
        "error" => $conn->error
    ]);
    exit;
}

$createQuest->bind_param("ssi", $questTitle, $questDesc, $user_id);
if(!$createQuest->execute()){
    echo json_encode([
        "success"=> false, 
        "error" => $createQuest->error
    ]); 
    exit;
}

echo json_encode([
    "success" => true, 
    "questID" => $createQuest->insert_id
]);

$createQuest->close();
$conn->close();
exit;
?>