
function productback()
{

	  jQuery(".right-col-wrap").show();
	  jQuery(".productdetail").hide();
	
}	


function equipmentdetails(prodictkey)
{
	/*console.log(prodictkey);
	console.log("hello");
	return false;*/
	console.log(prodictkey);

	event.preventDefault();
	var prodictid =  prodictkey;
	
	jQuery('#inifiniteLoader').hide();

	jQuery(window).unbind('scroll');

	jQuery.ajax({
		url: amsjs_ajax_url.ajaxurl,
		type: 'post',
		data : {
			action : 'equipmentproductdetails_action',
			prodictid : prodictid
		},
		success: function(result)
		{
			/*console.log(result);
			return false;*/
			jQuery(".productdetail").show();
			jQuery('.productdetail').html(result);
			jQuery(".right-col-wrap").hide();
		}
	});

	
}

function categorydata(getcatid)
{
	event.preventDefault();
	var catid =  getcatid;

	document.getElementById('keyword').value = '';

	/*console.log(catid);*/


	jQuery.ajax({
		url: amsjs_ajax_url.ajaxurl,
		type: 'post',
		data : {
			action : 'getcategory_action',
			catid : catid
		},
		success: function(result)
		{
			/*console.log(result);
			return false;*/
			jQuery(".productdetail").hide();
			jQuery(".right-col-wrap").show();
			jQuery('.right-col-wrap').html(result);
		}
	});
}

function fetchequipment()
{
	event.preventDefault();

	jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searchcategorydata_action', keyword: jQuery('#keyword').val() },
        success: function(data) {
        	jQuery(".productdetail").hide();
        	jQuery(".right-col-wrap").show();
            jQuery('.right-col-wrap').html(data);
        }
    });
}


	/*$('.wp-block-columns').bind('scroll', function(){
	   if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
	      
	      var data = {
	            'action': 'infinitescroll_action',
	            'page': page
	        };
	 
	        $.post(amsjs_ajax_url.ajaxurl, data, function(response) {
	            if($.trim(response) != '') {
	                //jQuery('.categorysearchdata').html(response);
	                jQuery('.categorysearchdata').append(response);
	                page++;
	            } 
	        });

	   }
	});*/
	
	
