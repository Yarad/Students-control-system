/**
 * Created by user on 16.10.2017.
 */

function onPageLoad()
{
    ShowGroupsQuery();
    //обработчик внутри
}

function onChooseList(groupID)
{

}

function onShowGroups()
{

}

function ShowGroupsQuery()
{
    $.post(
        "teacherPageHandler.php",
        {
            param1: "param1",
            param2: 2
        },
        ShowGroupsHandler
    );
}

function ShowGroupsHandler(data)
{
    doc = document.getElementById("rightContent");
    doc.innerHTML = data;
}