<?php
include "databaseInfo.php";


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
    <div class="allQuestContainer"> <!--flex container to wrap the quests-->
        <div id="questContainer">
            <div id="questDetails"></div>
        </div>
    </div>
    <!-- what if I make this a popup as well?-->
    <div id="singleQuestViewer"> <!--Where a single quest displays on click  --></div>

    <!--Make this a pop up?-->

    <div id="questEditContainer" class="questEditContainer" style="display:none;"><!-- This is the entire popup to dsiplay --></div>



    <script>
        //basic User stuff (points)
        const pointsContainer = document.getElementById("pointsContainer");


        //Quest stuff start
        const questContainer = document.getElementById("questContainer");
        let currentQuestID = null;
        const singleQuestViewer = document.getElementById("singleQuestViewer");
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


            if (event.target.classList.contains("editQuestBtn")) {
                const questID = event.target.dataset.id;
                //console.log("questID= " + questID);
                const viewQuestDisplay = await fetch(`quest/editQuestDisplay.php?questID=${questID}`);
                questEditContainer.style.display = "block";
                const questHTML = await viewQuestDisplay.text();
                questEditContainer.innerHTML = questHTML;

                //editQuest(questID);
            }

            if (event.target.classList.contains("detailedEditQuest")) {
                console.log("Detail!");
                const questID = event.target.dataset.id;

                window.location.href =`quest/detailedQuestEdit.php?questID=${questID}`;
            }

        });


        //quest pop up page :p

        const editQuestPopup = document.getElementById("editQuestPopUp");
        const questEditContainer = document.getElementById("questEditContainer");

        //now while in here, if the user clicks create habit it will submit and create the habit while adding it to the quest
        questEditContainer.addEventListener("submit", async (event) => {
            console.log("submitted beech");
            if (event.target.id !== "createHabitForm") return;
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            //gathering form data
            try {
                const response = await fetch("quest/addHabittoQuest.php", {
                    method: "POST",
                    body: formData
                })

                const responseText = await response.text();
                console.log("RAW PHP: ", responseText);

                const result = JSON.parse(responseText);
                console.log("parsed PHP RESULT: ", result);

                if (result.success) {
                    console.log("habit created");
                    form.reset();
                    await refreshQuests();
                    questEditContainer.style.display = "none";


                } else {
                    console.error(result.message);
                }
            } catch (err) {
                console.error(err);
            }
        })


        questEditContainer.addEventListener("click", async (event) => {
            if (event.target.id === "createHabitsBtn") {
                //create the variables WITHIN the event listeners!! 
                const addHabitsDisplay = document.getElementById("addHabitID");
                const questInfo = document.getElementById("editQuestContID")
                const createHabitBtn = document.getElementById("createHabitsBtn");
                const addHabitCont = document.getElementById("addHabitID");


                addHabitsDisplay.style.display = "block";
                questInfo.style.display = "none";
                createHabitBtn.style.display = "none";




                /* trying something while I hold this
                addHabitCont.addEventListener("submit", async (event)=>{
                    try{
                        const response = await fetch (`quest/addHabittoQuest.php`, {
                            method: "POST",
                            body: JSON.stringify ({
                                questID : questID
                            })
                        });
                        const result = await response.json();

                        if(result.success){
                            console.log("did it");
                        }


                    } catch (err){
                        console.error(err);
                    }
                })
                    */

            }
        })

        refreshQuests();
    </script>
</body>

</html>