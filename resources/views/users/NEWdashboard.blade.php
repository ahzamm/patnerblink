@extends('users.layouts.app', [
    'profileCollection' => $profileCollection,
])
@section('title')
    Dashboard
@endsection
@section('owncss')
<head>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<script src="https://kit.fontawesome.com/45fa0ed931.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<link href="css-circular-prog-bar.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<link href="{{ asset('dashboardCss/dashboard.css') }}" rel="stylesheet">

</head>
@endsection
@section('content')
<main>
    <body>
        <section class="home-section">
    {{-- <header>
         <div class="container"> 
            <div class="head-section">
                <div class="partone">
                    <i class="fa-solid fa-bars"></i>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <i class="fa-solid fa-bell"></i>
                </div>
                

                    <div class="profile">
                        <div class="icon">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="heading4">
                            <h4>Jawad</h4>
                        </div>
                    </div>
                </div>
                </div>
                </div> 
               </header> --}}
             <div class="bord"></div>
               <nav>
                <div class="containerss">
                    <div class="nav-section">
                        <div class="hello">
                            <h4>Changing World with technology..!</h4>
                            <h2>Hello,<b> Logon Broadband</b></h2>
                            <h5>jawadalam@lbi.net.pk , Office No E1 Executive floor Glass Tower Clifton Karachi </h5>
                            <div class="modal" tabindex="-1" id="myModal">
                                <div class="modal-dialog  modal-lg">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Modal title</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <!-- ########## DO WORK HERE################# -->
                                    <div class="modal-body">
                                     <div class="img-1">
                                      <img class="img-12" src="images/graph-1`.png">
                        
                                     </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                        <div class="bott2">
                          <button class="bot"href="#" data-bs-toggle="modal" data-bs-target="#myModal">MRTG Graph</button>
                        </div>
                              
                        </div>
                            <div class="parttwo">
                                <div class="amount">
                                    <div class="icon-text"><i class="fa-solid fa-wallet"></i>   Wallet </div>
                                    <h6>147,390</h6>
                                </div>
                        </div>
                        
                    </div>
                   
                </div>
            </nav>
                </section>
    <section class="specify-section">
        <div class="containerssss"> 
            <div class="second-section">
                <div class="one">
                    <h4>156814</h4>
                    <h4>All Users</h4>
                </div>
                <div class="two">
                    <h4>141</h4>
                    <h4>Dealer</h4>
                </div>
                <div class="three">
                    <h4>850</h4>
                    <h4>Sub-Dealer
                    </h4>
                </div>
                <div class="four">
                    <h4>0</h4>
                    <h4>Trader
                    </h4>
                </div>
                <div class="five">
                    <h4>0</h4>
                    <h4>Active Users
                    </h4>
                </div>
               
              
              
            </div>
        </div>
    </section>
        </section>
  <section class="Chart-section">
                <div class="containersssssssss">
                    <div class="bar-graph">
                        <div id="chartContainer" style="height: 400px; width: 80%; display: inline-block;">
</div>
<div class="table-section">
  <div class="scroll">
  <table>
      <tr>
        <th>Profile Name</th>
        <th>Login time</th>
        <th>Profile Color</th>
      </tr>
      <tr>
        <td>Alfreds Futterkiste</td>
        <td  border="2"align="center">2 min </td>
        <td style="background-color: rgb(41, 230, 236);"></td>
      </tr>
      <tr>
        <td>Centro comercial Moctezuma</td>
        <td align="center">1 hr </td>
        <td style="background-color: rgb(216, 22, 31);"></td>
      </tr>
      <tr>
        <td>Ernst Handel</td>
        <td align="center">7 hr </td>
        <td style="background-color: rgb(11, 196, 57);"></td>
      </tr>
      <tr>
        <td>Island Trading</td>
        <td align="center">57 min </td>
        <td style="background-color: rgb(23, 13, 168);"></td>
      </tr>
      <tr>
        <td>Laughing Bacchus Winecellars</td>
        <td align="center">10 min </td>
        <td style="background-color: rgb(180, 0, 126);"></td>
      </tr>
      <tr>
        <td>Magazzini Alimentari Riuniti</td>
        <td align="center">8 min </td>
        <td style="background-color: rgb(174, 202, 11);"></td>
      </tr>

      <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">17 min </td>
          <td style="background-color: rgb(0, 0, 0);"></td>
        </tr>

        <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">2 hr </td>
          <td style="background-color:orangered;"></td>
        </tr>

        <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">2 hr </td>
          <td style="background-color: rgb(0, 84, 87);"></td>
        </tr>

        <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">6 hr </td>
          <td style="background-color: rgb(216, 22, 31);"></td>
        </tr>

        <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">1 min </td>
          <td style="background-color:rgb(26, 9, 172);"></td>
        </tr>

        <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">4 hr </td>
          <td style="background-color: rgb(216, 22, 31);"></td>
        </tr>

        <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">55 min </td>
          <td style="background-color: rgb(43, 134, 0);"></td>
        </tr>

        <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">8 hr </td>
          <td style="background-color: rgb(255, 217, 0);"></td>
        </tr>

          <tr>
          <td>Magazzini Alimentari Riuniti</td>
          <td align="center">1 hr </td>
          <td style="background-color: rgb(128, 128, 128);"></td>
        </tr>
    </table>
    </div>
