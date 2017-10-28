/**
 * Created by user on 25.10.2017.
 */
function onPageLoad() {

    var inputItems = document.getElementsByTagName('input');
    for (var key in inputItems)
        if (inputItems.hasOwnProperty(key))
            inputItems[key].onclick = onInputClickHandler;
}

function onInputClickHandler(event) {
    if (event.target.value == "Логин" || event.target.value == "Пароль")
        event.target.value = "";
}