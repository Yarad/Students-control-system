/**
 * Created by user on 16.10.2017.
 */
currGroupID='';

function onPageLoad() {
    ShowGroupsQuery();
    //обработчик внутри
    //ShowStudentsQuery('group1');
}

function onListButtonClick(groupID) {
    currGroupId = groupID;
    ShowStudentsQuery(groupID);
    ChangeViewOfRightMenuBlocks();
}

function onBackToGroupsButtonClick()
{
    currGroupID = '';
    ShowGroupsQuery();
    ChangeViewOfRightMenuBlocks();
}

function onStudentEditTimetableClick($studentLogin){
    ShowStudentTimetableQuery($studentLogin);
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

//Ajax Handlers

function ShowGroupsHandler(data) {
    doc = document.getElementById("rightContent-inner1");
    doc.innerHTML =  data;
}

function ShowStudentTimetableQuery(studentLogin) {
    $.post(
        "teacherPageHandler.php",
        {
            task: "ShowTimetable",
            studentID: studentLogin,
            monthOffset: 0, //смещение относительно текущего месяца. Надо будет как-то ограничить
            currGroupID: currGroupID
        },
        ShowStudentTimetableHandler
    );
}

function ShowStudentTimetableHandler(data) {
    doc = document.getElementById("leftContent");
    doc.innerHTML =  data;
}

function ShowStudentsHandler(data) {
    //сдвиг блока... помещение data в блок
    doc = document.getElementById("rightContent-inner2");
    doc.innerHTML = data;
}

function ChangeViewOfRightMenuBlocks()
{
    doc1 = document.getElementById("rightContent-inner1");
    doc2 = document.getElementById("rightContent-inner2");
    if(doc1.style.display=="none"){
        doc2.style.display="none";
        doc1.style.display="block";
    }
    else
    {
        doc1.style.display="none";
        doc2.style.display="block";
    }
}