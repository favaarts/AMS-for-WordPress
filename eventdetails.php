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
                    
                    $bgcolor = get_option('wpams_button_colour_btn_label');
                    if(empty($bgcolor))
                    {
                        $bgcolor = "#337AB7";
                    }


                    $arrayResult = get_eventlisting($arrayevid[1]);
                    
                    //
                    $post = get_post($arrayevid[0]);
                    $blocks = parse_blocks($post->post_content);

                    // Register URL
                    if($blocks[0]['attrs']['register_url'])
                    {
                        $registerurl = $blocks[0]['attrs']['register_url'];
                    }
                    else
                    {
                        $apiurl = get_option('wpams_url_btn_label');
                        $registerurl = "https://".$apiurl.".amsnetwork.ca/login";
                    }

                    $eventwindow = $blocks[0]['attrs']['register_urltab'];
                    if(empty($eventwindow))
                    {
                        $eventwindow = "_self";
                    }
                ?>    
                        <div class="event-img-sec">
                            <div class="img-sec">
                                <?php
                                if($arrayResult['program']['photo']['photo']['medium']['url'] == NULL || $arrayResult['program']['photo']['photo']['medium']['url'] == "")
                                {  
                                    // Check if organization toogle is ON
                                  if (isset($blocks[0]['attrs']['organizationevents']))
                                  { 
                                    if(empty($arrayResult['program']['organization_logo']))
                                    {
                                      echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ ) .">";
                                    }
                                    else 
                                    {
                                      echo "<img class='organizationlogo' src=".$arrayResult['program']['organization_logo'] .">";
                                    }

                                  }
                                  else
                                  {
                                    echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ ) .">";
                                  }
                                }
                                else
                                { 
                                ?>
                                <img src="<?=$arrayResult['program']['photo']['photo']['medium']['url']?>">
                                <?php } ?>
                            </div>
                            <div class="ing-title">
                                <?php
                                if($arrayResult['program']['status_string'] == "Cancelled")
                                {
                                    echo "<span class='statusstring'>Event Cancelled</span>";
                                }
                                elseif ($arrayResult['program']['status_string'] == "Finished") {
                                    echo "<span class='statusstring'>Event Finished</span>";
                                }
                                ?>

                                <h1><?=$arrayResult['program']['name']?></h1>

                                <?php if(!empty($arrayResult['program']['member_enrollment_price']))
                                { 
                                    if (!isset($blocks[0]['attrs']['member']))
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
                                            echo "$". $arrayResult['program']['member_enrollment_price'];
                                        }

                                        ?>
                                        
                                    </p>
                                </div>
                                <?php
                                    }
                                }
                                if(!empty($arrayResult['program']['enrollment_price']))
                                {
                                    if (!isset($blocks[0]['attrs']['nonmember']))
                                    { 

                                        if($arrayResult['program']['enrollment_price'] > 0)
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
                                            

                                                echo "$". $arrayResult['program']['enrollment_price'];
                                            
                                        }
                                        ?>    
                                    </p>
                                </div>
                                <?php 
                                        }
                                    }
                                }
                                if(!empty($arrayResult['program']['earlybird_discount']))
                                {
                                    if (!isset($blocks[0]['attrs']['earlybird']))
                                    {
                                 ?>
                                <div class="enrollment">
                                    <h3>Earlybird Discount</h3>
                                    <p>
                                        <?php
                                        if($arrayResult['program']['earlybird_discount'] == 0)
                                        {
                                            echo "Free";
                                        }
                                        else
                                        {
                                            echo "$". $arrayResult['program']['earlybird_discount'];
                                        }
                                        ?>    
                                    </p>
                                </div>
                                <?php 
                                    }
                                } 
                                ?>

                            </div>
                        </div>

                        <div class="event-detail-sec">
                            <div class="left-sec">
                                <h1></h1>
                                <h2>About this Event</h2>
                                <div class="text-sec">
                                    <p class="text-italic">
                                        <?php echo $arrayResult['program']['description']; ?>
                                    </p>
                                </div>
                            </div>
                            

                            <div class="right-sec">
                                <div class="date-time-sec">
                                    <?php
                                    //$date = $arrayResult['program']['created_at'];
                                    $date=date_create($arrayResult['program']['created_at']);
                                    
                                    ?>
                                    <h3>Date And Time</h3>
                                </div>
                                
                                <?php 
                                    $eventtime = get_eventscheduletime($arrayevid[1]);
                                     
                                    if(isset($eventtime['scheduled_program_dates']))
                                    {    
                                        $keys = array_keys($eventtime['scheduled_program_dates']);
                                         $lastKey = $keys[count($keys)-1];
                                         
                                        $total = count($keys);
                                        $start=date_create($eventtime['scheduled_program_dates'][$lastKey]['start']);
                                        $end=date_create($eventtime['scheduled_program_dates'][$lastKey]['end']);

                                        if($total > 1)
                                        {
                                            echo "<div class='ragister-sec'>";
                                                echo "<div class='classevent'>"; 
                                                foreach ($eventtime['scheduled_program_dates'] as $key => $daytime) { 
                                                echo "<div class='daytimediv'>";    
                                                    echo  "<div class='evtdate'>";
                                                            echo "<p>".date('D, M d, Y', strtotime($daytime['start']))."</p>";
                                                    echo  "</div>";
                                                    
                                                    echo "<div class='time'>";
                                                    echo "<p>".date('H:i', strtotime($daytime['start'])). " – ".date('H:i', strtotime($daytime['end'])). "</p>";
                                                    echo "</div>";
                                                echo "</div>";    
                                                }
                                                echo "</div>";
                                            echo   "<br>";
                                            if (!isset($blocks[0]['attrs']['showhideurl']))
                                            {
                                            echo   "<div class='reg-sec 3'>";
                                            echo   "<a href=".$registerurl." style='background-color:".$bgcolor."' target=".$eventwindow.">Register</a>";
                                            echo   "</div>";
                                            }
                                            echo "</div>";
                                        }
                                        else
                                        {
                                           

                                            echo "<div class='ragister-sec'>";
                                            if (!isset($blocks[0]['attrs']['showhideurl']))
                                            {
                                            echo   "<div class='reg-sec 1'>";
                                            echo   "<a href=".$registerurl." target=".$eventwindow." style='background-color:".$bgcolor."'>Register</a>";
                                            echo   "</div>";
                                            }
                                                
                                            echo  "<div class='evtdate'>";
                                                    echo "<p>".date_format($start,"D, M d, Y")."</p>";
                                            echo  "</div>";
                                                
                                            echo "<div class='time'>";
                                            echo "<p>".date_format($start,"H:i"). " – ".date_format($end,"H:i"). "</p>";
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                    }
                                ?>

                                <div class="location-sec">
                                    <h3>Location</h3>
                                    <p>
                                        <?php if(!empty($arrayResult['program']['location']))
                                        {
                                            echo $arrayResult['program']['location'];
                                        }
                                        else
                                        {
                                            echo "No Location Found";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                                if(!empty($arrayResult['program']['instructors']))
                                {
                                ?>
                                <div class="location-sec">
                                    <h3>Instructors</h3>
                                    <p><?=$arrayResult['program']['instructors']?></p>
                                </div>
                                <?php 
                                } 
                                ?>
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
