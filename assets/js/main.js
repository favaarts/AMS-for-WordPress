

function equipmentdetails(prodictkey)
{
	console.log(prodictkey);
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
			jQuery('.productdetail').html(result);
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
			jQuery('.right-col-wrap').html(result);
		}
	});
}

function fetchequipment()
{
	event.preventDefault();
	var slugurl = jQuery('#slugurl').val();
	var catid = jQuery('#categoryid').val();

	jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searchcategorydata_action', keyword: jQuery('#keyword').val(),slugurl:slugurl, catid:catid },
        success: function(data) {
        	//console.log(data);
        	
        	jQuery('.right-col-wrap').html(data);
        		
        }
    });
}


function fetchevent()
{
	event.preventDefault();
	/*var slugurl = jQuery('#slugurl').val();
	var catid = jQuery('#categoryid').val();*/
	var pageslug = jQuery('#inputpageslug').val();
	var pageid = jQuery('#inputpageid').val();
	
	jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searcheventdata_action', getevent: jQuery('#getevent').val(), pageslug: pageslug, pageid: pageid},
        success: function(data) {
        	//console.log(data);
        	
        	jQuery('.right-col-wrap').html(data);
        	jQuery('#seemore').hide();
        }
    });
}


function fetchproject()
{
	var pageid = jQuery("#getpageid").val();
	
	jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searchprojectdata_action', projectdata: jQuery('#getproject').val(), pageid:pageid},
        success: function(data) {
        	//console.log(data);
        	
        	jQuery('.right-col-wrap').html(data);
        	jQuery('#seemore').hide();
        }
    });
}