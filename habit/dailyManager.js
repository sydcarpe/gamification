async function loadDailies() {
    const response = await fetch("habit/getAllDailies.php");
    const data = await response.json();

    return data;
}


async function refreshDailies() {
    const data = await loadDailies();
    if (data.success) {
        renderDailies(data.dailies);
    }
}


//updating the checkbox
async function updateHabitStatus(event) {
    const habitCheckbox = event.target;
    const habitID = habitCheckbox.dataset.id; //habit id 
    const completed = habitCheckbox.checked ? 1 : 0;
    const pointsContainer = document.getElementById("pointsContainer");


    console.log("habit ID:", habitID);
    console.log("completed", completed);

    try {
        const response = await fetch("habit/updateHabit.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: habitID,
                completed: completed
            })
        });

        const result = await response.json();
        // console.log(result.longestStreak);
        //console.log("yesterday" , result.yesterday);
        //console.log("Last completed: " , result.lastCompletedDate);
        // console.log(result);

        if (result.success) {
            pointsContainer.innerHTML = result.totalPoints;
        }

    } catch (err) {
        console.error(err)
    }
}


//creating a task
/*
async function createHabit() {
    const formData = new FormData(createHabitForm);
    //const errorMessage = document.getElementById("tempMessage");

    try {
        const createResponse = await fetch("habit/createHabit.php", {
            method: "POST",
            body: formData
        });

        const data = await createResponse.json();
        console.log("Create response: ", data);

        if (!createResponse.ok || !data.success) {
            // errorMessage.textContent = data.error || "habit failed to be created...";
            console.log(data.error);
            return;
        }

        //errorMessage.textContent = "created";
        createHabitForm.reset();
        if (!window.location.pathname.endsWith('questHomePage.php')) {
            await refreshDailies();
        }

        habitCreationDisplay.style.display = "none";
        document.getElementById("viewCreateHabitBtn").style.display = "block";
    } catch (err) {
        console.error(err);
    }
}
    */

//update createHabit()
async function createHabit(formData){
    const createResponse = await fetch("habit/createHabit.php", {
        method: "POST", 
        body: formData
    });

    return await createResponse.json()
}

//viewing the habit
async function viewHabit(habitID) {
    console.log(habitID);

    try {
        const response = await fetch("habit/viewHabit.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                habitID: habitID
            })
        });

        const data = await response.json();

        if (data.success) {
            //if currentHabit is the same habit empty! 
            if (currentHabitID === habitID) {
                singleHabitView.innerHTML = "";
                currentHabitID = null;
                return;
            }

            if (currentHabitID == null) {
                //displaying still!
                const habit = data.habit;

                //display the habits here! 
                singleHabitView.innerHTML = `
                <h2>${habit.title}</h2>
                <p>${habit.description}</p>
                <p>longest Streak: ${habit.longestStreak}</p>
                <p>current Streak: ${habit.currentStreak}</p>
                <p>last completed date: ${habit.lastCompletedDate}</p>
                <button class="deleteHabitBtn" data-id="${habit.ID}"> Delete</button>
                `;

                currentHabitID = habitID;
            }
            if (currentHabitID !== habitID) {
                const habit = data.habit;

                //display the habits here! 
                singleHabitView.innerHTML = `
                <h2>${habit.title}</h2>
                <p>${habit.description}</p>
                <p>longest Streak: ${habit.longestStreak}</p>
                <p>current Streak: ${habit.currentStreak}</p>
                <p>last completed date: ${habit.lastCompletedDate}</p>
                <button class="deleteHabitBtn" data-id="${habit.ID}"> Delete</button>
                `;

                currentHabitID = habitID;
            }


        }

    } catch (err) {
        console.error(err);
    }
}

//deleting habit TO DO  
async function deleteHabit(habitID) {
    console.log(habitID);
    try {
        const response = await fetch("habit/deleteHabit.php", {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                habitID: habitID
            })
        });
        await refreshDailies();

        singleHabitView.innerHTML = "";
    } catch (err) {
        console.error(err);
    }
}


async function clearHabitChecks() {
    try {
        const response = await fetch("habit/clearHabitChecks.php", {
            method: "POST"
        });

        const result = await response.json();
        if (result.success) {
            refreshDailies();
        } else {
            console.log(result.error)
        }
    } catch (err) {
        console.error(err);
    }
}


function createHabitPopup() {
    habitCreationDisplay.style.display = "block";
    document.getElementById("viewCreateHabitBtn").style.display = "none";
}