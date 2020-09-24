jQuery( document ).ready(function() {
    
    jQuery("#starapikey").hide();
         
    var subdomainurl = jQuery('#subdomainurl').val(); 
    var apikeytext = jQuery('#apikeytext').val(); 
    if(subdomainurl != "" && apikeytext != "") 
    {
        jQuery('#subdomainurl').prop('readonly', true);
        jQuery('#starapikey').prop('readonly', true);  

        jQuery("#starapikey").show();
        jQuery("#apikeytext").hide();

    }


    jQuery('#cleartext').click(function() {
        var r = confirm('Are you sure you want to clear the API settings?')
        if (r == true) {
            jQuery('#subdomainurl').prop('readonly', false);
            jQuery('#starapikey').prop('readonly', false);

            jQuery('#subdomainurl').val(''); 
            jQuery('#apikeytext').val(''); 

            jQuery("#starapikey").hide();
            jQuery("#apikeytext").show();
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