$(function(){
	$("#wizard_form").steps({
        headerTag: "h4",
        bodyTag: "section",
        transitionEffect: "fade",
        enableAllSteps: true,
        transitionEffectSpeed: 300,
        labels: {
            next: "Themes and Logo",
            previous: "Reseller Detail"
        },
        onStepChanging: function (event, currentIndex, newIndex) { 
            if ( newIndex === 1 ) {
                $('.steps ul').addClass('step-2');
            } else {
                
                $('.steps ul').removeClass('step-2');
            }
            if ( newIndex === 2 ) {
                $('.steps ul').addClass('step-3');
                $('.actions ul').addClass('mt-7');
            } else {
                $('.steps ul').removeClass('step-3');
                $('.actions ul').removeClass('mt-7');
            }
            return true; 
        },
        // Triggered when clicking the Finish button
        onFinishing: function(e, currentIndex) {
		   
            // alert(isValid);
            // var fv         = $('#wizard_form').data('formValidation'),
                // $container = $('#wizard_form').find('section');
                // $container = $('#wizard_form').find('section[data-step="' + currentIndex +'"]');

            // Validate the last step container
            // fv.validateContainer($container);

            // var isValidStep = fv.isValidContainer($container);
            // if (isValid === false || isValid === null) {
            //     console.log('false');
            //     return false;
            // }
            console.log('true');

            return true;
        },
        onFinished: function(e, currentIndex) {
            // Uncomment the following line to submit the form using the defaultSubmit() method
            // $('#profileForm').formValidation('defaultSubmit');
            // alert('hello');
            // For testing purpose
            // $('#welcomeModal').modal();
        }
    });
    // Custom Button Jquery Steps
    $('.forward').click(function(){
    	$("#wizard_form").steps('next');
    })
    $('.backward').click(function(){
        $("#wizard_form").steps('previous');
    })
    // Grid 
    $('.grid .grid-item').click(function(){
        $('.grid .grid-item').removeClass('active');
        $(this).addClass('active');
    })
    // Click to see password 
    $('.password i').click(function(){
        if ( $('.password input').attr('type') === 'password' ) {
            $(this).next().attr('type', 'text');
        } else {
            $('.password input').attr('type', 'password');
        }
    }) 
    // Date Picker
    // var dp1 = $('#dp1').datepicker().data('datepicker');
    // dp1.selectDate( new Date( ));
})
