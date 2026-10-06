<?php
// This is a todo later... I cannot get this atm


require "../databaseInfo.php";

//erroring checking!
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

//getting quest info
//$quest = json_decode(file_get_contents("php://input"), true);
//$questID = $quest["questID"];

$questID = $_GET['questID'];

$getQuestInfo = $conn->prepare("SELECT * FROM quest WHERE ID = ?");
$getQuestInfo->bind_param("i", $questID);
$getQuestInfo->execute();
$getQuestResult = $getQuestInfo->get_result();

$quest = $getQuestResult->fetch_assoc();
//var_dump($questID);
?>
<!--Treat this as "InnerHTML" for the questEditContainer that is called on the questHomePage.php page-->

<div class="editQuestPopUp" id="questEditContainer">
    <div class="editQuestCont" id="editQuestContID">
        <form id="editQuestForm" action="quest/editQuest.php" method="post">
            <h3> Update Quest </h3>
            <p>Name</p>
            <input type='text' name='questTitleUpdate' value="<?php echo $quest['title']; ?>">
            <p> Description </p>
            <textarea name='questDescUpdate'><?php echo $quest['description']; ?></textarea>
            <input type='submit' value="Update">
            <input type='hidden' name='questID' value=<?php echo $questID; ?>>
        </form>
    </div>

    <!--add habit btn-->
    <button class="createHabitsBtn" id="createHabitsBtn"> Add New Habit </button>

    <!-- Add Habits form-->
    <div class="addHabitToQuest" id="addHabitID" style='display:none'>
        <form id="createHabitForm" action="quest/addHabittoQuest.php" method="post"> 
            <p> habit </p>
            <input type='text' name='habitTitle' required>
            <p> description </p>
            <textarea name='habitDesc'></textarea>
            <!--getting questID-->
            <input type='hidden' name='questID' value= <?php echo $questID; ?> >
            <button type="submit" id="createHabitbtn">Create Habit</button>
        </form>
    </div>
</div>