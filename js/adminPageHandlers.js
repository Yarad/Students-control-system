/**
 * Created by user on 23.11.2017.
 */

var currTeacherId = '';

function onAdminPageLoad() {
    //ShowTeachersQuery();
    //обработчик внутри
    //ShowStudentsQuery('group1');
}

function ShowTeachersQuery() {
    $.post(
        "adminPageHandler.php",
        {
            task: "ShowTeachers"
        },
        ShowTeachersHandler
    );
}

function ShowTeachersHandler(data) {
    $("#rightContent-inner1").html(data);
}

function onTeacherRecordClick(teacherId) {
    if (event.target != event.currentTarget) return;
    ChooseTeacher(teacherId);
}

function onDeleteTeacherClick() {

}

function ChooseTeacher(teacherId) {
    //currTeacherId = teacherId;
    Cookies.set("teacherUnderAdmin",teacherId,{ path: '/'});
    ShowGroupsByTeacherIdQuery(teacherId);
}

function ShowGroupsByTeacherIdQuery(teacherId) {
    $.post(
        "teacherPageHandler.php",
        {
            task: "ShowGroups",
            teacherId: teacherId
        },
        ShowGroupsHandler
    );
}
