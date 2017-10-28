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

function LogoutHandler(data) {
    document.location.href = "index.php";
    var doc = document.getElementById("rightContent");
    doc.innerHTML = data;
}