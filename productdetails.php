<?php

get_header();  ?>

<div class="container-wrap">
    <div class="site-content"> <!-- site-content" -->
     <div class="container no-sidebar ams-content">
      <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        <!-- Entry content -->
        <div class="entry-content">

            <!-- New Asstes Details page -->
            <?php
                global $wp, $wpdb;
                
                $prodictidaray = $wp->query_vars['proname'];
                $prodictid = explode("-",$prodictidaray);

                $ab = $wp->query_vars['category'];
                
                $landingurl = get_option('wpams_landing_url_btn_label');
                $bgcolor = get_option('wpams_button_colour_btn_label');
                $targeturl = get_option('url_window');

                //
                $post = get_post($prodictid[0]);
                $blocks = parse_blocks($post->post_content);
                
                $arrayResult = get_apirequest(NULL,NULL,$prodictid[1]);
            ?>
            <div class="wp-block-columns main-content main-content-three-col">
                <?php
                foreach($arrayResult as $json_value) 
                {

                
                    echo "<div class='product-detail-wrap'>";
                    foreach($arrayResult as $json_value) {
                    
                    echo "<div class='pro-detail-inner'>";
                    
                    echo "<div class='pro-detail-left'>";
                    
                    /*echo "<a href='javascript:history.back()' class='pro-back'><img class='back-img' src='". plugins_url( 'assets/img/back.png', __FILE__ ) ."' >Back</a>"; */
                    
                    if($json_value['photo'] == NULL || $json_value['photo'] == "")
                    {                                    
                    echo "<div class='pro-img'>";
                    echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$json_value['name'].">";
                    echo "</div>";
                    }
                    else
                    {
                    echo "<div class='pro-img'>"; 
                    echo "<img src=".$json_value['photo_medium']." alt='".$json_value['name']."' onerror='this.src=\"".$json_value['photo_small']."\"'>";
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
                    if(!isset($blocks[0]['attrs']['member']) || !isset($blocks[0]['attrs']['nonmember']))
                    {
                    echo "<p >Prices(per day)</p>";
                    }
                    echo "</div>";
                    if (!isset($blocks[0]['attrs']['member']))
                    {
                    echo "<p class='memberprice'>". $json_value['price_types'][0][0] ."</p>";
                    }
                    if (!isset($blocks[0]['attrs']['nonmember']))
                    {
                    echo "<p class='price-non-mem'>". $json_value['price_types'][1][0] ."</p>";
                    }
                    echo "</div>";
                    
                    echo "<div class='available-details'>"; 
                    if($json_value['status_text'] == "Active")
                    {
                    echo "<bR class='d-n'>";
                    echo "<p><span class='label label-success btn-common' style='background-color: $bgcolor;'><a href='$landingurl' target='$targeturl'>Book This Item</a></span></p>";
                    }    
                    else
                    {
                    echo "<p><span class='label label-danger btn-common' disabled>Unavailable</span></p>";
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
                    echo "<spna class='B-text'>".$json_value['insurance_value']."</span>";
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

                }   


                    $priceone = explode("-",$json_value['price_types'][0][0]);
                    $pricetwo = explode("-",$json_value['price_types'][1][0]);

                ?>
                <div class="categorysearchdata right-col">
                    <div class="eventdetail assetsdetail">
                        <div class="event-img-sec">
                            <?php
                                echo"<div class='img-sec'>";
                                if($json_value['photo'] == NULL || $json_value['photo'] == "")
                                {  
                                     echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$json_value['name'].">";
                                }
                                else
                                {
                                    echo "<img src=".$json_value['photo_medium']." alt=".$json_value['name'].">";
                                } 
                                echo "</div>";


                                echo "<div class='ing-title'>
                                    <h1>". $json_value['name'] ."</h1>
                                    <span>". $json_value['category_name'] ."</span>";
                                echo "<div class='enrollment enrtop'>
                                        <h3>Member Price</h3>";
                                        if (!isset($blocks[0]['attrs']['member']))
                                        {
                                            echo "<p>". $priceone[1] ."</p>";
                                        } 
                                      
                                echo "</div>";
                                
                                echo "<div class='enrollment enrtop'>
                                        <h3>Non - Member Price</h3>";
                                        if (!isset($blocks[0]['attrs']['member']))
                                        {
                                            echo "<p>". $pricetwo[2] ."</p>";
                                        } 
                                      
                                echo "</div>";    
                                    
                                echo "</div>";
                            ?>
                            
                        </div>

                        <div class="event-detail-sec">
                            <div class="left-sec">
                                <?php
                                if($json_value['description'])
                                {
                                    echo "<h2 class='infotitle'>Information</h2>";
                                    echo "<div class='text-sec assetsdescription'>";
                                    echo "<p class='text-italic'>". $json_value['description'] ."</p>";
                                    echo "<hr>";
                                    echo "</div>";
                                    
                                }

                                if($json_value['included_accessories'])
                                {
                                    echo "<h2 class='infotitle'>Included Accessories</h2>";
                                    echo "<div class='text-sec assetsinfo'>";
                                        echo $json_value['included_accessories'];
                                    echo "</div>";
                                }

                                if($json_value['warranty_info'])
                                {
                                    echo "<h2 class='infotitle'>Warranty Information</h2>";
                                    echo "<div class='text-sec assetsinfo'>";
                                    echo  $json_value['warranty_info'];
                                    echo "</div>";
                                }

                                ?>
                                
                            </div>
                            

                            <div class="right-sec">
                                
                                <div class="ragister-sec">
                                    <div class="reg-sec">
                                        <?php
                                        if($json_value['status_text'] == "Active")
                                        {
                                        echo "<a href='$landingurl' target='$targeturl' style='background-color: $bgcolor;'>Book This Item</a>";
                                        }    
                                        else
                                        {
                                        echo "<p><span class='label label-danger btn-common' disabled>Unavailable</span></p>";
                                        }
                                        ?>
                                        
                                    </div>
                                
                                </div>
                                
                                <?php
                                    if(isset($json_value['barcode']))
                                    {
                                    echo "<div class='location-sec'>";
                                    echo "<h3>Barcode Number</h3>";
                                    echo "<p>".$json_value['barcode']."</p>";
                                    echo "</div>";
                                    }

                                    if(isset($json_value['serial_number']))
                                    {
                                    echo "<div class='location-sec'>";
                                    echo "<h3>Serial Number</h3>";
                                    echo "<p>".$json_value['serial_number']."</p>";
                                    echo "</div>";
                                    }

                                    if(isset($json_value['insurance_value']))
                                    {
                                    echo "<div class='location-sec'>";
                                    echo "<h3>Serial Number</h3>";
                                    echo "<p>".$json_value['insurance_value']."</p>";
                                    echo "</div>";
                                    }
                                ?>        
                                
                           </div>
                            
                        </div>
                    </div>  
                </div> 
            <?php } ?>
            </div>
            <!-- End asstes details page -->

            
        </div>
        <!-- .entry-content -->


            </main><!-- #main -->
        </div><!-- #primary -->
      </div>  
     </div><!-- .container no-sidebar -->
    </div><!-- .site-content -->
</div>    
<?php
get_footer();