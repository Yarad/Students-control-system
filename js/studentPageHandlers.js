/**
 * Created by user on 24.10.2017.
 */
currMonthOffset = 0;
//остальное добавляется php ... велосипед, блин. Надо бы переделать ч/з cookie

function onPrevMonthClick() {
    currMonthOffset--;
    ShowStudentTimetableQuery();
}
function onNextMonthClick() {
    currMonthOffset++;
    ShowStudentTimetableQuery();
}
//обработчики

function ShowStudentTimetableQuery() {
    $.post(
        "studentPageHandler.php",
        {
            task: "ShowTimetable",
            monthOffset: currMonthOffset, //смещение относительно текущего месяца. Надо будет как-то ограничить
            currGroupID: currGroupID
        },
        ShowStudentTimetableHandler
    );
}

function ShowStudentTimetableHandler(data) {
    doc = document.getElementById("leftContent");
    doc.innerHTML = data;
}
