<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: copperplate;
            src: url('/certificate/copperplate-gothic.ttf');
        }
        @font-face {
            font-family: copperplate-bold;
            src: url('/certificate/copperplate-gothic-bold.ttf');
        }
        body{
            font-family: copperplate;
            background-color: #6c6c6c;
            text-transform: uppercase;
            /* font-size:18px; */
        }
        .img__wrapper{
            position: relative;
            width: 1400px;
            margin: auto;
        }
        .content{
            position: absolute;
            top: 31%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
        }
        .content p{
            text-align: center;
        }
        .blink-color{
            color: #3a9b3e
        }
        .content h1{
            font-size: 2.5em;
            letter-spacing: 3px;
        }
        .blink-title{
            font-weight: bold;
            letter-spacing: 20px;
            font-size: 22px;
        }
        hr{
            border-top: 2px solid #000;
            margin-bottom: 5px;
            margin-top: 5px;
        }
        .first-para{
            text-transform: uppercase;
            /* font-size: 18px; */
        }
        #img_container canvas{
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="" id="img_container">
        <div class="img__wrapper" id="imgWrapper">
            <img src="/certificate/certificate.jpg" alt="">
            <div class="content" id="canvasWrapper">
                <h1 class="text-center blink-color">Husnain baber</h1>
                <hr>
                <p class="text-center blink-color blink-title">BLINK BROADBAND</p>
                <p class="first-para">The certificate above is hereby granted a contractor certificate to practice/cary on the profession / occupation / business of: </p>
                <p style="font-weight: 500;font-size: 24px;" class="mb-0">Contractor Service  : <span class="blink-color">General Contractor</span></p>
                <p style="font-weight: 500;font-size: 24px;">Contractor Category : <span class="blink-color">Filer Contractor</span></p>
                <p>Granted this on 18-07-2023 by and witht the authority of the Blink Contractor association and subject to the term and conditions that</p>
                <p>The certificate acquires all other necessary government agencies compliances relating to </p>
                <p>This certificate expires on : <span class="blink-color">July 18, 2024</span></p>
                <div style="width:200px; margin-left: 230px;margin-top:70px">
                    <h4 class="mb-0 text-center">18 July, 2023</h4>
                    <hr>
                    <p class="blink-color mb-0" style="font-weight: bold;letter-spacing: 3px;
                    font-size: 20px;">Issue Date</p>
                </div>
            </div>
        </div>
        <!-- <a href="#" id="downloadLink" class="btn btn-success" onclick="downloadFunction()">View Certificate</a> -->
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/certificate/html2canvas.min.js"></script>
    <script>
        var element = document.getElementById('imgWrapper');
        var container = document.getElementById('img_container');
// var aLink = document.getElementById('downloadLink');
function downloadFunction() {
// html2canvas(element).then(function(canvas) {
//         container.appendChild(canvas);
//         element.style.display = 'none';
//         var image = canvas.toDataURL("image/jpg");
//         aLink.href = image;
//         callLink(image);
//     });
}
function callLink(url){
    let image2 = document.createElement('img');
    image2.src = url;
    var newTab = window.open();
    newTab.document.body.appendChild(image2);
    newTab.document.body.style.backgroundColor = "gray";
}
window.onload = function() {
    html2canvas(element).then(function(canvas) {
        container.appendChild(canvas);
        element.style.display = 'none';
    });
}
</script>
</body>
</html>