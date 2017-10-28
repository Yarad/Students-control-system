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

function onCommonHomeworkButtonClick() {
    currStudentID = "ALL_STUDENTS";
    GiveCommonHomeworkQuery();
}

function onStudentEditTimetableClick(studentLogin) {
    currStudentID = studentLogin;
    ShowStudentTimetableQuery();
}

function onSaveStudentNotesClick() {
    currMonthNotes = $(".note-edit");
    currMonthMarks = $(".mark-edit");

    currMonthNotesAndMarksArr = {};

    for (i = 0; i < currMonthNotes.length; i++) {
        if (currMonthNotes[i].value != '' || currMonthMarks[i].value != '')
            currMonthNotesAndMarksArr[currMonthNotes[i].id] = [currMonthNotes[i].value, currMonthMarks[i].value];
    }

    SaveNotesAndMarksQuery(currMonthNotesAndMarksArr, currMonthOffset)
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

function SaveNotesAndMarksQuery(currMonthNotesAndMarksArr, monthOffset) {
    $.post(
        "teacherPageHandler.php",
        {
            task: "SaveNotesAndMarks",
            notesAndMarks: JSON.stringify(currMonthNotesAndMarksArr),
            studentID: currStudentID,
            currGroupID: currGroupID,
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

function GiveCommonHomeworkQuery() {
    $.post(
        "teacherPageHandler.php",
        {
            task: "ShowCommonTimetable",
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

function ClearLeftContent() {
    doc = document.getElementById("leftContent");
    doc.innerHTML = "";
}