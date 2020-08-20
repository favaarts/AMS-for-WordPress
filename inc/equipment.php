<?php

function amsequipment_function( $slug ) {
    ob_start();  
    ?>
<div id="category" class="category">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div id="getquiment"></div>	

<script>

$(document).ready(function(){

var apiurljs = "<?php echo get_option('wpams_url_btn_label'); ?>";
var apikeyjs = "<?php echo get_option('wpams_apikey_btn_label'); ?>";
/*console.log(apiurljs);
alert(apiurljs);
return false;*/
var page = '1';

function getData() {
  $.getJSON('https://'+apiurljs+'.amsnetwork.ca/api/v3/assets?type=Equipment&page='+page+'&limit=10&access_token='+apikeyjs+'&method=get&format=json', function(data) {

    console.log(data);


    var equipmentdata = data.assets;

    $.each(equipmentdata, function(i, val) {

     console.log(data);
      var image = val;
      var imageURL = val.photo;
      var userName = val.name;
      
      $('#getquiment').append('<div class="image"><img src="'+ imageURL +'"><p><a href="#" target="_blank">'+userName+'</a></p></div>');  

    });

  });
}

getData();

	$('#btn').on('click', function() {

		page ++;
  		getData();
	  
	});

});

</script>

 <button id="btn" data-page="1" data-per-page="10">Load More </button> 
	
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}
add_shortcode('amsequipment', 'amsequipment_function');

?>