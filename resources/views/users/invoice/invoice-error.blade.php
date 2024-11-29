<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body{
            height: 100vh;
        }
        .bg__img{
            height: 80vh;
            background-image: url(https://img.icons8.com/dotty/80/error--v2.png);
            background-repeat: no-repeat;
            background-size: contain;
            background-position: right center;
        }
        .bg__img::before{
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffffed;
        }
        .wrapper{
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .back_btn{
            border: 1px solid;
            padding: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="bg__img"></div>
    <div class="wrapper">
        <p style='text-align:center'><img width="50" height="50" src="https://img.icons8.com/ios-filled/50/medium-risk.png" alt="medium-risk"/></p>
        <h1>Invoice Error Page</h1>
        <p style='text-align:center'><a href="#" class="back_btn">Go Back</a></p>
    </div>
</body>
</html>