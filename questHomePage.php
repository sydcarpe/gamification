<?php
include "databaseInfo.php";


?>
<!DOCTYPE html>
<html>
<!--Including the other script pages-->

<head>
    <link rel="stylesheet" href="../css/questHomePage.css">


    <!--Font information-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quantico:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <script src="task/taskManager.js"></script>
    <script src="task/taskUi.js"></script>
    <script src="habit/dailyManager.js"></script>
    <script src="habit/dailyUI.js"></script>
    <script src="quest/questUI.js"></script>
    <script src="quest/questManager.js"></script>
    <script src="quest/questFunctions.js"></script>

</head>

<body>
    <button onclick="window.location.href='../homePage.php';"> Home</button>
    Welcome to my website freak
    </br>
    User Level <p id="level"> <?php echo $userLevel; ?> </p>
    Total Points: <p id="pointsContainer"> <?php echo $totalPoints ?> </p>
    <hr>


    <!--This is displaying all quests. I am thinking I may wrap this container-->

    <h2> All Quests </h2>
    <button id="createNewQuestBtn" onclick="createQuestDisplay()"> Create New Quest </button>

    <div id="questContainer"> <!--where all quests populates?-->
    </div>
    <!-- what if I make this a popup as well?-->
    <div id="singleQuestViewer"> <!--Where a single quest displays on click  --></div>

    <!--Make this a pop up?-->

    <div id="questEditContainer" class="questEditContainer" style="display:none;"><!-- This is the entire popup to dsiplay --></div>
    <div id="createQuestContainer" class="createQuestContainer" style="display:none;">
        <form id="createQuestForm" method="post" action="quest/createQuest.php">
            <h2> Create new Quest </h2>
            <p> Quest Name </p>
            <input type="text" required name="questTitle">
            <p> description </p>
            <input type="textbox" name="questDescription">
            <input type="submit" value="Create Quest">
        </form>

    </div>


    <script>
        //basic User stuff (points)
        const pointsContainer = document.getElementById("pointsContainer");


        //Quest stuff start
        const questContainer = document.getElementById("questContainer");
        let currentQuestID = null;
        const singleQuestViewer = document.getElementById("singleQuestViewer");
        const viewAllQuestBtn = document.getElementById("viewAllQuests");
        const questCard = document.getElementById("questCard");


        //updating quest tasks/habits when checkbox is clicked 
        questContainer.addEventListener("change", (event) => {
            if (event.target.classList.contains("task-checkbox")) {
                updateTaskStatus(event);
            }
            if (event.target.classList.contains("habit-checkbox")) {
                updateHabitStatus(event);
            }
        })

        



        //viewing quest when clicked 
        questContainer.addEventListener("click", (event) => {

            const singleQuest = document.getElementById("singleQuest");
            if (event.target.classList.contains("questSingleTitle")) {
                const questDiv = event.target.closest(".singleQuest");
                if (!questDiv) return;
                const questID = questDiv.dataset.id;
                viewQuest(questID);
            }

            //deleting the habit when clicked 
            if (event.target.classList.contains("deleteHabitBtn")) {
                const habitID = event.target.dataset.id;
                console.log("Deleteing: ", habitID);
                deleteHabit(habitID);
            }

            //deleting the task when clicked :) 
            if (event.target.classList.contains("deleteTaskBtn")) {
                const taskID = event.target.dataset.id;
                console.log("Deleting task: ", taskID);
                deleteTask(taskID);
            }

            //TODO create QuestHabit & QuestTask
            //getting the const of the different IDS on quests 

            const viewCreateQuestTaskBtn = document.getElementById("viewCreateQuestTaskBtn");
            const questTaskPopup = document.getElementById("createQuestTaskPopup");


        });


        //deleting and editing the quest
        singleQuestViewer.addEventListener("click", async (event) => {
            if (event.target.classList.contains("deleteQuestBtn")) {
                const questID = event.target.dataset.id;
                //console.log(questID);
                deleteQuest(questID);
            }

            //editing the quest
            if (event.target.classList.contains("editQuestBtn")) {
                const questID = event.target.dataset.id;
                //console.log("questID= " + questID);
                const viewQuestDisplay = await fetch(`quest/editQuestDisplay.php?questID=${questID}`);
                questEditContainer.style.display = "block";
                const questHTML = await viewQuestDisplay.text();
                questEditContainer.innerHTML = questHTML;
            }

            //navigating to a new page to a more detailed edit
            if (event.target.classList.contains("detailedEditQuest")) {
                console.log("Detail!");
                const questID = event.target.dataset.id;

                window.location.href = `quest/detailedQuestEdit.php?questID=${questID}`;
            }

        });

        //Create Quest Stuff
        const createQuestContainer = document.getElementById("createQuestContainer");
        const createQuestBtn = document.getElementById("createQuestBtn");

        function createQuestDisplay() {
            createQuestContainer.style.display = "block";
            console.log("you've been clicked");
        }

        createQuestContainer.addEventListener("submit", async (event) => {
            if (event.target.id !== "createQuestForm") return;

            event.preventDefault();

            const createQuestForm = event.target;
            const creatQuestFormData = new FormData(createQuestForm);

            try {
                const createQuestResponse = await fetch("quest/createQuest.php", {
                    method: "POST",
                    body: creatQuestFormData
                })
                const responseText = await createQuestResponse.text();
                console.log("RAW PHP: ", responseText);
                const result = JSON.parse(responseText);
                console.log("Parsed: ", result);

                if (result.success) {
                    console.log("quest created");
                    createQuestForm.reset();
                    await refreshQuests();
                    createQuestContainer.style.display = "none";

                } else {
                    console.error(result.message);
                }
            } catch (err) {
                console.error(err);
            }
        })

        //quest pop up edit page :p
        const editQuestPopup = document.getElementById("editQuestPopUp");
        const questEditContainer = document.getElementById("questEditContainer");

        //now while in here, if the user clicks create habit it will submit and create the habit while adding it to the quest
        questEditContainer.addEventListener("submit", async (event) => {
            if (event.target.id !== "createHabitForm" && event.target.id !== "createTaskForm" && event.target.id !== "editQuestForm") return;
            event.preventDefault();

            const form = event.target;
            //using this to create which form i am sending it to :p 
            //const endGoal = form.id === "createHabitForm" ? "quest/addHabittoQuest.php" : "quest/addTasktoQuest.php"; //fancy way of doing if -> else

            if (form.id === "createHabitForm") {
                endGoal = "quest/addHabittoQuest.php";
            } else if (form.id === "createTaskForm") {
                endGoal = "quest/addTasktoQuest.php";
            } else if (form.id === "editQuestForm") {
                endGoal = "quest/editQuest.php";
            }

            const formData = new FormData(form);

            //gathering form data
            try {
                const response = await fetch(endGoal, {
                    method: "POST",
                    body: formData
                })

                const responseText = await response.text();
                console.log("RAW PHP: ", responseText);

                const result = JSON.parse(responseText);
                console.log("parsed PHP RESULT: ", result);

                if (result.success) {
                    console.log("quest updated");
                    form.reset();
                    await refreshQuests();
                    questEditContainer.style.display = "none";


                } else {
                    console.error(result.message);
                }
            } catch (err) {
                console.error(err);
            }

            try {

            } catch (err) {
                console.error(err);
            }

        })


        questEditContainer.addEventListener("click", async (event) => {
            const questInfo = document.getElementById("editQuestContID");
            const addHabitsDisplay = document.getElementById("addHabitID");
            const addTaskDisplay = document.getElementById("taskCreationDisplay");
            const createHabitBtn = document.getElementById("createHabitsBtn");
            const addTaskBtn = document.getElementById("createTaskBtn");

            if (event.target.id === "createHabitsBtn") {
                //create the variables WITHIN the event listeners!!
                addHabitsDisplay.style.display = "block";
                questInfo.style.display = "none";
                createHabitBtn.style.display = "none";
                addTaskBtn.style.display = "none";
            }


            if (event.target.id === "createTaskBtn") {
                addTaskDisplay.style.display = "block";
                questInfo.style.display = "none";
                createHabitBtn.style.display = "none";
                addTaskBtn.style.display = "none";

            }

        })


        refreshQuests();
    </script>
</body>

</html>