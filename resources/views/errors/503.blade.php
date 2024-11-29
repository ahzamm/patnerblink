<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
</head>
<style>
    *{
        margin: 0;
        padding: 0;
        font-family: 'poppins', sans-serif;
        box-sizing: border-box;
    }

.container{
    width: 100vw;
    height: 100vh;
    background-image: url('/maintenance_img/maintenance-background.png');
    background-position: center;
    background-size: cover;
    padding: 0 8%;
}
.container:before{
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: #686868;
    opacity: .5;
}
.logo{
    width: 120px;
    padding: 20px 0;
    cursor: pointer;
}
.content{
    top: 50%;
    position: absolute;
    transform: translateY(-50%);
    color: #fff;
}
.content p{
    margin-bottom: 20px;
}
.content h1{
    font-size: 64px;
    font-weight: 600;
    margin-bottom: 20px;
}
.content h1 span{
    color:#ff3753;
}
.content button{
    background: transparent;
    border: 2px solid #fff;
    outline: none;
    padding: 12px 25px;
    color: #fff;
    display: flex;
    align-items: center;
    margin-top: 30px;
    cursor: pointer;
}
.content button img{
    width: 15px;
    margin-left: 10px;
}
.launch-time{
    display: flex;
}
.launch-time div{
    flex-basis: 200px;
    text-align: center;
}
.launch-time div p{
    font-size: 60px;
    margin-bottom: -4px;
}
.gear_img{
    position: absolute;
    width: 150px;
    bottom: -100px;
    filter: invert(1) opacity(0.3);
    right: 0;
    animation: gear 10s linear infinite
}
.rocket{
    width: 250px;
    position: absolute;
    right: 10%;
    bottom: 0;
    animation: rocket 4s linear infinite;
}
@keyframes rocket{
    0%{
        bottom: 0;
        opacity: 0;
    }
    100%{
        bottom: 105%;
        opacity: 0.7;
    }
}
@keyframes gear{
    
    100%{
        transform: rotate(360deg);
    }
}

#custom_modal{
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #9797975a;
    padding: 40px 20px;
    border-radius: 4px;
    backdrop-filter: blur(10px);
    color: #fff;
    width: 650px;
}
#custom_modal p{
    margin-bottom: 10px;
    font-size: 22px;
    text-align: center;
}
#custom_modal button {
    position: absolute;
    right: 15px;
    top: 0;
    font-size: 40px;
    background: transparent;
    border: none;
    color: #fff;
    cursor: pointer;
}

@media (max-width: 768px) {
    #custom_modal{
        width: 95%
    }
    .gear_img{
        right: 40px;
        bottom: -50px;
    }
    .content h1{
        font-size: 50px;
    }
    .launch-time div p{
        font-size: 48px;
    }
    .rocket{
        width: 200px;
    }
}
@media (max-width: 576px) {
    .gear_img{
        width: 100px;
    }
    .launch-time div p{
        font-size: 36px;
    }
    .rocket{
        width: 150px;
    }
    .container{
        padding: 0 20px;
    }
    #custom_modal p{
        font-size: 18px;
    }
}
</style>
<body>
    <div class="container">
        <!-- <img src="https://i.postimg.cc/DZtGv0tD/logo.png" class="logo"> -->
        <div class="content">
            <p>Portal Is Under Maintenance</p>
            <h1>We're <span>Coming</span> Shortly</h1>
            <div class="launch-time">
                <div>
                    
                    <p id="days">00</p>
                    <span>Days</span>
                </div>
                <div>
                    <p id="hours">00</p>
                    <span>Hours</span>
                </div>
                <div>
                    <p id="minutes">00</p>
                    <span>Minutes</span>
                </div>
                <div>
                    <p id="seconds">00</p>
                    <span>Seconds</span>
                </div>
            </div>
            <button type="button" onclick="showModal()">Contact Us <img src="https://i.postimg.cc/QC1THsDM/triangle.png" ></button>
        <img src="/maintenance_img/gear.png" class="gear_img">
        
        </div>
        <img src="/maintenance_img/rocket.png" class="rocket">
    </div>
    <div class="custom_modal" id="custom_modal">
        <div class="custom_modal_content">
            <button onclick="closeModal()">&times;</button>
            <p>Please contact on the below numbers</p>
            <p>(021) 3 11 11 LOGON , (021) 38402064 , (0300) 9278931 </p>
        </div>
    </div>
    <script>
        var time = '<?php echo $message->activation_time; ?>';
     
        var countDownDate = new Date(time).getTime();
        
        var x = setInterval(function() {
        
          var now = new Date().getTime();
        
          var distance = countDownDate - now;
        
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
          document.getElementById('days').innerHTML = days;
          document.getElementById('hours').innerHTML = hours;
          document.getElementById('minutes').innerHTML = minutes;
          document.getElementById('seconds').innerHTML = seconds;
        
          // If the count down is finished, write some text
          if (distance < 0) {
            clearInterval(x);
            // document.getElementById("demo").innerHTML = "EXPIRED";
          }
        }, 1000);
        </script>

        <script>
            var x = document.getElementById('custom_modal');
            function showModal() {
                x.style.display = 'block';
            }
            function closeModal() {
                x.style.display = 'none';
            }
        </script>
</body>
</html>