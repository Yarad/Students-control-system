/**
 * Created by user on 24.10.2017.
 */
/**
 * Created by user on 16.10.2017.
 */
currGroupID = '';
currMonthOffset = 0;
currStudentID = '';

function onPrevMonthClick() {
    currMonthOffset--;
    ShowStudentTimetableQuery();
}
function onNextMonthClick() {
    currMonthOffset++;
    ShowStudentTimetableQuery();
}

function ShowStudentTimetableQuery() {
    $.post(
        "teacherPageHandler.php",
        {
            task: "ShowTimetable",
            studentID: currStudentID,
            monthOffset: currMonthOffset, //смещение относительно текущего месяца. Надо будет как-то ограничить
            currGroupID: currGroupID
        },
        ShowStudentTimetableHandler
    );
}
