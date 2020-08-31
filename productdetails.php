<?php

get_header(); 


global $wp, $wpdb;

//echo $wp->query_vars['category'];
//echo "<br>";
//echo $wp->query_vars['proname'];

$apiurl = get_option('wpams_url_btn_label');
$apikey = get_option('wpams_apikey_btn_label');

$i = 1;

$url = "https://".$apiurl.".amsnetwork.ca/api/v3/assets";
?>
<div class="site-content"> <!-- site-content" -->
<div class="container no-sidebar">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">


    <!-- Entry content -->
    <div class="entry-content">

<div class="wp-block-columns main-content main-content-three-col" >
    



    <div class="categorysearchdata right-col" >
        <div class="productdetail"></div>
            <div class="right-col-wrap">
                
            <?php
            $prodictid = $_POST['prodictid'];
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    

    $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets/".$wp->query_vars['proname']."?type=Equipment&access_token=".$apikey."&method=get&format=json";

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

        
            echo "<div class='product-detail-wrap'>";
            foreach($arrayResult as $json_value) {

                echo "<div class='pro-detail-inner'>";

                     echo "<div class='pro-detail-left'>";

                        echo "<a href='javascript:history.back()' class='pro-back'><img class='back-img' src='". plugins_url( 'assets/img/back.png', __FILE__ ) ."' >Back</a>"; 

                        if($json_value['photo'] == NULL || $json_value['photo'] == "")
                        {                                    
                            echo "<div class='pro-img'>";
                                echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$json_value['name'].">";
                             echo "</div>";
                        }
                        else
                        {
                            echo "<div class='pro-img'>"; 
                                echo "<img src=".$json_value['photo_medium']." alt=".$json_value['name'].">";
                            echo "</div>";
                        }
                     echo "</div>";

                     echo "<div class='pro-detail-right'>"; 
                        echo "<div class='cat-name'>"; 
                            echo "<p >". $json_value['category_name'] ."</p>";
                        echo "</div>";

                        echo "<div class='pro-name'>"; 
                             echo "<p >". $json_value['name'] ."</p>";
                        echo "</div>";

                        echo "<div class='price_types'>";
                            echo "<div class='cat-name'>"; 
                                echo "<p >Prices(per day)</p>";
                            echo "</div>";
                            
                            echo "<p class='pro-price'>". $json_value['price_types'][0][0] ."</p>";
                            echo "<p class='pro-price non-mem'>". $json_value['price_types'][1][0] ."</p>";
                        echo "</div>";
                        
                        echo "<div class='available-details'>"; 
                            if($json_value['status_text'] == "Active")
                            {
                                echo "<bR class='d-n'>";
                                echo "<p><span class='label label-success btn-common'>Available</span></p>";
                            }    
                            else
                            {
                                echo "<p><span class='label label-danger btn-common'>Unavailable</span></p>";
                            }
                        echo "</div>"; 

                        echo "<div class='pro-num'>";
                            echo "<div class='barcode cat-name'>"; 
                                echo "<p>Barcode Number:</p>";
                                echo "<spna class='B-text'>".$json_value['barcode']."</span>";
                            echo "</div>";

                            echo "<div class='barcode cat-name'>"; 
                                echo "<p>Serial Number:</p>";
                                echo "<spna class='B-text'>"
                                .$json_value['serial_number']."</span>";
                            echo "</div>";

                             echo "<div class='barcode cat-name'>"; 
                                echo "<p>Insurance Value:</p>";
                                echo "<spna class='B-text'>31738.25</span>";
                            echo "</div>";

                            
                        echo "</div>";

                     echo "</div>";

                echo "</div>";
                if($json_value['description'])
                {
                    echo "<div class='product-des acc-des'>";
                        echo "<p class='product-des-title'>Information</p>";
                        echo "<p class='pro-des-text'>". $json_value['description'] ."</p>";
                    echo "</div>";
                }
               
                echo "<div class='product-des-wrap'>";

                    if($json_value['included_accessories']) 
                    {
                        echo "<div class='product-des m-r-pro'>";  
                        echo "<p class='product-des-title' id='include-acc'>Included Accessories</p>";
                            echo "<div class='included_accessories' id='include-acc-des'>"; 
                                echo $json_value['included_accessories'];
                            echo "</div>";
                        echo "</div>";
                    }

                    if($json_value['warranty_info']) 
                    {
                       echo "<div class='product-des '>";     
                        echo "<p class='product-des-title'>Warranty Information</p>";
                        echo "<p class='pro-des-text'>". $json_value['warranty_info'] ."</p>";

                        echo "</div>"; 
                    }
                    
                 echo "</div>";

               
                   
            }
            echo "</div>";
            ?>
           </div>   
        </div> 
    
    </div>
        

    </div><!-- .entry-content -->


</main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->
</div><!-- .wrap -->
<?php
get_footer();