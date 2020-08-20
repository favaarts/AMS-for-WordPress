<?php

function amscategory_function( $slug ) {
	ob_start();  
	?>
<div id="category" class="category">

 <?php

//
$apiurl = get_option('wpams_url_btn_label');
$apikey = get_option('wpams_apikey_btn_label');
//$url = "".$apiurl."?access_token=".$apikey."&method=get&format=json";
$url = "https://".$apiurl.".amsnetwork.ca/api/v3/assets/filter?access_token=".$apikey."&method=get&format=json";

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
$json = curl_exec($ch);
if(!$json) {
    echo curl_error($ch);
}
curl_close($ch);

 $arrayResult = json_decode($json, true);
//print_r(json_decode($json));
/* echo "<pre>";
 var_dump($arrayResult);
 echo "</pre>";
 die;*/

if(isset($arrayResult['error']))
{
    echo $arrayResult['error'];

    
}
elseif($arrayResult == NULL && $arrayResult == "")
{
    echo " Something went wrong! Please check subdomain and API key ";    
}
else
{
    foreach($arrayResult as $json_value) {
        
        foreach($json_value as $x => $x_value) { 
         
            if($x === 'categories') {
                echo '<h3>Categories</h3>';
                foreach($x_value as $c => $c_value) {
                    echo $c_value[1];
                    echo "<br>";
                }
            }
            
        }
    }
}
    

?>
</div>
<?php
	$ret = ob_get_contents();  
	ob_end_clean(); 
	return $ret; 
}
add_shortcode('amscategory', 'amscategory_function');

?>