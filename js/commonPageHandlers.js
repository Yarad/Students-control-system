/**
 * Created by user on 28.10.2017.
 */
function ProvideLogout() {
    LogoutQuery();
}

function LogoutQuery() {
    $.post(
        "commonPageHandler.php",
        {
            task: "logout"
        },
        LogoutHandler
    );
}

function OpenSettings() {
    $("#settingsWindow").find('input').each(function(indx, element){  element.value = '';});
    $("#settingsWindow").css('display',"inline-block");
}
function SaveSettings() {
    var oldPassword = document.getElementById('oldPassword').value;
    var newPassword1 = document.getElementById('newPassword').value;
    var newPassword2 = document.getElementById('newPassword2').value;

    if (newPassword1 == newPassword2)
        SaveSettingsQuery(oldPassword,newPassword1);
    else
        alert("Неверно введены данные");
}

/*function onPageLoadCommon() {

 var fieldItems = document.getElementsByClassName('field');
 for (var key in fieldItems)
 if (fieldItems.hasOwnProperty(key))
 fieldItems[key].onclick = onFieldClickHandler;
 }*/

function SaveSettingsQuery(oldPassword, newPassword) {
    $.post(
        "commonPageHandler.php",
        {
            task: "SaveSettings",
            oldPassword: oldPassword,
            newPassword:newPassword
        },
        SaveSettingsHandler
    );
}

function SaveSettingsHandler(data) {
    if (data == "OK")
        alert('Успешно сохранено');
    else {
        alert('Ошибка');
        document.getElementById('leftContent').innerHTML = data;
    }

    CancelSettings();
}

function CancelSettings() {
    var doc = document.getElementById('settingsWindow');
    doc.style.display = 'none';
}

function onFieldClickHandler(element) {

    if (element.value == "Логин" || element.value == "Пароль" || element.value == "Фамилия" || element.value == "Имя" || element.value == "Новый пароль" || element.value == "Старый пароль")
        element.value = "";
}

function LogoutHandler(data) {
    document.location.href = "index.php";
    var doc = document.getElementById("rightContent");
    doc.innerHTML = data;
}