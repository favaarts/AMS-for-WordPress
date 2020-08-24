<?php

function amsevents_function( $slug ) {
    ob_start();  
    ?>

<div id="category" class="category cat-wrap">

 <?php

$apiurl = get_option('wpams_url_btn_label');
$apikey = get_option('wpams_apikey_btn_label');

$url = "https://".$apiurl.".amsnetwork.ca/api/v3/programs";

?>

<div class="entry-content main-content-wrap">
 
<!-- ======================================================================
notes::

main-content - this class is for two columns.

main-content main-content-three-col - this class is for three columns.

======================================================================  -->

<div class="wp-block-columns main-content main-content-three-col" >

	<!-- Search section -->
	<div class="wp-block-column left-col" >
        <div class="searchbox">
            <h4>Search Box</h4>
            <input type="text" class="search-input" name="keyword" id="keyword" onkeyup="fetchequipment()"></input>
        </div>

        <?php
        $filterurl = $url ."/filter?access_token=".$apikey."&method=get&format=json";


        // Request to get the categories 
        $catch = curl_init();
        curl_setopt($catch,CURLOPT_URL,$filterurl);
        curl_setopt($catch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($catch,CURLOPT_CONNECTTIMEOUT, 4);
        $json = curl_exec($catch);
        if(!$json) {
            echo curl_error($catch);
        }
        curl_close($catch);

        $filArrayResult = json_decode($json, true);


        // Showing the statuses/filters (REEVALUATE HOW TO SHOW THESE FILTERS and FILTER through the events (put them at the top?))
        foreach($filArrayResult as $catjson_value) {
                
                foreach($catjson_value as $fil => $fil_value) { 
                 
                    if($fil === 'status') {
                        echo '<h4>Categories</h4>';
                         echo "<ul class='ul-cat-wrap'>";
                        foreach($fil_value as $f => $f_value) {
                            echo "<li>";
                            ?>

                            <a href='' onclick='return categorydata(<?= $f_value[0] ?>)'><?= $f_value[1]?></a>

                            <?php   
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                    
                }
            }

        ?>
    </div>  


    <!-- Main content, Programming of events -->
    <div class="categorysearchdata right-col" >
        <div class="right-col-wrap">
            
        

         <?php
        $categoryid = 834;

        $programurl = $url ."?category_ids=%5B".$categoryid."%5D&access_token=".$apikey."&method=get&format=json";

       

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$programurl);
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
                                    $member = $x_value[0];
                                    $member_price = $member[0];
                                    
                                    $nonmember = $x_value[1];
                                    $nonmember_price = $nonmember[0];
                                    
                                    echo "<p>".$member_price."</p>";
                                 echo "</div>";
                             echo "</div>";
                            echo "<p class='price-non-mem'>".$nonmember_price."</p>";

                        echo "</div>";    
                        echo "</div>";
                    }
                }

                
            }
          ?>
       </div>   
    </div> 
    
</div>

<?php 
	// Calls the whole code above
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}
add_shortcode('amsevents', 'amsevents_function');

?>