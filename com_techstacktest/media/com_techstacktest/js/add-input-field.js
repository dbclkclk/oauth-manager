jQuery(document).ready(function(){
    jQuery('.addOptionField').on('click', function() {
        var maxField = 10; //Input fields increment limitation
        var wrapper = jQuery('#options.option_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="controls" style="margin-bottom:5px;"><input type="text" name="multiple-option[]" />&nbsp; <a href="javascript:void(0);" class="remove_button btn btn-danger" title="Remove field">Remove</a></div>'; //New input field html 
        var x = 1; //Initial field counter is 1
        if(x < maxField){ //Check maximum number of input fields
            x++; //Increment field counter
            jQuery(wrapper).append(fieldHTML); // Add field html
            }

        jQuery(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });

    jQuery('.addAnswerField').on('click', function() {
        var maxField = 10; //Input fields increment limitation
        var wrapper = jQuery('.answer_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="controls" style="margin-bottom:5px;"><input type="text" name="multiple-answer[]" />&nbsp; <a href="javascript:void(0);" class="remove_button btn btn-danger" title="Remove field">Remove</a></div>'; //New input field html 
        var x = 1; //Initial field counter is 1
        if(x < maxField){ //Check maximum number of input fields
            x++; //Increment field counter
            jQuery(wrapper).append(fieldHTML); // Add field html
            }

        jQuery(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });

    jQuery('.addField').on('click', function() {
        var maxField = 10; //Input fields increment limitation
        var wrapper = jQuery('.option_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="controls" style="margin-bottom:5px;"><input type="text" name="sin-multiple-option[]" />&nbsp; <a href="javascript:void(0);" class="remove_button btn btn-danger" title="Remove field">Remove</a></div>'; //New input field html 
        var x = 1; //Initial field counter is 1
        if(x < maxField){ //Check maximum number of input fields
            x++; //Increment field counter
            jQuery(wrapper).append(fieldHTML); // Add field html
            }

        jQuery(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });

});
