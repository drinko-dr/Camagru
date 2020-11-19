document.addEventListener('DOMContentLoaded', function(){
    /**
     * Глобальные id переменных
     * @type {HTMLElement}
     */
    var singIn = checkLogin();
    var closePopUpBtn = document.getElementById("close-popUp");
    var popUpContainer = document.getElementById("pop-up");
    var overlay = document.getElementById("overlay");

    /**
     * Функция проверяет залогинен ли пользователь, если да, вешает обработчик открытия меню
     * Функция привязана на область имении пользователя для открытия, для закрытия используется тег <a> который открыт на всю страницу
     * @returns {HTMLElement|null}
     */
    function checkLogin() {
        var idElement = document.getElementById("login");
        var firstChildElem = idElement.children[0];
        if (firstChildElem.classList.contains("my-account")){
            firstChildElem.addEventListener("click", function(e){
                var profMenu = document.getElementById("profile-menu");
                var overlayMenu = document.getElementById("overlayMenu");
                if (!profMenu.classList.contains("show-profile-menu")){
                    profMenu.classList.add("show-profile-menu");
                    overlayMenu.style.display = "block";
                    overlayMenu.addEventListener("click", function f(e) {
                        profMenu.classList.remove("show-profile-menu");
                        overlayMenu.style.display = "none";
                        overlayMenu.removeEventListener("click", f);
                    });
                } else{
                    profMenu.classList.remove("show-profile-menu");
                    overlayMenu.style.display = "none";
                    // overlayMenu.removeEventListener("click", f);
                }
            }, false);
            return null;
        }else{
            return document.getElementById("sing-in");
        }
    }

    /**
     * ПопАп форма для входа в аккаунт
     */
    function showSingIn(){
        popUpContainer.insertAdjacentHTML('beforeend', "<form id=\"pop-up-action\" method=\"post\">" +
                                                                    "<div class=\"form-group-js\">" +
                                                                    "<div class=\" control-label\">" +
                                                                    "<label for=\"name\">Имя пользователя" +
                                                                    "</div>" +
                                                                    "<div class=\" controls\">" +
                                                                    "<input id=\"login\" type=\"text\" name=\"login\">" +
                                                                    "</div>" +
                                                                    "</div>" +
                                                                    "<div class=\"form-group-js\">" +
                                                                    "<div class=\" control-label\">" +
                                                                    "<label for=\"password\">Пароль" +
                                                                    "</div>" +
                                                                    "<div class=\" controls\">" +
                                                                    "<input id=\"password\" type=\"password\" name=\"password\">" +
                                                                    "<p id=\"errorMSG\" style='color: red; margin-top: 5px'></p>" +
                                                                    "</div>" +
                                                                    "</div>" +
                                                                    "<div class=\"form-group-js\">" +
                                                                    "<div class=\" controls\">" +
                                                                    "<input name=\"sing-in\" type=\"submit\" id=\"sendForm\" value=\"Sing In\">" +
                                                                    "</div>" +
                                                                    "</div>" +
                                                                    "<a href='reset-pwd'>Забыли пароль?</a>" +
                                                                    "</form>");
        popUpContainer.classList.add("show-popUp");
        overlay.style.display = "block";
        var idFom = document.getElementById("sendForm");
        idFom.addEventListener("click", getSingIn, false);
    }

    /**
     * Функция закрытия ПопАп формы
     * @param val - id контейнера который нужно удалить
     * overlay у всех фор один
     */
    function closePopUp(val) {
        var element = document.getElementById(val);
        popUpContainer.classList.remove("show-popUp");
        overlay.style.display = "none";
        var video = document.querySelector('video');
        var tools = document.getElementById('redactor-tools');
        if (video){
            video.srcObject.getTracks().forEach(function (track) {track.stop(); });
            tools.style.display = "none";
        }

        element.remove();
    }


    /**
     * Слушатель для ПопАп формы, если пользователь не залогинен
     */
    if (singIn != null){
        singIn.addEventListener("click", showSingIn);
    }

    /**
     * Глобальные слушатели для ПопАп форм
     */
    overlay.addEventListener("click", () => { closePopUp("pop-up-action"); });
    closePopUpBtn.addEventListener("click",() => { closePopUp("pop-up-action"); });

    /**
     * ajax функция для вывода формы входа в личный кабинет
     */
    function getSingIn(event) {
            event.preventDefault();
            var data = "login=" + document.getElementById("login").value + "&";
            data += "password=" + document.getElementById("password").value + "&";
            data += "sing-in=SingIn";

            var XHR = ("onload" in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;
            var xhr = new XHR();
            xhr.open('POST', '/auth', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function () {
                    var resalt = this.responseText;
                    console.log(resalt);
                    if (resalt === "false")
                        document.getElementById("errorMSG").innerHTML = "Неверный имя пользователя или пароль!";
                    else{
                        location.href=location.href;
                    }
                };

            xhr.onerror = function () {
                alert('Ошибка ' + this.status);
            };
            xhr.send(data);

    }

});