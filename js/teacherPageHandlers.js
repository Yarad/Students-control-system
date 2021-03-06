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

function onAddStudentInGroupButtonClick() {
    $("#edit-user-info-form").css('display', 'inline-block');

    $("#fieldLogin").val("Логин");
    $("#fieldPassword").val("Пароль");
    $("#fieldSurname").val("Фамилия");
    $("#fieldName").val("Имя");

    var btn = document.getElementById('confirmFormButton');
    btn.onclick = onConfirmFormButtonClick;
}

function onConfirmFormButtonClick() {
    var login = document.getElementById("fieldLogin").value;
    var password = document.getElementById("fieldPassword").value;
    var surname = document.getElementById("fieldSurname").value;
    var name = document.getElementById("fieldName").value;

    var doc = document.getElementById("edit-user-info-form");
    doc.style.display = "none";

    AddStudentQuery(login, password, surname, name);
}

function onCancelFormButtonClick() {
    var doc = document.getElementById("edit-user-info-form");
    doc.style.display = "none";

    AddStudentQuery(login, password, surname, name);
}


function onListButtonClick(groupID) {
    currGroupID = groupID;
    ShowStudentsQuery(groupID);
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

function onDeleteStudClick(studLogin) {
    DeleteStudentQuery(studLogin);
}

function onSaveStudentNotesClick() {
    currMonthNotes = $(".note-edit");
    currMonthMarks = $(".mark-edit");

    currMonthNotesAndMarksArr = {};

    for (i = 0; i < currMonthNotes.length; i++) {
        if (currMonthNotes[i].value != '' || currMonthMarks[i].value != '')
            currMonthNotesAndMarksArr[currMonthNotes[i].id] = [currMonthNotes[i].value, currMonthMarks[i].value];
    }
    SaveNotesAndMarksQuery(currMonthNotesAndMarksArr, currMonthOffset);
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

function DeleteStudentQuery(studLogin) {
    $.post(
        "teacherPageHandler.php",
        {
            task: "DeleteStudent",
            groupID: currGroupID,
            nick: studLogin
        },
        DeleteStudentHandler
    );
}

function AddStudentQuery(login, password, surname, name) {
    $.post(
        "teacherPageHandler.php",
        {
            task: "AddStudent",
            groupID: currGroupID,
            login: login,
            password: password,
            surname: surname,
            name: name
        },
        StudentWasAddedHandler
    );
}

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


function DeleteStudentHandler(data) {
    if (data == "OK") {
        ShowStudentsQuery(currGroupID);
        alert("Студент успешно удалён из списка");
    }
    else {
        alert("Ошибка удаления.");
    }
}

function StudentWasAddedHandler(data) {
    if (data == 'DB_ERROR')
        alert('Ошибка сохранения в БД. Возможно, такой пользователь уже существует.');
    else if (data == 'INPUT_ERROR') {
        alert('Неверно введены данные. В логине и пароле допустимы только латиница и цифры');
    }
    else
        document.getElementById('rightContent-inner2').innerHTML = data;
}

function SaveNotesAndMarksHandler(data) {

    //doc = document.getElementById('leftContent');
    //doc.innerHTML = data;
    if (data == "OK")
        alert("Пометки сохранены");
    else
        alert("Ошибка сохранения в БД");
}

function ShowGroupsHandler(data) {
    doc = document.getElementById("rightContent-inner1");
    doc.innerHTML = data;
    ClearLeftContent();
}

function ShowStudentTimetableHandler(data) {
    doc = document.getElementById("leftContent");
    doc.innerHTML = data;
}

function ShowStudentsHandler(data) {
    //сдвиг блока... помещение data в блок
    doc = document.getElementById("rightContent-inner2");
    doc.innerHTML = data;
	ChangeViewOfRightMenuBlocks();
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