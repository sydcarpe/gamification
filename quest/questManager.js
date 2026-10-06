async function loadQuests() {
    const response = await fetch("quest/getAllQuests.php");
    const data = response.json();
    return data;
}


async function refreshQuests() {
    const data = await loadQuests();
    //console.log(data);
    if (data.success) {
        renderQuests(data.quests)
    }
}

async function refreshInProgressQuests() {
    const data = await loadQuests();
    if (data.success) {
        const inProgressQuests = data.quests.filter(quest => quest.status === "In Progress");

        renderInProgressQuests(inProgressQuests);
    }
}

//viewing quest on click! 
async function viewQuest(questID) {
    if (window.location.pathname.endsWith('questHomePage.php')) {
        questEditContainer.style.display ="none";
    }

    //console.log("QuestID: ", questID);
    var editBtn = "";

    try {
        const response = await fetch("quest/viewQuest.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                questID: questID
            })
        });

        const data = await response.json();

        if (data.success) {
            const quest = data.quest;


            if (currentQuestID === questID) {
                singleQuestViewer.innerHTML = "";
                if (questEditContainer === null) {
                    //do nothing
                } else if (questEditContainer != null) {
                    questEditContainer.innerHTML = "";
                }
                return
            }

            // if user is on the questHomePage it will show the edit btn 
            if (window.location.pathname.endsWith('questHomePage.php')) {
                editBtn = `<button class="editQuestBtn" data-id='${quest.id}'> Quick Edit </button>`;
                detailedEdit = `<button class="detailedEditQuest" data-id='${quest.id}'> Detailed Edit </button>`; //TODO !
            }

            // <button class="editQuestBtn" data-id="${quest.id}"> Edit </button> 
            if (currentQuestID == null) {
                singleQuestViewer.innerHTML = `
                <h2>${quest.title}</h2>
                <p>${quest.description}</p>
                <p>${quest.status}</p>
                <button class="deleteQuestBtn" data-id="${quest.id}"> Delete </button>
                ` + editBtn + detailedEdit;

                currentQuestID = questID;
            }

            // <button class="editQuestBtn" data-id="${quest.ID}"> Edit </button>
            if (currentQuestID !== questID) {

                singleQuestViewer.innerHTML = `
                <h2>${quest.title}</h2>
                <p>${quest.description}</p>
                <button class="deleteQuestBtn" data-id="${quest.ID}"> Delete </button>
                ` + editBtn + detailedEdit;

                if (questEditContainer != null) {
                    questEditContainer.innerHTML = "";

                }

                currentQuestID = questID;
            }

        }
    } catch (err) {
        console.error(err);
    }
}


//updating the status of quests - this will not automatically refresh the page because I am not calling it 
async function updateQuestStatus(questID, newStatus) {
    //console.log("questID", questID);
    //console.log("new status", newStatus);

    try {
        const response = await fetch("quest/UpdateQuestStatus.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: questID,
                newStatus: newStatus
            })

        });

        const result = await response.json();

        if (result.success) {
            pointsContainer.innerHTML = result.totalPoints;
            //console.log("Stinky stink");
            console.log(result);
        }

    } catch (err) {
        console.error(err);
    }

}

//editing the quest TODO - I am having trouble atm
async function editQuest(questID) {
    console.log("editing: ", questID);
    try {
        const response = await fetch("quest/editQuest.php", {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                questID: questID
            })
        });
    } catch (err) {
        console.error(err);
    }
}

//deleting the Quest-- to finish 
async function deleteQuest(questID) {
    console.log("deleting: ", questID);

    try {
        const response = await fetch("quest/deleteQuest.php", {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                questID: questID
            })
        });
        await refreshQuests();
        await refreshTasks();
        //await refreshHabits();

        singleQuestViewer.innerHTML = "";
    } catch (err) {
        console.error(err);
    }

}