</div>
                         
                    </div>
                    <div class="container2">
                        <div class="card">
                            <div class="percent" style="--clr:white; --num:82;">
                                <div class="dot"></div>
                                <svg>
                                    <circle cx="70" cy="70" r="70"></circle>
                                    <circle cx="70" cy="70" r="70"></circle>
                                   </svg>
                                <div class="number">
                                    <h2>82<span>%</span></h2>
                                    <p>Online Users</p>
                   </div>
                   </div>
                   </div>
                   </div>
                       </div>
                       
                          </section>
                          <section class="round-box">
                            
<div class="container">
    <div class="round-section">
              <div class="rb1">
                                    <div class="headerr">
                                    <img src="{{ asset('images/2nd.jpg') }}">
                                    </div>
                                    <div class="mainn">
                                        <h3>39</h3>
                                        <p>Invalid Login</p>
                                    </div>
                             </div>
                               
                            <div class="rb1">
                                        <div class="headerr">
                                    <img src="{{ asset('images/2nd.jpg') }}">
                                    </div>
                                    <div  class="mainn">
                                        <h3>1486</h3>
                                    
                                        <p>Disable User</p>
                                    </div>
                                </div>
                            <div class="rb1">
                                    <div class="headerr">
                                        <img src="{{ asset('images/2nd.jpg') }}">
                                        </div>
                                        <div class="mainn">
                                            <h3>4292</h3>
                                            <p>Upcoming Expire</p>
                                        </div>
                                   </div>
                            <div class="rb1">
                                    <div class="headerr">
                                    <img src="{{ asset('images/2nd.jpg') }}">
                                    </div>
                                    <div class="mainn">
                                        <h3>39</h3>
                                        <p> 104789 Nic Verified
                                        </p>
                </div>
                </div>
                </div>
                </div>
                </section>


            <section class="slider">
                <div class="container">
                    <p class="moving-texturdu">تمام  ڈیلرز اور سبڈیلر  کو مطلع کیا جاتا ہے کہ وہ اپنے تمام یوزرز کو تصدیق کریں بصورت دیگر آپ اپنے غیر تصدیق شدہ یوزرز کی پروفائل تبدیل نہیں کر سکتے 
                    </p>
                    <p class="moving-text">
                        It is to inform all dealers/sub-dealers to Verify your users, Otherwise you will not be able to change unverified user profile.
                    </p>
                    </p>
                </div>
             </section>
             <footer>
                <div class="container">
                    <div class="last-section">
                        <div class="social-media">
                            <div class="facebook">
                                <i class="fa-brands fa-facebook-f" title="facebook"></i>
                            </div>
                            <div class="twitter">
                                <i class="fa-brands fa-twitter" title="twitter"></i>    
                            </div>
                            <div class="google">
                                <i class="fa-brands fa-google-plus-g" title="googleplus"></i> 
                            </div>
                            <div class="linked">
                                <i class="fa-brands fa-linkedin-in" title="linkedin"></i>
                            </div>
                        </div>
                        <div class="copyright">
                            Copyright © 2020 All Rights Reserved by Logon Broadband (pvt) Ltd
                        </div>
                    </div>
                </div>
             </footer>
    </body>
    @endsection
@section('ownjs')
     <script>
//        var canvasElement = document.getElementById("cookieChart");
//         var config={
//  type:"bar",
//  data:{label:["Nasr","Solana-5","Ghaznavi","Bitcoin-10","Cardano-18","Shaheen","Ababeel","Babur"],
//  datasets:[{label:"No Of Users", data:[50,76,234,481,640,864,1487,2228]}],
//  }
//          }
//        var cookieChart =new Chart(canvasElement,config)
const ctx = document.getElementById('myChart');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["Nasr","Solana-5","Ghaznavi","Bitcoin-10","Cardano-18","Shaheen","Ababeel","Babur"],
    datasets: [{
      label: ' No of Users',
      data: [481,76,765,50,640,864,167  ,998],
      backgroundColor:[
      'rgba(4, 59, 92)',
      'rgba(40, 67, 135)',
      'rgba(8, 14, 44)',
      'rgba(30, 81, 123)',
      'rgba(44, 62, 80)',
      'rgba(1, 1, 122)',
      'rgba(92, 151, 191)',
      'rgba(30, 81, 123)',
],
borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)',
      'rgba(186, 153, 207)',
    ],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
$(function() {
  $('.chart').easyPieChart({
    size: 160,
    barColor: "#36e617",
    scaleLength: 0,
    lineWidth: 15,
    trackColor: "#525151",
    lineCap: "circle",
    animate: 2000,
  });
});
    </script> 
    
    <script>
        window.onload = function () {
            
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            
            title:{
                text:""
            },
            axisX:{
                interval: 0
            },
            axisY2:{
                interlacedColor: "rgba(2,44,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "No. of Users"
            },
            data: [{
                type: "bar",
                name: "companies",
                axisYType: "secondary",
                color: "rgb(0, 45, 109,1)",
                dataPoints: [
                    { y: 100, label: "Nasr" },
                    { y: 200, label: "Solana-5" },
                    { y: 300, label: "Ghanzanvi" },
                    { y: 400, label: "Bit-coin10" },
                    { y: 540, label: "Shaheen" },
                    { y: 600, label: "Etheremu" },
                    { y: 700, label: "half" },
                   
                ]
            }]
        });
        chart.render();
        
        }
        </script>
</main>
@endsection
