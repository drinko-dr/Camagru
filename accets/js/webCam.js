
document.addEventListener('DOMContentLoaded', function(){
    var photo = document.getElementById('new-photo');
    var closePopUpBtn = document.getElementById("close-popUp");

    var popUpContainer = document.getElementById("pop-up");
    var overlay = document.getElementById("overlay");

    photo.addEventListener("click", function (e) {
        createWebCam();
        popUpContainer.style.left = "30%";
        popUpContainer.style.top = "10%";

        popUpContainer.style.maxWidth = "unset";

        popUpContainer.style.width = "640px";
        popUpContainer.classList.add("show-popUp");
        overlay.style.display = "block";

    });
    let outer = null;
    let selectBox = null;
    let filter = null;
    let resizeNavLeft = null;
    let videoWidht = null
    let videoHeight = null
    function snapShote() {
        popUpContainer.insertAdjacentHTML('beforeend', "<div id='pop-up-action'>" +
                                                                        "<canvas id='canvas' usemap=\"#workmap\" ></canvas>" +
                                                                        "<div class='resize' id='resize-left'></div>" +
                                                                        "<div class='resize' id='resize-top'></div>" +
                                                                        "<div class='resize' id='resize-right'></div>" +
                                                                        "<div class='resize' id='resize-bottom'></div>" +
                                                                        "<div id='filter'></div>" +
                                                                        "<div class='imgareaselect' style=''></div>" +
                                                                        "<div class='imgareaselect-outer' id='imgareaselect-outer-left'></div>" +
                                                                        "<div class='imgareaselect-outer' id='imgareaselect-outer-top'></div>" +
                                                                        "<div class='imgareaselect-outer' id='imgareaselect-outer-right'></div>" +
                                                                        "<div class='imgareaselect-outer' id='imgareaselect-outer-bottom'></div>" +
                                                                        "<button id='change-photo'>Изменить</button><button id='save-photo'>Сохранить</button></div>");
        var video = document.querySelector('video');
        videoWidht = video.videoWidth;
        videoHeight = video.videoHeight;

        var canvas = document.getElementById('canvas');
        canvas.width = videoWidht;
        canvas.height = videoHeight;

        var ctx = canvas.getContext('2d');

        ctx.drawImage(video,0,0,videoWidht,videoHeight);

        var imageDataURL = canvas.toDataURL('image/jpeg');

        // document.getElementById('img_shote').src = imageDataURL;

        video.srcObject.getTracks().forEach(function (track) {track.stop(); });
        document.getElementById('pop-up-action').remove();

        document.getElementById('change-photo').addEventListener("click",() => { changePhoto(); });
        document.getElementById('save-photo').addEventListener("click",() => { savePhoto(imageDataURL); });

        document.getElementById('redactor-tools').style.display = "block";

////////////////////////////////////////////////////////////
        filter = document.getElementById('filter');

        selectBox = document.querySelector('.imgareaselect');
        outer = {
                    left:document.getElementById('imgareaselect-outer-left'),
                    top:document.getElementById('imgareaselect-outer-top'),
                    right:document.getElementById('imgareaselect-outer-right'),
                    bottom:document.getElementById('imgareaselect-outer-bottom'),
                    resize:{
                        left: document.getElementById('resize-left'),
                        right: document.getElementById('resize-right'),
                        top:document.getElementById('resize-top'),
                        bottom:document.getElementById('resize-bottom')
                        }
                            };
        document.onselectstart = function() {
            return false;
        };
        filter.onmousedown = function (e) {
            selectArea(e);
        };
        resizeNavLeft = document.getElementById('resize-left');
        ///////////////////////////////////////////////////
    }


    function selectArea(e, size) {
        selectBox.style.width = "0px";
        selectBox.style.height = "0px";
        if (size){
            // selectBox.style.width = size.width+"px";
            // selectBox.style.height = size.height+"px";
            // setMoveArea();
            setCoord({x:size.x1, y:size.y1}, {x:size.x2, y:size.y2});
        }
        var start = {x:e.offsetX==undefined?e.layerX:e.offsetX, y:e.offsetY==undefined?e.layerY:e.offsetY}, end;
        document.onmouseup = function (e) {
            document.onmousemove = null;
            setMoveArea();
        };
        document.onmousemove = function (e) {
            end = {x:e.offsetX==undefined?e.layerX:e.offsetX, y:e.offsetY==undefined?e.layerY:e.offsetY};
            // console.log(e.target.getAttribute('id'));
            setCoord(start,end);
        };
    }

    function isResize(coordCurrent) {
        var start = {x:parseInt(selectBox.style.left), y:parseInt(selectBox.style.top)};
        var end = {x:parseInt(selectBox.style.width) + start.x, y:parseInt(selectBox.style.height) + start.y};

        if (coordCurrent.x >= start.x  && coordCurrent.x <= start.x + 6 && coordCurrent.y >= start.y  && coordCurrent.y <= end.y){
            return "left";
        }else if (coordCurrent.x >= end.x - 6 && coordCurrent.x <= end.x + 2 && coordCurrent.y >= start.y  && coordCurrent.y <= end.y) {
            return "right";
        }else if (coordCurrent.y >= start.y  && coordCurrent.y <= start.y + 6 && coordCurrent.x >= start.x  && coordCurrent.x <= end.x) {
            return "top";
        }else if (coordCurrent.y >= end.y - 6 && coordCurrent.y <= end.y + 2 && coordCurrent.x >= start.x  && coordCurrent.x <= end.x) {
            return "bottom";
        }else if (coordCurrent.x > start.x + 6  && coordCurrent.x <= end.x - 6 && coordCurrent.y > start.y + 6  && coordCurrent.y < end.y - 6){
            return "center";
        }else
            return false;
    }

    function mouseHover() {

        filter.onmousemove = function(e){
            var coordCurrent = {x:e.offsetX==undefined?e.layerX:e.offsetX, y:e.offsetY==undefined?e.layerY:e.offsetY};

            let resizeable = isResize(coordCurrent);

            switch (resizeable) {
                case "left":
                case "right":
                    filter.style.cursor = "e-resize";
                    break;
                case "top":
                case "bottom":
                    filter.style.cursor = "n-resize";
                    break;
                case "center":
                    filter.style.cursor = "move";
                    break;
                default:
                    filter.style.cursor = "unset";
            }

        };

    }

    function setMoveArea() {
        mouseHover();
        filter.onmousedown = function (e) {

            var start = {x:parseInt(selectBox.style.left), y:parseInt(selectBox.style.top)};
            var end = {x:parseInt(selectBox.style.width) + start.x, y:parseInt(selectBox.style.height) + start.y};
            var coord = {x:e.offsetX==undefined?e.layerX:e.offsetX, y:e.offsetY==undefined?e.layerY:e.offsetY}, coordEnd;


            if (isResize(coord) === "left"){
                setResize(e, "left");
                // console.log("resizeleft");
            }else if (isResize(coord) === "top"){
                setResize(e, "top");
                // console.log("resize top");

            }else if (isResize(coord) === "bottom"){
                setResize(e, "bottom");
                // console.log("resize bot");

            }else if (isResize(coord) === "right"){
                setResize(e, "right");
                // console.log("resize right");

            }
            else if (isResize(coord) === "center"){
                document.onmouseup = function (e) {
                    filter.onmousemove = null;
                    mouseHover();
                };
                filter.onmousemove = function (e) {
                    coordEnd = {x:e.offsetX==undefined?e.layerX:e.offsetX, y:e.offsetY==undefined?e.layerY:e.offsetY};
                    moveCoord(coordEnd, coord, start,end);

                };
            }else{
                // setResize();
                selectArea(e, {x1:coord.x, y1:coord.y, x2:coord.x + 5, y2:coord.y + 5});
            }
        };

    }
    
    function moveCoord(coordEnd, coord, start, end) {

        var left, top, selectLeft, selectTop;

        left =  coordEnd.x - coord.x;
        top = coordEnd.y - coord.y;
        selectLeft = start.x + left;
        selectTop = start.y + top;
        if (selectLeft <= 0){
            selectLeft = 1;
        }
        if ( selectTop <= 0 )
            selectTop = 1;

        if ( end.x + left + 4 <= videoWidht)
            selectBox.style.left = selectLeft +"px";

        if ( end.y + top + 4 <= videoHeight)
            selectBox.style.top = selectTop +"px";

        setResizePointer( end.x - start.x, end.y - start.y, selectTop, selectLeft, true);
        setBackground({x:selectLeft, y:selectTop}, {x:left + end.x + 4, y:top + end.y + 4}, selectLeft,selectTop);

    }


   function setCoord(start, end) {

        var leftPos = start.x;
        var topPos = start.y;
        var widthX = end.x  - leftPos;
        var heightY = end.y - topPos;

        setBackground(start, end, widthX, heightY);

        if (heightY < 0){
            heightY *= (-1);
            topPos -= heightY;
        }

        if (widthX < 0){
            widthX *= (-1);
            leftPos -= widthX;
        }

        if ( topPos <= 0 )
            topPos = 0;
        if ( leftPos <= 0)
            leftPos  = 0;

        console.log(topPos, leftPos);

        selectBox.style.left = leftPos+"px";
        selectBox.style.top = topPos+"px";
        selectBox.style.width = (widthX - 4)+"px";
        selectBox.style.height = (heightY - 4)+"px";

        setResizePointer( widthX, heightY, topPos, leftPos);

    }

    function setResizePointer( widthX, heightY, topPos, leftPos, move) {
        var point = 0;
        if (move === true)
            point = 4;
        var top = heightY + topPos - 6 + point;
        var left = widthX + leftPos - 6 + point;

        if ( top <= videoHeight - 6){
            outer.resize.top.style.top = topPos + "px";
            outer.resize.left.style.top = (heightY  / 2 + topPos) + "px";
            outer.resize.bottom.style.top = top + "px";
            outer.resize.right.style.top = (heightY  / 2 + topPos) + "px";
        }

        if ( left <= videoWidht - 6){
            outer.resize.right.style.left = left + "px";
            outer.resize.left.style.left = leftPos + "px";
            outer.resize.top.style.left = (widthX  / 2 + leftPos) + "px";
            outer.resize.bottom.style.left = (widthX  / 2 + leftPos) + "px";
        }



    }

    function setBackground( start, end, x, y){
        let temp;
        let x1 = start.x;
        let x2 = end.x;
        let y1 = start.y;
        let y2 = end.y;

console.log(start, end, "x1: " +x, "y1: " + y);

        if ( x <= 0 ){
            temp = x1;
            x1 = x2;
            x2 = temp;
        }
        if ( y <= 0 ){
            temp = y1;
            y1 = y2;
            y2 = temp;
        }


        if (x1 > 1 && x2 <= videoWidht){
            outer.left.style.width = x1 + "px";
            outer.top.style.left = x1 + "px";
            outer.top.style.width = (x2 - x1) + "px";
            outer.bottom.style.left = x1 + "px";
            outer.bottom.style.width = (x2 - x1) + "px";
            outer.right.style.left = x2 + "px";
            outer.right.style.width = (videoWidht - x2) + "px";
        }

        if (y1 > 1 && y2 <= videoHeight){
            outer.top.style.height = y1 + "px";
            outer.bottom.style.height = (videoHeight - y2) + "px";
            outer.bottom.style.top = (y2) + "px";
        }




        outer.left.style.height = videoHeight + "px";

        outer.right.style.height = videoHeight + "px";

    }


    function setResize(e,side) {

        var start, end;
        var left = parseInt( selectBox.style.left );
        var width = parseInt( selectBox.style.width );
        var top = parseInt( selectBox.style.top );
        var height = parseInt( selectBox.style.height );
        var resize = 0;


        start = {x:e.offsetX==undefined?e.layerX:e.offsetX,y:e.offsetY==undefined?e.layerY:e.offsetY };
        document.onmouseup = function (e) {
            filter.onmousemove = null;
            mouseHover();
            console.log("hover active");
        };
        filter.onmousemove = function (e) {

            end = {x: e.offsetX == undefined ? e.layerX : e.offsetX, y:e.offsetY==undefined?e.layerY:e.offsetY};



            switch (side) {
                case "left":
                    resize = end.x - start.x;
                    resizeLeft(left, width, resize);
                    break;
                case "top":
                    resize = end.y - start.y;
                    resizeTop(height,top,resize);
                    break;
                case "right":
                    resize = start.x - end.x;
                    resizeRight(left,width,resize);
                    break;
                case "bottom":
                    resize =  start.y - end.y ;
                    resizeBottom(height,top,resize);
                    break;
                default:
                    return;
            }

        }

    }

    function resizeTop(height,top, resize) {

        if ( height - resize <= 10 )
            return;

        selectBox.style.top = (resize + top ) + "px";
        selectBox.style.height = ( height - resize ) + "px";

        outer.resize.left.style.top = (resize / 2 + top + height /2) + "px";
        outer.resize.top.style.top = (resize + top ) + "px";
        outer.resize.right.style.top = (resize / 2 + top + height /2 ) + "px";

        outer.top.style.height = (resize + top ) + "px";
    }

    function resizeBottom(height,top, resize) {

        if ( height - resize <= 10 )
            return;

        selectBox.style.height = ( height - resize ) + "px";

        outer.resize.left.style.top = ( top + height /2 - resize / 2) + "px";
        outer.resize.bottom.style.top = (height + top - resize ) + "px";
        outer.resize.right.style.top = ( top + height /2 - resize / 2 ) + "px";

        outer.bottom.style.top = (top + height - resize + 4 ) + "px";
        outer.bottom.style.height = ( (videoHeight - (top + height)) + resize - 4 ) + "px";
    }

    function resizeLeft(left,width, resize) {

            if ( width - resize <= 10 )
                return;

            selectBox.style.left = (resize + left ) + "px";
            selectBox.style.width = ( width - resize ) + "px";

            outer.resize.left.style.left = (resize + left ) + "px";
            outer.resize.top.style.left = (resize/2 + left + width/2 ) + "px";
            outer.resize.bottom.style.left = (resize/2 + left + width/2) + "px";


            outer.left.style.width = (resize + left ) + "px";

            outer.top.style.left = (resize + left ) + "px";
            outer.top.style.width = (width - resize + 4 ) + "px";

            outer.bottom.style.left = (resize + left ) + "px";
            outer.bottom.style.width = (width - resize + 4  ) + "px";
        }
    function resizeRight(left,width, resize) {

            if ( width - resize <= 10 )
                return;

            selectBox.style.width = ( width - resize ) + "px";

            outer.resize.right.style.left = ( left + width - resize ) + "px";
            outer.resize.top.style.left = ( left + width/2 - resize/2 ) + "px";
            outer.resize.bottom.style.left = (left + width/2 - resize/2 ) + "px";

            outer.right.style.width = ( (videoWidht - ( width + left)) + resize - 4) + "px";
            outer.right.style.left = (width + left - resize + 4 ) + "px";

            outer.top.style.width = (width - resize + 4 ) + "px";

            outer.bottom.style.width = (width - resize + 4  ) + "px";
        }

    function changePhoto() {
        document.getElementById('pop-up-action').remove();
        createWebCam();
    }


    function savePhoto(img) {

        var data = "avatar="+img;

        var XHR = ("onload" in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;
        var xhr = new XHR();
        xhr.open('POST', '/save_img', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function () {
            var resalt = this.responseText;
            console.log(resalt);
            // if (resalt === "false")
            //     document.getElementById("errorMSG").innerHTML = "Неверный имя пользователя или пароль!";
            // else{
            //     location.href=location.href;
            // }
        };

        xhr.onerror = function () {
            alert('Ошибка ' + this.status);
        };
        xhr.send(data);

    }

    // closePopUpBtn.addEventListener("click",() => { closePopUp("pop-up-action"); });

    function createWebCam() {
        popUpContainer.insertAdjacentHTML('beforeend', "<div id='pop-up-action' class=\"view--video\"><video autoplay></video><div><button  id='snapshot'>Фото</button></div></div>");
        navigator.getUserMedia = (navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

        // var front = false;
        // document.getElementById('flip-button').onclick = function() { front = !front; };

        // var constraints = { video: { facingMode: (front? "user" : "environment") } };
        var options = {
            audio: false,

            video: {width: { min: 375, ideal: 640, max: 640 },
                    height: { min: 480, ideal: 520, max: 520 } }
        };


        navigator.mediaDevices.getUserMedia(options)
            .then(function(mediaStream) {
                var video = document.querySelector('video');
                video.srcObject = mediaStream;
                video.onloadedmetadata = function(e) {
                    video.play();
                };
            })
            .catch(function(err) {
                console.log(err.name + ": " + err.message);
            });

        document.getElementById('snapshot').addEventListener("click", () => { snapShote(); });
    }



});