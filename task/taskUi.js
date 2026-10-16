function renderTasks(tasks) {

    if (!tasks.length) {
        taskContainer.innerHTML = "<p> NONE LOSER</p>";
        return;
    }

    const taskHtml = tasks.map(task => {
        return `
                <div class="taskRow">
                    <input class="task-checkbox" type="checkbox" data-id="${task.ID}" ${task.completed == 1 ? "checked" : ""}>
                    <div class="singleTask" data-id="${task.ID}">
                        <p class="indivdualTask">${task.title}</p>
                    </div>
                    <button class="deleteTaskBtn deleteButton" data-id="${task.ID}"> X </button>
                </div>
                `
    }).join("");
    taskContainer.innerHTML = taskHtml;

}

