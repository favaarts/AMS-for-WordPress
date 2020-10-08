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
            <div class="wp-block-columns main-content main-content-three-col" >
                <div class="categorysearchdata right-col">
                    <div class="eventdetail">
                    <?php
                    global $wp, $wpdb;

                    $alleventid = $wp->query_vars['eventslug'];
                    $arrayevid = explode("-",$alleventid);
                    
                    $arrayResult = get_eventlisting($arrayevid[1]);

                    /*echo "<pre>";
                    print_r($arrayResult['program']);
                    echo "</pre>";*/
                    //echo $arrayResult['program']['description'];
                    ?>    
                        <div class="event-img-sec">
                            <div class="img-sec">
                                <?php
                                if($arrayResult['program']['photo']['photo']['medium']['url'] == NULL || $arrayResult['program']['photo']['photo']['medium']['url'] == "")
                                {  
                                ?>
                                <img src="<?= plugins_url( 'assets/img/bg-image.png', __FILE__ )?>">
                                <?php 
                                }
                                else
                                { 
                                ?>
                                <img src="<?=$arrayResult['program']['photo']['photo']['medium']['url']?>">
                                <?php } ?>
                            </div>
                            <div class="ing-title">
                                <h1><?=$arrayResult['program']['name']?></h1>

                                <?php if(!empty($arrayResult['program']['member_enrollment_price']))
                                { 
                                ?>
                                <div class="enrollment enrtop">
                                    <h3>Member Enrollment Price</h3>
                                    <p>
                                        <?php
                                        if($arrayResult['program']['member_enrollment_price'] == 0)
                                        {
                                            echo "Free";
                                        }
                                        else
                                        {
                                            echo $arrayResult['program']['member_enrollment_price'];
                                        }

                                        ?>
                                        
                                    </p>
                                </div>
                                <?php
                                }
                                if(!empty($arrayResult['program']['enrollment_price']))
                                { 
                                ?>
                                <div class="enrollment">
                                    <h3>Non MemberPrice</h3>
                                    <p>
                                        <?php
                                        if($arrayResult['program']['enrollment_price'] == 0)
                                        {
                                            echo "Free";
                                        }
                                        else
                                        {
                                            echo $arrayResult['program']['enrollment_price'];
                                        }
                                        ?>    
                                    </p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="event-detail-sec">
                            <div class="left-sec">
                                <h1>Join us virtually for budget-friendly recipe inspiration or to cook alongside us from the comfort of your home kitchen!</h1>
                                <h2>About this Event</h2>
                                <div class="text-sec">
                                    <p class="text-italic">
                                        <?php echo $arrayResult['program']['description']; ?>
                                    </p>
                                    <hr>
                                    
                                </div>
                            </div>
                            

                            <div class="right-sec">
                                <div class="date-time-sec">
                                    <?php
                                    //$date = $arrayResult['program']['created_at'];
                                    $date=date_create($arrayResult['program']['created_at']);
                                    
                                    ?>
                                    <h3>Date And Time</h3>
                                    <p><?php echo date_format($date, 'D, M d, Y'); ?></p>

                                    <?php
                                     $eventtime = get_eventscheduletime($arrayevid[1]);
                                     $keys = array_keys($eventtime['scheduled_program_dates']);
                                     $lastKey = $keys[count($keys)-1];
                                     

                                     $start=date_create($eventtime['scheduled_program_dates'][$lastKey]['start']);
                                     $end=date_create($eventtime['scheduled_program_dates'][$lastKey]['end']);
                                    
                                    ?>
                                    <p><?=date_format($start,"H:i A")?> – <?=date_format($end,"H:i A")?> IST</p>
                                </div>
                                <?php
                                if(!empty($arrayResult['program']['location']))
                                { 
                                ?>
                                <div class="location-sec">
                                    <h3>Location</h3>
                                    <p><?=$arrayResult['program']['location']?></p>
                                </div>
                                <?php
                                }
                                if(!empty($arrayResult['program']['instructors']))
                                {
                                ?>
                                <div class="location-sec">
                                    <h3>Instructors</h3>
                                    <p><?=$arrayResult['program']['instructors']?></p>
                                </div>
                                <?php 
                                } 
                                if(!empty($arrayResult['program']['earlybird_discount']))
                                {     
                                ?>
                                <div class="location-sec">
                                    <h3>Earlybird Discount</h3>
                                    <p><?=$arrayResult['program']['earlybird_discount']?></p>
                                </div>
                                <?php } ?>
                            </div>
                            
                        </div>
                    </div>  
                </div> 
            </div>
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