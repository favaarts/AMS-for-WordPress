<?php

function amscategoryequipment_function( $slug ) {
    ob_start();  
    ?>

<div id="category" class="category cat-wrap">

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
    
  
<div class="entry-content main-content-wrap">
 
<!-- ======================================================================
notes::

main-content - this class is for two columns.

main-content main-content-three-col - this class is for three columns.

======================================================================  -->

<div class="wp-block-columns main-content main-content-three-col" >
    


    <div class="wp-block-column left-col" >
        <div class="searchbox">
            <h4>Search Box</h4>
            <input type="text" class="searrch-input" name="keyword" id="keyword" onkeyup="fetchequipment()"></input>
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
                         echo "<ul class='ul-cat-wrap'>";
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




    <div class="categorysearchdata right-col" >
        <div class="right-col-wrap">
            
        

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

          /*echo "<pre>";
 var_dump($arrayResult['meta']);
 echo "</pre>";
 die;*/

 //echo $arrayResult['meta']['equipment_items_count'];

//echo "<img src=". plugins_url( 'assets/img/loader.svg', __FILE__ ).">";
//echo "<img src=". esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) . ">";

            foreach($arrayResult as $json_value) {
                
                

                foreach($json_value as $x_value) { 

                    if(isset($x_value['id']))
                    {
                        //productstyle  
                        //
                        echo "<div class='productstyle'>";
                        echo "<div>";
                            if(isset($x_value['name']))
                            {
                                echo "<a href='". $x_value['id']."' target='_blank'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
                                 echo "<div class='product-img-wrap'>";
                                    echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                                 echo "</div>";

                                echo "<div class='bottom-fix'>"; 
                                if($x_value['status_text'] == "Active")
                                    echo "<p><span class='label label-success btn-common'>Available</span></p>";
                                    else
                                    {
                                        echo "<p><span class='label label-danger btn-common'>Unavailable</span></p>";
                                    }
                                }

                                 echo "<div class='price-main'>"; 
                                    echo "<p>$ 10 </p>";
                                 echo "</div>";
                             echo "</div>";
                            echo "<p class='price-non-mem'>Non-Member: $20.00 </p>";

                        echo "</div>";    
                        echo "</div>";
                    }
                }

                
            }
          ?>
       </div>   
    </div> 
    
</div>
    <div class="loaderdiv">
        <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['equipment_items_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>
    <div>    
</div>




<?php
function myscript() {
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

     /*console.log(amsjs_ajax_url.ajaxurl);
     console.log("hello");*/

   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   $(window).scroll(function(){
     if ($(window).scrollTop() == $(document).height() - $(window).height()){
      if (count > total){
        return false;
      }else{
        loadArticle(count);
      }
      count++;
     }
   });


   function loadArticle(pageNumber){
     $('a#inifiniteLoader').show('fast');

     console.log(amsjs_ajax_url.ajaxurl);
     console.log("hello");

     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: "action=infinitescroll_action&page="+ pageNumber + '&loop_file=loop',
       beforeSend: function(){
        // Show image container
        $("#inifiniteLoader").show();
       },
       success: function (html) {
         jQuery('#inifiniteLoader').hide('1000');
         
         jQuery('.right-col-wrap').append(html);
       }
     });
     return false;
   }

    

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