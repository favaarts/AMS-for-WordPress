<?php

function amscategoryequipment_function( $slug ) {
    ob_start();  
    ?>

<div id="category" class="category">

 <?php

// https://wpd.amsnetwork.ca/api/v3/assets?type=Equipment&access_token=de5cb03d9c6bb57d5cd41b0616e72716d53fb5f6ec34e6bb0b8ff05acf029dac&method=get&format=json
$apiurl = get_option('wpams_url_btn_label');
$apikey = get_option('wpams_apikey_btn_label');
//$url = "".$apiurl."?access_token=".$apikey."&method=get&format=json";
//$url = "https://".$apiurl.".amsnetwork.ca/api/v3/assets&type=Equipment?access_token=".$apikey."&method=get&format=json";
$i = 1;

/*$url = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&page=1&limit=10&access_token=".$apikey."&method=get&format=json";*/
$url = "https://".$apiurl.".amsnetwork.ca/api/v3/assets";

//$url = "https://".$apiurl.".amsnetwork.ca/api/v3/assets/filter?access_token=".$apikey."&method=get&format=json";


//print_r(json_decode($json));
/* echo "<pre>";
 var_dump($arrayResult);
 echo "</pre>";
 die;*/


 //=======

 //

?>
    
  
<div class="entry-content">
        
<div class="wp-block-columns" >
    


    <div class="wp-block-column" style="flex-basis:33.33%">
        <div class="searchbox">
            <h4>Search Box</h4>
            <input type="text" name="keyword" id="keyword" onkeyup="fetchequipment()"></input>
        </div>

        <?php
        $carurl = $url ."/filter?access_token=".$apikey."&method=get&format=json";



        $catch = curl_init();
        curl_setopt($catch,CURLOPT_URL,$carurl);
        curl_setopt($catch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($catch,CURLOPT_CONNECTTIMEOUT, 4);
        $json = curl_exec($catch);
        if(!$json) {
            echo curl_error($catch);
        }
        curl_close($catch);

        $catArrayResult = json_decode($json, true);

        

         foreach($catArrayResult as $catjson_value) {
                
                foreach($catjson_value as $cat => $cat_value) { 
                 
                    if($cat === 'categories') {
                        echo '<h4>Categories</h4>';
                         echo "<ul>";
                        foreach($cat_value as $c => $c_value) {
                            echo "<li>";
                            ?>

                            <a href='' onclick='return categorydata(<?= $c_value[0] ?>)'><?= $c_value[1]?></a>

                            
                            
                            <?php   
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                    
                }
            }

        ?>
    </div>  




    <div class="categorysearchdata" style="flex-basis:66.66%">

         <?php
        $categoryid = 834;

        $producturl = $url ."?type=Equipment&category_ids=%5B".$categoryid."%5D&access_token=".$apikey."&method=get&format=json";

        /*echo $producturl;
        die;*/

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$producturl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
        $json = curl_exec($ch);
        if(!$json) {
            echo curl_error($ch);
        }
        curl_close($ch);

         $arrayResult = json_decode($json, true);

            foreach($arrayResult as $json_value) {
                
                foreach($json_value as $x_value) { 

                        if(isset($x_value['name']))
                        {
                            echo "<a href='". $x_value['id']."' target='_blank'> <h3>". $x_value['name'] ."</h3> </a>";
                            echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                        }
                   
                }
            }
          ?>
    </div> 
    
</div>

</div>




<?php
function myscript() {
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

    var page = 2;    
    jQuery(window).scroll(function () {
        
        
        if (jQuery(window).scrollTop() +  jQuery(window).height() > jQuery(document).height() - 600)
        {
            
            var data = {
                'action': 'infinitescroll_action',
                'page': page
            };
     
            jQuery.post(amsjs_ajax_url.ajaxurl, data, function(response) {
                if(jQuery.trim(response) != '') {
                    //jQuery('.categorysearchdata').html(response);
                    jQuery('.categorysearchdata').append(response);
                    page++;
                } 
            });
        }
    });

});    
</script>
<?php
}
add_action('wp_footer', 'myscript');

?>
	
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}
add_shortcode('amscategoryequipment', 'amscategoryequipment_function');

?>