<?php 
include "../databaseInfo.php";


$questID = $_GET["questID"];
echo $questID;

echo "Hello I am detailed quest edit page. This is where I will SOON be able to edit the habits, tasks";
?>


<!DOCTYPE html>
<html>
<!--Including the other script pages-->

<head>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/task.css">
    <link rel="stylesheet" href="../css/habit.css">
    <link rel="stylesheet" href="../css/quest.css">


    <!--Font information-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quantico:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <script src="../task/taskManager.js"></script>
    <script src="../task/taskUi.js"></script>
    <script src="../habit/dailyManager.js"></script>
    <script src="../habit/dailyUI.js"></script>
    <script src="questUI.js"></script>
    <script src="questManager.js"></script>
    <script src="questFunctions.js"></script>

</head>

<body>
    <button onclick="window.location.href='../homePage.php';"> Home</button>
    Welcome to my website freak
    </br>
    User Level <p id="level"> <?php echo $userLevel; ?> </p>
    Total Points: <p id="pointsContainer"> <?php echo $totalPoints ?> </p>
    <hr>

    <button onclick="window.location='../questHomePage.php'">back to quest page</button>

</body>

</html>

