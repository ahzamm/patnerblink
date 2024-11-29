       <style type="text/css">
        .animate {
            transition: .5s ease-in-out;
            transform: translateX(100px);
        }
        .complaint__container {
            position: fixed;top: 50%;right: 15px;z-index: 999999;padding: 20px;width:240px;
            transform: translate(500px, -50%);transition: .5s ease-in-out;
            background-color: #FFF;
            border: 5px solid #4e4e4e;
            border-radius: 20px;
            background-attachment: fixed;
            background-size: cover;
        }
        .complaint__container::before {
            content: '';
            position: absolute;
            right: -22px;
            top: 50%;
            width: 20px;
            height: 20px;
            border-width: 18px 0px 18px 18px;
            border-style: solid;
            border-top-color: transparent;
            border-bottom-color: transparent;
            border-right-color: transparent;
            border-left-color: #4e4e4e;
            transform: translateY(-50%);
        }
        .complaint__container.show {
            transition: .5s ease-in-out;
            transform: translate(0px, -50%);
        }
        .complaint__wrapper.hidden,
        .complaint__container.hidden {
            display:none;
        }
        </style>
        <!-- Register Complaint -->
        <div class="complaint__container">
            <div class="complaint__wrapper p-2"> 
                <h4 class="text-center theme-color" style="padding-top: 10px;font-weight:500">Register Complaint</h4>
                <hr style="height:1px;margin-top:15px;margin-bottom:15px">
                <div id="returnMsg"></div>
                <div class="row">
                    <form id="popUpregisterComplainForm">
                        <div class="col-sm-12 form-group mt-3">
                            <label for="" style="font-weight:normal">Select Complaint Nature</label>
                            <select class="form-control" name="complaint_nature_id">
                            </select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="" style="font-weight:normal">Description</label>
                            <textarea class="form-control" name="description" rows="7" style="resize:none"></textarea>
                        </div>
                        <div class="col-12 form-group">
                            <p class="text-center" >
                                <button type="submit" class="btn btn-primary mr-1" style="margin-top: 20px">Register</button> 
                                <button type="button" class="btn btn-danger" onclick="closeComplaintForm()" style="margin-top: 20px">Cancel</button></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Register Complaint end -->

            <!-- animated button -->

            <div class="svg__btn_complain" id="btn__complain" style="position: fixed;top:209px;right:0;z-index:99999;cursor: pointer;" onclick="openComplaintForm()">
                <div class="button" style="width:49px;height:46px;border-top-left-radius: 50%;border-bottom-left-radius:50%;background-color: #000000bc">
                    <svg id="support" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 128 128" shape-rendering="geometricPrecision" text-rendering="geometricPrecision"><g id="support-u-headphones"><path id="support-s-path1" d="M42,56c0-12.150264,9.849736-22,22-22s22,9.849736,22,22" fill="none" stroke="#f5b939" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path id="support-s-path2" d="M41,77c-3.313708,0-6-2.686292-6-6v-6c0-3.313708,2.686292-6,6-6h1c1.148752,0,2.08.931248,2.08,2.08v13.84c0,1.148752-.931248,2.08-2.08,2.08Z" transform="translate(0-3)" fill="none" stroke="#f5b939" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path id="support-s-path3" d="M87,77c3.313708,0,6-2.686292,6-6v-6c0-3.313708-2.686292-6-6-6h-1c-1.148752,0-2.08.931248-2.08,2.08v13.84c0,1.148752.931248,2.08,2.08,2.08Z" transform="translate(0-3)" fill="none" stroke="#f5b939" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path id="support-s-path4" d="M68,79c9.19,0,16.86-8.165172,18.62-18.988774" transform="translate(0 14)" fill="none" stroke="#f5b939" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><line id="support-s-line1" x1="68" y1="79" x2="64" y2="79" transform="translate(0 14)" fill="none" stroke="#f5b939" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/></g><g id="support-u-head"><circle id="support-u-head2" r="20" transform="translate(64 65)" fill="none" stroke="#f5b939" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><g id="support-u-face" transform="translate(0 0.000002)"><path id="support-u-mouth" d="M10.5,0C10.5,5.79899,5.79899,10.5,0,10.5" transform="matrix(.707107 0.707107-.707107 0.707107 64 65)" opacity="0" fill="none" fill-opacity="0.5" stroke="#f5b939" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="24.75" stroke-dasharray="0,16.5"/><circle id="support-u-eye" r="1" transform="matrix(1.5 0 0 0 56 62.00962)" opacity="0" fill="#f5b939" fill-opacity="0.5" stroke="#f5b939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle id="support-u-eye2" r="1" transform="matrix(1.5 0 0 0 72 62.00962)" opacity="0" fill="#f5b939" fill-opacity="0.5" stroke="#f5b939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></g></g>
                        <script><![CDATA[
                            (function(s,i,u,o,c,w,d,t,n,x,e,p,a,b){(a=document.getElementById(i.root)).svgatorPlayer={ready:(function(a){b=[];return function(c){return c?(b.push(c),a.svgatorPlayer):b}})(a)};w[o]=w[o]||{};w[o][s]=w[o][s]||[];w[o][s].push(i);e=d.createElementNS(n,t);e.async=true;e.setAttributeNS(x,'href',[u,s,'.','j','s','?','v','=',c].join(''));e.setAttributeNS(null,'src',[u,s,'.','j','s','?','v','=',c].join(''));p=d.getElementsByTagName(t)[0];p.parentNode.insertBefore(e,p);})('91c80d77',{"root":"support","version":"2022-05-04","animations":[{"elements":{"support-u-headphones":{"transform":{"data":{"t":{"x":-64,"y":-65}},"keys":{"o":[{"t":0,"v":{"x":64,"y":65,"type":"corner"},"e":[0.42,0,0.58,1]},{"t":500,"v":{"x":64,"y":64,"type":"corner"},"e":[0.42,0,0.58,1]},{"t":1000,"v":{"x":64,"y":65,"type":"corner"}}]}}},"support-u-head":{"transform":{"data":{"t":{"x":-64,"y":-65}},"keys":{"o":[{"t":0,"v":{"x":64,"y":65,"type":"corner"},"e":[0.42,0,0.58,1]},{"t":500,"v":{"x":64,"y":63,"type":"corner"},"e":[0.42,0,0.58,1]},{"t":1000,"v":{"x":64,"y":65,"type":"corner"}}]}}},"support-u-face":{"transform":{"data":{"t":{"x":-64,"y":-64.999998}},"keys":{"o":[{"t":0,"v":{"x":64,"y":65,"type":"corner"},"e":[0.42,0,0.58,1]},{"t":500,"v":{"x":64,"y":63,"type":"corner"},"e":[0.42,0,0.58,1]},{"t":1000,"v":{"x":64,"y":65,"type":"corner"}}]}}},"support-u-mouth":{"opacity":[{"t":90,"v":0},{"t":100,"v":1}],"stroke-dashoffset":[{"t":100,"v":24.75,"e":[0.215,0.61,0.355,1]},{"t":400,"v":33}],"stroke-dasharray":[{"t":100,"v":[0,16.5],"e":[0.215,0.61,0.355,1]},{"t":400,"v":[16.5,0]}]},"support-u-eye":{"transform":{"data":{"o":{"x":56,"y":62.00962,"type":"corner"}},"keys":{"s":[{"t":0,"v":{"x":1.5,"y":0},"e":[0.215,0.61,0.355,1]},{"t":400,"v":{"x":1,"y":1}},{"t":700,"v":{"x":1,"y":1},"e":[0.42,0,1,1]},{"t":800,"v":{"x":1.5,"y":0.2},"e":[0,0,0.58,1]},{"t":900,"v":{"x":1,"y":1}}]}},"opacity":[{"t":0,"v":0,"e":[0.215,0.61,0.355,1]},{"t":400,"v":1,"e":[0.55,0.055,0.675,0.19]}]},"support-u-eye2":{"transform":{"data":{"o":{"x":72,"y":62.00962,"type":"corner"}},"keys":{"s":[{"t":0,"v":{"x":1.5,"y":0},"e":[0.215,0.61,0.355,1]},{"t":400,"v":{"x":1,"y":1}},{"t":700,"v":{"x":1,"y":1},"e":[0.42,0,1,1]},{"t":800,"v":{"x":1.5,"y":0.2},"e":[0,0,0.58,1]},{"t":900,"v":{"x":1,"y":1}}]}},"opacity":[{"t":0,"v":0,"e":[0.215,0.61,0.355,1]},{"t":400,"v":1,"e":[0.55,0.055,0.675,0.19]}]}},"s":"MDFA1ZGE4VzRmTFjkxYTI5ZjhlTYTE5NjljOWIV0ZkM2NzVlNWBQ1ZEY1ZDU5NMGY5MTk2TjlmHOTI5MGExOTYB5YzliNGY2NzNVlNTk0Zjk2YHTE5MjlmOGVVVYTE5NjljOWJUhMDRmNjc1ZTKU5QjRmOTM5NHjk5OTk0ZjY3WNWU1OTRmOGUK5OWExUzkyOWYY5YjhlYTE5MMjRmNjc5MzhlFOTlhMDkyNTkG0ZmEwOWQ5MjNkyOTE0ZjY3NOWVXNTk0ZjkzIOWRhMDRmNjcP1ZTVkNWRPYWBE/"}],"options":"MDKAyMzg1MmM3ZJDdlNmJFN2M3IZTJjNDQyYzcUyNzk4MDZmN2LMyYzM2MmM3MTlY3OTgwNmY3EYzJjTTQ0MmMJ3YzZmODA2ZjCdjSzdkNmYyYHzg3"},'https://cdn.svgator.com/ply/','__SVGATOR_PLAYER__','2022-05-04',window,document,'script','http://www.w3.org/2000/svg','http://www.w3.org/1999/xlink')
                            ]]></script>
                        </svg>
                    </div>
                </div>

                <script>
                    function openComplaintForm() {

            //
                        $.ajax({
                            method: 'GET',
                            url: "{{route('users.complain.get_complain_nature')}}",


                            success: function(data){
                                $('.complaint__container select').html(data);
                            },
                            complete: function(){
                                $('#btn__complain').addClass('animate');
                                setTimeout(() => {
                                    $('.complaint__container').addClass('show');
                                }, 500);
                            }   
                        });
                    }
                    function closeComplaintForm() {
                        $('#btn__complain').removeClass('animate');
                        $('.complaint__container').removeClass('show');
                    }
                </script>

                <script type="text/javascript">
    // $(document).ready(function(){
                  $("#popUpregisterComplainForm").submit(function() {
    // 
                    if(confirm("Do your really want register this complain?")){

      //
                      $.ajax({ 
                        type: "POST",
                        url: "{{route('users.complain.generate_complaint')}}",
                        data:$("#popUpregisterComplainForm").serialize(),
                        success: function (data) {
                          $('.complaint__container #returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
                          $('#popUpregisterComplainForm').trigger('reset');
                   //
                          setTimeout(function() { 
                            $('#btn__complain').removeClass('animate');
                            $('.complaint__container').removeClass('show');
                            $('.complaint__container #returnMsg').html('');
                        }, 2000);
                          
                      },
                      error: function(jqXHR, text, error){
                          $('.complaint__container #returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
                      },
                      complete:function(){

                      },
                  });

                  }
                  return false;
              })
          </script>