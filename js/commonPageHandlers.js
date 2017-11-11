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

/*function onPageLoadCommon() {

    var fieldItems = document.getElementsByClassName('field');
    for (var key in fieldItems)
        if (fieldItems.hasOwnProperty(key))
            fieldItems[key].onclick = onFieldClickHandler;
}*/

function onFieldClickHandler(element) {
	
    if (element.value == "Логин" || element.value == "Пароль" || element.value == "Фамилия"|| element.value == "Имя" )
        element.value = "";
}

function LogoutHandler(data) {
    document.location.href = "index.php";
    var doc = document.getElementById("rightContent");
    doc.innerHTML = data;
}