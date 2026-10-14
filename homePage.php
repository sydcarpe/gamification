<?php
include "databaseInfo.php";

?>
<!DOCTYPE html>
<html>
<!--Including the other script pages-->

<head>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/task.css">
    <link rel="stylesheet" href="css/habit.css">
    <link rel="stylesheet" href="css/quest.css">


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
    Welcome to my website freak
    </br>
    User Level <p id="level"> <?php echo $userLevel; ?> </p>
    Total Points: <p id="pointsContainer"> <?php echo $totalPoints ?> </p>
    <hr>

    <div class="bodyContainer">
        <div class="containerOne">
            <!-- All task information below here-->
            <div id="taskContainer">
                <!--This is where the tasks go! -->
            </div>
            <button type="submit" id="deleteCompleteTasks">Delete Finished</button>

            <div id="taskDetails"></div>
            <button onclick="createTaskPopUp()" id="createbtn">+</button>

            <!--This is the task creation form. I can move this to whatever page once done :) -->
            <div class="createTaskPopup" style="display:none;" id="taskCreationDisplay">
                <form id="createTaskForm" method="POST">
                    <!--<p> task </p>-->
                    <input type='text' name='taskTitle' required>
                    <!--<p> description </p>
                    <textarea name='taskDesc'></textarea> -->
                    <button type="submit" id="createTaskBtn">&#10003;</button>
                </form>
                <div id="tempMessage"></div>
            </div>

            <!--End of task info-->

            <!--Start of dailies and habits-->
            <div class="habitsContainer">
                <h2>Habits</h2>
                <div id="dailyTodo">
                    <!--this is where the dailies populate-->
                </div>

                <div id="habitDetails"></div>
                <!-- where the habit dailies creation goes-->

                <button onclick="createHabitPopup()" id="viewCreateHabitBtn"> + </button>
                <div class="createHabitPopup" style="display:none;" id="habitCreationDisplay">
                    <form id="createHabitForm" method="post">
                        <!--<p> habit </p>-->
                        <div class="createHabitContainer">
                            <input type='text' name='habitTitle' required>
                            <!-- <p> description </p>
                            <textarea name='habitDesc'></textarea> -->
                            <button type="submit" id="createHabitbtn">&#10003;</button>
                            <button type="button"id="closeHabitCreation"> x </button>
                        </div>
                    </form>
                </div>
                <button type="submit" id="clearHabitCheckboxes">Reset Checkboxes </button>
            </div>
        </div>


        <!--Quest things -->
        <div class="containerTwo">
            <h2> In Progress Quests </h2>
            <div id="questContainer">
            </div>
            <div id="singleQuestViewer"> <!--Where a single quest displays on click  --></div>
            <button id="viewAllQuests" class="viewAllQuestBtn" onclick="window.location.href='questHomePage.php';">View All Quests</button>

        </div>
    </div>

    <script>
        //generic stuff 
        function escapeHtml(str = "") {
            return str
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        }

        //getting user information stuff
        const pointsContainer = document.getElementById("pointsContainer");

        // task stuff
        // getting the ids of items on this page :) 
        const taskContainer = document.getElementById("taskContainer");
        const singleTaskView = document.getElementById("taskDetails");
        const taskCreationDisplay = document.getElementById("taskCreationDisplay");
        const createTaskForm = document.getElementById("createTaskForm");
        const deleteCompleteTasks = document.getElementById("deleteCompleteTasks");
        let currentTaskID = null;

        //deleting all completed tasks
        deleteCompleteTasks.addEventListener("click", (event) => {
            deleteCompletedTasks();
        });

        //creating new tasks 
        createTaskForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            await createTask();
        })

        // updating tasks when checkbox is clicked
        taskContainer.addEventListener("change", (event) => {
            if (event.target.classList.contains("task-checkbox")) {
                updateTaskStatus(event);
            }
        });

        // viewing the task when clicked 
        taskContainer.addEventListener("click", (event) => {
            if (event.target.classList.contains("indivdualTask")) {
                const taskDiv = event.target.closest(".singleTask");

                if (!taskDiv) return;
                const taskID = taskDiv.dataset.id;

                viewTask(taskID);
            }

            //deleteing the task 
            if(event.target.classList.contains("deleteTaskBtn")){
                const taskID = event.target.dataset.id;
                //console.log("deleting: ", taskID);
                deleteTask(taskID)
            }


        });


        //end of task stuff


        //Habit stuff
        const dailyTodo = document.getElementById("dailyTodo");
        const createHabitForm = document.getElementById("createHabitForm");
        const habitCreationDisplay = document.getElementById("habitCreationDisplay");
        const singleHabitView = document.getElementById("habitDetails");
        const clearHabitCheckboxes = document.getElementById("clearHabitCheckboxes");
        const habitTitle = document.getElementById("individualHab");
        let currentHabitID = null;


        //creating the habit on click
        createHabitForm.addEventListener("submit", async (event) => {
            const habitData = new FormData(createHabitForm);
            const createHabitbtn = document.getElementById("createHabitbtn");
            event.preventDefault();
            await createHabit(habitData);
            createHabitForm.reset();
            refreshDailies();
        });

        createHabitForm.addEventListener("click", async (event) => {
            if(event.target.classList.contains("closeHabitCreation")){
                
            }
        })

        //updating habit status when clicked - this is good 
        dailyTodo.addEventListener("change", (event) => {
            if (event.target.classList.contains("habit-checkbox")) {
                updateHabitStatus(event);
            }
        });

        //viewing the habit when clicking the title AND deleting when click "X" 
        dailyTodo.addEventListener("click", (event) => {

            if (event.target.classList.contains("individualHab")) {
                const habitDiv = event.target.closest(".individualHab");
                if (!habitDiv) return;
                const habitID = habitDiv.dataset.id;

                viewHabit(habitID);

            }

            //deleting the habit when clicked 
            if (event.target.classList.contains("deleteHabitBtn")) {
                const habitID = event.target.dataset.id;
                console.log("Deleteing: ", habitID);
                deleteHabit(habitID);
            }



        });



        clearHabitCheckboxes.addEventListener("click", (event) => {
            clearHabitChecks();
        });

        //end of Habit stuff

        //Quest stuff start
        const questContainer = document.getElementById("questContainer");
        let currentQuestID = null;
        const singleQuestViewer = document.getElementById("singleQuestViewer");
        const questEditContainer = document.getElementById("questEditContainer");
        const viewAllQuestBtn = document.getElementById("viewAllQuests");

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
            const questDiv = event.target.closest(".singleQuest");
            if (!questDiv) return;
            const questID = questDiv.dataset.id;

            viewQuest(questID);
        });


        //deleting and editing the quest
        singleQuestViewer.addEventListener("click", async (event) => {
            if (event.target.classList.contains("deleteQuestBtn")) {
                const questID = event.target.dataset.id;
                //console.log(questID);
                deleteQuest(questID);
            }
        });


        //deleting and editing the quest
        singleQuestViewer.addEventListener("click", async (event) => {
            if (event.target.classList.contains("deleteQuestBtn")) {
                const questID = event.target.dataset.id;
                //console.log(questID);
                deleteQuest(questID);
            }


        });


        refreshTasks();
        refreshDailies();
        refreshInProgressQuests();
    </script>
</body>

</html>