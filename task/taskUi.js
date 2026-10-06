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
                <p>${task.title}</p>
                </div>
                </div>
                `
    }).join("");
    taskContainer.innerHTML = taskHtml;

}

