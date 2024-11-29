
<script>
    let bind_mac_route = "{{route('users.bind_unbind_mac')}}";
      //
    $(document).on('click','.bindmacclass',function(){
      // 
      let element = $(this);
      var username = element.attr('data-username');
        //
          $.ajax({ 
            type: "POST",
            url: bind_mac_route ,
            data: "username="+username,
            success: function (data) {
  
                   $(element).html('<i class="la la-server"></i> '+data);
            },
            error: function(jqXHR, text, error){
                  console.log(jqXHR.responseJSON.message);
            },
          });
          
          // }
          return false;
        });
  </script>