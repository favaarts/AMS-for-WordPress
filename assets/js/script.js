jQuery( document ).ready(function() {
    var alltext = jQuery('.wpamsform input[type="text"]').val();
    console.log(alltext)

    if(!jQuery('.wpamsform input[type="text"]').val()) {

         jQuery('#savechanges').attr("disabled",false);
    }
    else
    {
    	 jQuery('#savechanges').attr("disabled",true);
         jQuery('input[type="text"]').prop('disabled', true);
         jQuery('input[type="url"]').prop('disabled', true);
         jQuery('input[type="color"]').prop('disabled', true);
         jQuery('#urlwindow').prop('disabled', true);
    }


    jQuery('#cleartext').click(function(){				
		if(confirm("Want to clear?")){
			/*Clear all input type="text" box*/
			jQuery('.wpamsform input[type="text"]').val('');

			jQuery('#savechanges').attr("disabled",false);

            jQuery('input[type="text"]').prop('disabled', false);
            jQuery('input[type="url"]').prop('disabled', false);
            jQuery('input[type="color"]').prop('disabled', false);

            jQuery('#urlwindow').prop('disabled', false);
		}					
	});

    //Color
    jQuery('#colorpicker').on('change', function() {
    jQuery('#hexcolor').val(this.value);
    });
    jQuery('#hexcolor').on('change', function() {
      jQuery('#colorpicker').val(this.value);
    });


    // Check subdomain
    jQuery("#subdomainerror").hide();
    jQuery('input[name="wpams_url_btn_label"]').blur(function() {

        var subdomain = jQuery(this).val();
        var amsajax =  jQuery("#ajaxurl").val();
        
        jQuery.ajax({
            url: amsajax,
            type: 'post',
            data : {
                action : 'subdomainkey_validation',
                subdomain : subdomain
            },
            success: function(result)
            {
                if (result == 0)
                {
                    console.log("Invalide subdomain.");
                    jQuery('#subdomainerror').html("Invalide subdomain.");
                    jQuery("#subdomainerror").show();
                }
                else
                {
                    console.log("Success");
                    jQuery("#subdomainerror").hide();
                }
                
            },
            fail : function( err ) {
                // You can craft something here to handle an error if something goes wrong when doing the AJAX request.
                console.log( "There was an error: " + err );
            }
        });

    });


    
});