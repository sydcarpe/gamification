function renderDailies(dailies) {

    if (!dailies.length) {
        dailyTodo.innerHTML = "<p> NONE LOSER</p>";
        return;
    }

    const dailiesHTML = dailies.map(habit => {
        return `
                <div class="habitRow">
                <input class="habit-checkbox" type="checkbox" data-id="${habit.ID}" ${habit.completed == 1 ? "checked" : ""}>
                <div class="singleHabit" data-id="${habit.ID}">
                <p>${habit.title}</p>
                <button class="deleteHabitBtn" data-id="${habit.ID}"> Delete</button>
                </div>
                </div>
                `
    }).join("");
    dailyTodo.innerHTML = dailiesHTML;

}

