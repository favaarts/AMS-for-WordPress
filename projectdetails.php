<?php

get_header();  ?>

<div class="container-wrap">

<?php

global $wp, $wpdb;
$alleventid = $wp->query_vars['projectslug'];
$arrayevid = explode("-",$alleventid);

$arrayResult = get_projectdetails($arrayevid[0]);

$crewrole = "Crew%20Role";
$attributeCrewResult = get_projectattributes($arrayevid[0],$crewrole);

$photo = "Media%20Type";
$attributePhoto = get_projectattributes($arrayevid[0],$photo);

$longattributes = "Long%20Attributes";
$longAttribute = get_projectattributes($arrayevid[0],$longattributes);

$post = get_post($arrayevid[2]);
$blocks = parse_blocks($post->post_content);

$projectconnectmemberid = $wpdb->get_var('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_content LIKE "%[members_list]%" AND post_parent = 0 AND post_status = "publish"');

// Connect to member
$connectmember = get_post($projectconnectmemberid);
$connectmemberblocks = parse_blocks($connectmember->post_content);

?>


    <div class="site-content"> <!-- site-content" -->
     <div class="container no-sidebar ams-content">
      <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        <!-- Entry content -->
        <div class="entry-content">
            <div class="wp-block-columns main-content main-content-three-col">
                <div class="categorysearchdata right-col">
                    <div class="projectdetail">
                        
                        <div class="project-img-sec">

                            <div class="thumbnail">
                                <img src="<?php echo $arrayResult['project']['thumbnail']; ?>">
                            </div>
                            <div class="img-sec">

                                <video poster="<?= plugins_url( 'assets/img/video_poster.jpg', __FILE__ )?>" controls="controls" controlsList="nodownload" width="517">
                                    <source src="<?php echo $attributePhoto['project_attributes'][0]['file_attachment']; ?>" type="video/mp4">
                                </video>
                            </div>

                            <div class="ing-title">
                                <?php
                                echo  "<h1> ". $arrayResult['project']['name'] ;
                                  if($arrayResult['project']['completed_year'])
                                  {
                                    echo " (".$arrayResult['project']['completed_year'].")";
                                  }
                                  echo "</h1>";
                                
                                if($arrayResult['project']['creator'])
                                {
                                    echo "<div class='enrollment enrtop'>
                                        <h3>Author</h3>";
                                      if ($blocks[0]['attrs']['projecttomember'] && $connectmemberblocks[0]['blockName'] == "wpdams-amsnetwork-member/amsnetwork-block-member")  
                                      {
                                       
                                            echo "<p><a target='_blank' href='".site_url('/members/'.$arrayResult['project']['user_id'].'-'.$projectconnectmemberid.'/details' )."'>".$arrayResult['project']['creator']."</a></p>";
                                        
                                      }
                                      else
                                      {
                                        echo "<p>".$arrayResult['project']['creator']."</p>";
                                      }
                                        
                                    "</div>";
                                }

                                foreach($attributeCrewResult['project_attributes'] as $x_value) 
                                {
                                    if($x_value['project_attribute_type_name'] == "Director")
                                    {
                                        echo "<div class='enrollment'>
                                        <h3>Director(s)</h3>";
                                        if ($blocks[0]['attrs']['projecttomember'] && $connectmemberblocks[0]['blockName'] == "wpdams-amsnetwork-member/amsnetwork-block-member")  
                                        {
                                            echo "<p><a target='_blank' href='".site_url('/members/'.$attributeCrewResult['project_attributes'][0]['value_2'].'-'.$projectconnectmemberid.'/details' )."'>".$attributeCrewResult['project_attributes'][0]['value']."</a></p>";
                                        }
                                        else
                                        {   
                                            echo "<p>".$attributeCrewResult['project_attributes'][0]['value']."</p>";
                                        }
                                        echo "</div>";
                                    }
                                } 

                                foreach($attributeCrewResult['project_attributes'] as $x_value) 
                                {
                                    if($x_value['project_attribute_type_name'] == "Writer")
                                    {
                                        echo "<div class='enrollment'>
                                        <h3>Writer(s)</h3>";
                                        if ($blocks[0]['attrs']['projecttomember'] && $connectmemberblocks[0]['blockName'] == "wpdams-amsnetwork-member/amsnetwork-block-member")  
                                        {
                                            echo "<p><a target='_blank' href='".site_url('/members/'.$x_value['value_2'].'-'.$projectconnectmemberid.'/details' )."'>".$x_value['value']."</a></p>";
                                        }
                                        else
                                        {
                                            echo "<p>".$x_value['value']."</p>";
                                        }
                                        echo "</div>";
                                    }
                                }

                                
                                    
                                ?>
                            </div>
                                
                        </div>

                        <div class="project-detail-sec">
                            <div class="left-sec">
                                <?php
                                if($arrayResult['project']['synopsis'])
                                {
                                    echo "<div class='synopsis prospace'>";
                                    echo "<h3>Synopsis:</h3>";
                                        echo "<div class='text-sec'>";
                                            echo "<p>".$arrayResult['project']['synopsis']."</p>";
                                        echo "</div>";
                                    echo "</div>";
                                }
                                ?>
                                 

                                <div class="videos prospace amsvideos">
                                    <h3>Videos:</h3>
                                    <div class="video-row">
                                        <?php
                                        $i = 1;                                        
                                         foreach($attributePhoto['project_attributes'] as $x_value) 
                                        {
                                            if($x_value['project_attribute_type_name'] == "Video")
                                            {
                                            echo "<div class='video-col'>";
                                                echo "<div class='video-thumb'>";
                                                  echo  "<img src='".plugins_url( 'assets/img/video_poster.jpg', __FILE__ )."'>";
                                                    echo "<a class='open-button' popup-open='popup-".$i."' href='javascript:void(0)'></a>";
                                                echo "</div>";
                                                echo "<div class='popup' popup-name='popup-".$i."'>";
                                                    echo "<div class='popup-content'>";
                                                        echo "<video  width='600' controls>";
                                                            echo "<source src='".$x_value['file_attachment'] ."' type='video/mp4'>";
                                                        echo "</video>";
                                                        echo "<a class='close-button' popup-close='popup-".$i."' href='javascript:void(0)''>x</a>";
                                                    echo "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                            }
                                          $i++;      
                                        }     
                                        ?>

                                    </div>
                                </div>

                                <div class="photos prospace">
                                    <h3>Photos:</h3>
                                    <div class="text-sec">
                                        <?php
                                        foreach($attributePhoto['project_attributes'] as $x_value) 
                                        {
                                            if($x_value['project_attribute_type_name'] == "Photo")
                                            {
                                                echo "<div class='column'>";
                                                echo   "<img src=".$x_value['file_attachment'] ." class='hover-shadow cursor'>";
                                                echo "</div>";
                                            }
                                        }    
                                        ?>
                                    </div>
                                </div>

                                <?php
                                echo "<div class='longattributes prospace'>";
                                echo "<h3>Long Attributes:</h3>";
                                    echo "<div class='text-sec longattrval'>";
                                        echo "<p><strong>Awards/Screenings/Festivals :- </strong>";
                                    foreach($longAttribute['project_attributes'] as $x_value) 
                                    {
                                        if($x_value['project_attribute_type_name'] == "Awards/Screenings/Festivals")
                                        {
                                            echo "<span> ". $x_value['value'] . "  </span>";
                                        }
                                    }
                                        echo "</p>";
                                    echo "</div>";
                                echo "</div>";
                                ?>
                                

                                <div class="crewroles prospace">
                                    <h3>Crew Roles:</h3>
                                        <?php
                                        foreach($attributeCrewResult['project_attributes'] as $x_value) 
                                        {
                                            echo "<div class='rolesdiv'>";
                                                echo "<div class='typename'>".$x_value['project_attribute_type_name'] ."</div> ";
                                                echo "<div class='typevalue'>".$x_value['value'] ."</div> ";
                                            echo "</div>";
                                        }    
                                        ?>
                                </div>

                            </div>
                            

                            <div class="right-sec">
                               
                                <div class="location-sec">
                                    <h3>Short Attributes</h3>
                                </div>
                                <?php
                                $shortattribute = "Short%20Attributes";
                                $shortAttributeResult = get_projectattributes($arrayevid[0],$shortattribute);

                                foreach($shortAttributeResult['project_attributes'] as $x_value) 
                                {
                                    echo "<div class='location-sec'>
                                            <h4>".$x_value['project_attribute_type_name']."</h4>
                                            <p>".$x_value['value']."</p>
                                        </div>";
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

<script type="text/javascript">
    jQuery( document ).ready(function() {

    /**/
    // Open Popup
        jQuery('[popup-open]').on('click', function() {
            var popup_name = jQuery(this).attr('popup-open');
            jQuery('[popup-name="' + popup_name + '"]').fadeIn(300);
        });
     
        // Close Popup
    jQuery('[popup-close]').on('click', function() {
        var popup_name = jQuery(this).attr('popup-close');
     
        jQuery("[popup-name="+popup_name+"]").find('video').get(0).pause();

        jQuery('[popup-name="' + popup_name + '"]').fadeOut(300);
        });
     
        // Close Popup When Click Outside
        jQuery('.popup').on('click', function() {
        var popup_name = jQuery(this).find('[popup-close]').attr('popup-close');
        jQuery("[popup-name="+popup_name+"]").find('video').get(0).pause();
        jQuery('[popup-name="' + popup_name + '"]').fadeOut(300);
        }).children().click(function() {
            return false;
    });

});        
</script>

<?php
get_footer();
