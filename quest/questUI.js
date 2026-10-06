function renderInProgressQuests(quests) {

    if (!quests.length) {
        questContainer.innerHTML = "empty";
        return;
    }

    const questHTML = quests.map(quest => {
        const taskHTML = renderQuestTasks(quest.tasks);
        const habitHTML = renderQuestHabits(quest.habits);
        const questStatus = "Not Started";

        if (quest.status == "Not Started") {
            return `
            <div class = "singleQuest" data-id= "${quest.id}">
            <h1 id='questSingleTitle'>${quest.title}</h1>
            <select value="${quest.status}" onchange ="updateQuestStatus( ${quest.id},this.value)">
            <option value = "Not Started" selected> Not Started </option>
            <option value = "In Progress"> In Progress </option>
            <option value = "Complete"> Complete </option>

            </select>
            <h3>Tasks</h3>
            <div class="questTasksCont">
            ${taskHTML}
            </div>

            <h3>Habits</h3>
            <div class="questHabitsCont">
            ${habitHTML}
            </div>
        `;
        } else if (quest.status == "In Progress") {
            return `
            <div class = "singleQuest" data-id= "${quest.id}">
            <h1 id='questSingleTitle'>${quest.title}</h1>
            <select value="${quest.status}" onchange ="updateQuestStatus( ${quest.id},this.value)">
            <option value = "Not Started"> Not Started </option>
            <option value = "In Progress" selected> In Progress </option>
            <option value = "Complete"> Complete </option>

            </select>
            <h3>Tasks</h3>
            <div class="questTasksCont">
            ${taskHTML}
            </div>

            <h3>Habits</h3>
            <div class="questHabitsCont">
            ${habitHTML}
            </div>
        `;
        } else if (quest.status == "Complete") { //completed one. May need to show this in a different field or something
            return `
            <div class = "singleQuest" data-id= "${quest.id}">
            <h1 id='questSingleTitle'>${quest.title}</h1>
            <select value="${quest.status}" onchange ="updateQuestStatus( ${quest.id},this.value)">
            <option value = "Not Started"> Not Started </option>
            <option value = "In Progress"> In Progress </option>
            <option value = "Complete" selected> Complete </option>

            </select>
            <h3>Tasks</h3>
            <div class="questTasksCont">
            ${taskHTML}
            </div>

            <h3>Habits</h3>
            <div class="questHabitsCont">
            <div id='dailyTodo'></div>
            ${habitHTML}
            </div>
        `;
        }


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

        if (quest.status == "Not Started") {
            return `<hr>
            <div class = "singleQuest" data-id= "${quest.id}">
            <h1>${quest.title}</h1>
            <select value="${quest.status}" onchange ="updateQuestStatus( ${quest.id},this.value)">
            <option value = "Not Started" selected> Not Started </option>
            <option value = "In Progress"> In Progress </option>
            <option value = "Complete"> Complete </option>

            </select>
            <h3>Tasks</h3>
            <div class="questTasksCont">
            ${taskHTML}
            </div>

            <h3>Habits</h3>
            <div class="questHabitsCont">
            ${habitHTML}
            </div>
        `;
        } else if (quest.status == "In Progress") {
            return `<hr>
            <div class = "singleQuest" data-id= "${quest.id}">
            <h1>${quest.title}</h1>
            <select value="${quest.status}" onchange ="updateQuestStatus( ${quest.id},this.value)">
            <option value = "Not Started"> Not Started </option>
            <option value = "In Progress" selected> In Progress </option>
            <option value = "Complete"> Complete </option>

            </select>
            <h3>Tasks</h3>
            <div class="questTasksCont">
            ${taskHTML}
            </div>

            <h3>Habits</h3>
            <div class="questHabitsCont">
            ${habitHTML}
            </div>
        `;
        } else if (quest.status == "Complete") { //completed one. May need to show this in a different field or something
            return `<hr>
            <div class = "singleQuest" data-id= "${quest.id}">
            <h1>${quest.title}</h1>
            <select value="${quest.status}" onchange ="updateQuestStatus( ${quest.id},this.value)">
            <option value = "Not Started"> Not Started </option>
            <option value = "In Progress"> In Progress </option>
            <option value = "Complete" selected> Complete </option>

            </select>
            <h3>Tasks</h3>
            <div class="questTasksCont">
            ${taskHTML}
            </div>

            <h3>Habits</h3>
            <div class="questHabitsCont">
            ${habitHTML}
            </div>
        `;
        }


    }).join("");
    questContainer.innerHTML = questHTML;
}