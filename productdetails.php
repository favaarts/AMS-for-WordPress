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
                                    echo "</div>";
                                    
                                }

                                /*-- html tab --*/
                                if($json_value['included_accessories'] || $json_value['warranty_info'])
                                {     
                                echo "<div class='tabs effect-3'>";
                                    /*-- tab-title --*/
                                if($json_value['included_accessories'])
                                    {    
                                echo "<input type='radio' id='tab-1' name='tab-effect-3' checked='checked'>
                                    <span style='color: $bgcolor;'>Included Accessories</span>";
                                    }

                                if($json_value['warranty_info'])
                                    {    
                                echo "<input type='radio' id='tab-2' name='tab-effect-3'>
                                    <span style='color: $bgcolor;'>Warranty Information</span>";
                                    }
                                
                                if($json_value['included_accessories'] || $json_value['warranty_info'])
                                    {        
                                echo "<div class='line ease' style='background-color: $bgcolor;'></div>";
                                    }
                                    /*-- tab-content --*/

                                echo "<div class='tab-content'>";
                                if($json_value['included_accessories'])
                                    {
                                echo    "<section id='tab-item-1'>
                                            ".$json_value['included_accessories']."
                                        </section>";
                                    }

                                if($json_value['warranty_info'])
                                    {        
                                echo    "<section id='tab-item-2'>
                                            ".$json_value['warranty_info']."
                                        </section>";
                                    }    
                                echo "</div>";
                                echo "</div>";
                                }
                                /*-- html end tab --*/

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