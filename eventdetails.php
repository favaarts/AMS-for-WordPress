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

                    $eventid = $wp->query_vars['eventslug'];
                    
                    $arrayResult = get_eventlisting($eventid);

                    /*echo "<pre>";
                    print_r($arrayResult['program']);
                    echo "</pre>";*/
                    //echo $arrayResult['program']['description'];
                    ?>    
                        <div class="event-img-sec">
                            <div class="img-sec">
                                <img src="<?=$arrayResult['program']['photo']['photo']['medium']['url']?>">
                            </div>
                            <div class="ing-title">
                                <h1><?=$arrayResult['program']['name']?></h1>
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
                                    <p>4:30 AM – 5:30 AM IST</p>
                                </div>
                                <div class="location-sec">
                                    <h3>Location</h3>
                                    <p><?=$arrayResult['program']['location']?></p>
                                </div>
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