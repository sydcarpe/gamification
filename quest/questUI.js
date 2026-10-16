function renderInProgressQuests(quests) {

    if (!quests.length) {
        questContainer.innerHTML = "empty";
        return;
    }

    const questHTML = quests.map(quest => {
        const taskHTML = renderQuestTasks(quest.tasks);
        const habitHTML = renderQuestHabits(quest.habits);
        const questStatus = "Not Started";

        return `
            <div class="questCard" data-id = "${quest.id}">
                <div class="singleQuest" data-id = "${quest.id}">
                    <h1 class='questSingleTitle'>${quest.title}</h1>

                    <select class="questStatus" onchange ="updateQuestStatus( ${quest.id},this.value)">
                    <option value = "Not Started" 
                        ${quest.status === "Not Started" ? "selected" : ""} > Not Started </option>
                    <option value = "In Progress" ${quest.status === "In Progress" ? "selected" : ""}> In Progress </option>
                    <option value = "Complete" ${quest.status === "Complete" ? "selected" : ""}> Complete </option>
                    </select>
                </div>

                <div class="questContent">
                    <div class="questTask">
                        <h3>Tasks</h3>

                        <div class="questTasksCont">
                            ${taskHTML}
                        </div>
                    </div>

                    <div class="questHabits">
                        <h3>Habits</h3>

                        <div class="questHabitsCont">
                            ${habitHTML}
                        </div>
                    </div>
                </div>
            </div>
        `;

    }).join("");
    questContainer.innerHTML = questHTML;
}

//getting all the tasks per quest
function renderQuestTasks(tasks) {
    if (!tasks.length) {
        return "No tasks!";
    }

    return tasks.map(task => {
        return `<div class="taskRow">
                    <input class="task-checkbox" type="checkbox" data-id="${task.ID}" ${task.completed == 1 ? "checked" : ""}>
                    <div class="singleTask" data-id="${task.ID}">
                        <p>${task.title}</p>
                    </div>
                    <button class="deleteTaskBtn deleteButton" data-id="${task.ID}"> X </button>
                </div>
            `
    }).join("");
}

function renderQuestHabits(habits) {
    if (!habits.length) {
        return "no habits";
    }

    return habits.map(habit => {
        return `
            <div class="habitRow">
                <input class="habit-checkbox" type="checkbox" data-id="${habit.ID}" ${habit.completed == 1 ? "checked" : ""}>
                <div class="singleHabit" data-id="${habit.ID}">
                    <p>${habit.title}</p>
                    <button class="deleteHabitBtn deleteButton" data-id="${habit.ID}"> X </button>
                </div>
            </div>
        `
    }).join("");
}

function renderQuests(quests) {

    if (!quests.length) {
        questContainer.innerHTML = "No quests... Create";
        return;
    }

    const questHTML = quests.map(quest => {
        const taskHTML = renderQuestTasks(quest.tasks);
        const habitHTML = renderQuestHabits(quest.habits);

        return `
        <div class="questCard" data-id = "${quest.id}">
                <div class="singleQuest" data-id = "${quest.id}">
                    <h1 class='questSingleTitle'>${quest.title}</h1>

                    <select class="questStatus" onchange ="updateQuestStatus( ${quest.id},this.value)">
                    <option value = "Not Started" 
                        ${quest.status === "Not Started" ? "selected" : ""} > Not Started </option>
                    <option value = "In Progress" ${quest.status === "In Progress" ? "selected" : ""}> In Progress </option>
                    <option value = "Complete" ${quest.status === "Complete" ? "selected" : ""}> Complete </option>
                    </select>
                </div>

                <div class="questContent">
                    <div class="questTask">
                        <h3>Tasks</h3>

                        <div class="questTasksCont">
                            ${taskHTML}
                        </div>
                    </div>

                    <div class="questHabits">
                        <h3>Habits</h3>

                        <div class="questHabitsCont">
                            ${habitHTML}
                        </div>
                    </div>
                </div>
            </div>
        `;

    }).join("");
    questContainer.innerHTML = questHTML;
}