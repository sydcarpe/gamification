function renderDailies(dailies) {

    if (!dailies.length) {
        dailyTodo.innerHTML = "<p> NONE LOSER</p>";
        return;
    }

    const dailiesHTML = dailies.map(habit => {
        return `
                <div class="habitRow">
                <div class="singleHabit" id = "singleHabit"data-id="${habit.ID}">
                <input class="habit-checkbox" type="checkbox" data-id="${habit.ID}" ${habit.completed == 1 ? "checked" : ""}>
                <p class="individualHab" data-id="${habit.ID}">${habit.title}</p>
                <button class="deleteHabitBtn deleteButton" data-id="${habit.ID}"> X </button>
                </div>
                </div>
                `
    }).join("");
    dailyTodo.innerHTML = dailiesHTML;

}
