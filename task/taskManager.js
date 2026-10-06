async function loadTasks() {
    const response = await fetch("task/getAllTasksAjax.php");
    const data = await response.json();

    return data;
}

async function updateTaskStatus(event) {
    const checkbox = event.target;
    const taskID = checkbox.dataset.id; // task ID 
    const completed = checkbox.checked ? 1 : 0;

    //console.log("Task ID:", taskID);
    //console.log("Completed:", completed);


    try {
        const response = await fetch("task/updateTaskAjax.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: taskID,
                completed: completed
            })
        });

        const result = await response.json();
        //console.log(result);

        if (result.success) {
            pointsContainer.innerHTML = result.totalPoints;
        }

    } catch (err) {
        console.error(err);
    }

}


async function refreshTasks() {
    const data = await loadTasks();
    if (data.success) {
        renderTasks(data.tasks);
    }
}

//viewing the task on click
async function viewTask(taskID) {
    //console.log("Viewing Task: ", taskID);
    //console.log("CurrentTaskID: ", currentTaskID);

    try {
        const response = await fetch("task/viewTaskAjax.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                taskID: taskID
            })
        });


        const data = await response.json();

        if (data.success) {

            if (currentTaskID === taskID) {
                document.getElementById("taskDetails").innerHTML = "";
                currentTaskID = null;
                return;
            }

            if (currentTaskID == null) {
                const task = data.task;

                //put all display task stuff here!! 
                document.getElementById("taskDetails").innerHTML = `
                <h2>${task.title}</h2>
                <p>${task.description}</p>
                <button class="deleteBtn" data-id="${task.ID}">Delete </button>`;

                currentTaskID = taskID;
            }
            if (currentTaskID !== taskID) {
                const task = data.task;

                //put all display task stuff here!! 
                document.getElementById("taskDetails").innerHTML = `
                    <h2>${task.title}</h2>
                    <p>${task.description}</p>
                    <button class="deleteBtn" data-id="${task.ID}">Delete </button>`;

                currentTaskID = taskID;
            }

        }

    } catch (err) {
        console.error(err);
    }

}


async function deleteTask(taskID) {
    //console.log(taskID);

    try {
        const response = await fetch("task/deleteTaskAjax.php", {
            method: "POST",
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                taskID: taskID
            })

        });

        await refreshTasks();

        singleTaskView.innerHTML = "";
    } catch (err) {
        console.error(err);
    }
}

//creating the task after btn click 
async function createTask() {
    const formData = new FormData(createTaskForm);
    const errorMessage = document.getElementById("tempMessage");

    try {
        const createResponse = await fetch("task/createTask.php", {
            method: "POST",
            body: formData
        });

        //if failing show this... 
        const data = await createResponse.json();
        console.log("Create Response:", data);
        //const raw = await createResponse.text();
        //console.log("RAW RESPONSE:", raw);
        //errorMessage.textContent = raw;

        if (!createResponse.ok || !data.success) {
            errorMessage.textContent = data.error || "The task could not be created";
            return;
        }

        errorMessage.textContent = "Task Created";
        createTaskForm.reset();
        await refreshTasks();

        taskCreationDisplay.style.display = "none";
        document.getElementById("createbtn").style.display = "block";
    } catch (err) {
        console.error(err);
    }
}

//deleting all completed tasks from list 
async function deleteCompletedTasks() {
    try {
        const response = await fetch("task/deleteCompletedTasks.php", {
            method: "POST",
        });

        const result = await response.json();

        if (result.success) {
            refreshTasks();
        } else {
            console.log(result.error);
        }

    } catch (err) {
        console.error(err);
    }
}



function createTaskPopUp() {
    taskCreationDisplay.style.display = "block";
    document.getElementById("createbtn").style.display = "none";
}