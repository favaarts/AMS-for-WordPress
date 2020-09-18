jQuery( document ).ready(function() {
    var alltext = jQuery('.wpamsform input[type="text"]').val();
    console.log(alltext)

    if(!jQuery('.wpamsform input[type="text"]').val()) {
         jQuery('#savechanges').attr("disabled",false);
    }
    else
    {
    	 jQuery('#savechanges').attr("disabled",true);
    }


    jQuery('#cleartext').click(function(){				
		if(confirm("Want to clear?")){
			/*Clear all input type="text" box*/
			jQuery('.wpamsform input[type="text"]').val('');

			jQuery('#savechanges').attr("disabled",false);
		}					
	});
});