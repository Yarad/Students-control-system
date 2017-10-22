/**
 * Created by user on 16.10.2017.
 */
currGroupID = '';
currMonthOffset = 0;
currStudentID = '';


function onPageLoad() {
    ShowGroupsQuery();
    //обработчик внутри
    //ShowStudentsQuery('group1');
}

function onListButtonClick(groupID) {
    currGroupID = groupID;
    ShowStudentsQuery(groupID);
    ChangeViewOfRightMenuBlocks();
}

function onBackToGroupsButtonClick() {
    currGroupID = '';
    ShowGroupsQuery();
    ChangeViewOfRightMenuBlocks();
}

function onStudentEditTimetableClick(studentLogin) {
    currStudentID = studentLogin;
    ShowStudentTimetableQuery();
}

function onSaveStudentNotesClick() {
    currMonthNotes = $(".note-edit");
    currMonthMarks = $(".mark-edit");

    currMonthNotesArr = {};
    currMonthMarksArr = {};

    for (i = 0; i < currMonthNotes.length; i++)
        currMonthNotesArr[currMonthNotes[i].id] = currMonthNotes[i].value;

    for (i = 0; i < currMonthMarks.length; i++)
        currMonthMarksArr[currMonthMarks[i].id] = currMonthMarks[i].value;

    SaveNotesAndMarksQuery(currMonthNotesArr, currMonthMarksArr, currMonthOffset)
}

function onPrevMonthClick() {
    currMonthOffset--;
    ShowStudentTimetableQuery();
}
function onNextMonthClick() {
    currMonthOffset++;
    ShowStudentTimetableQuery();
}
//обработчики

function ShowStudentsQuery(groupID) {
    $.post(
        "teacherPageHandler.php",
        {
            task: "ShowStudents",
            groupID: groupID
        },
        ShowStudentsHandler
    );
}

function ShowGroupsQuery() {
    $.post(
        "teacherPageHandler.php",
        {
            task: "ShowGroups"
        },
        ShowGroupsHandler
    );
}

function SaveNotesAndMarksQuery(notesArr, marksArr, monthOffset) {
    $.post(
        "teacherPageHandler.php",
        {
            task: "SaveNotesAndMarks",
            notes: JSON.stringify(notesArr),
            marks: JSON.stringify(marksArr),
            monthOffset: monthOffset
        },
        SaveNotesAndMarksHandler
    );
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

//Ajax Handlers

function SaveNotesAndMarksHandler(data) {
    doc = document.getElementById('leftContent');
    doc.innerHTML = data;
}

function ShowGroupsHandler(data) {
    doc = document.getElementById("rightContent-inner1");
    doc.innerHTML = data;
}


function ShowStudentTimetableHandler(data) {
    doc = document.getElementById("leftContent");
    doc.innerHTML = data;
}

function ShowStudentsHandler(data) {
    //сдвиг блока... помещение data в блок
    doc = document.getElementById("rightContent-inner2");
    doc.innerHTML = data;
}

function ChangeViewOfRightMenuBlocks() {
    doc1 = document.getElementById("rightContent-inner1");
    doc2 = document.getElementById("rightContent-inner2");
    if (doc1.style.display == "none") {
        doc2.style.display = "none";
        doc1.style.display = "block";
    }
    else {
        doc1.style.display = "none";
        doc2.style.display = "block";
    }
}
