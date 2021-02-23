<?php

get_header();  ?>

<div class="container-wrap">

<?php

global $wp, $wpdb;
$alleventid = $wp->query_vars['projectslug'];
$arrayevid = explode("-",$alleventid);

//echo $arrayevid[0];
//echo "-";
//echo $arrayevid[1];

$arrayResult = get_projectdetails($arrayevid[0]);

/*echo "<pre>";
print_r($arrayResult);
echo "</pre>";*/
$crewrole = "Crew%20Role";
$attributeCrewResult = get_projectattributes($arrayevid[0],$crewrole);

$photo = "Media%20Type";
$attributePhoto = get_projectattributes($arrayevid[0],$photo);

$longattributes = "Long%20Attributes";
$longAttribute = get_projectattributes($arrayevid[0],$longattributes);
//echo $longAttribute['project_attributes'][0]['value'];
//echo $attributeCrewResult['project_attributes'][0]['value'];
/*echo "<pre>";
print_r($attributeCrewResult);
echo "</pre>";*/
/*echo $attributePhoto['project_attributes'][0]['file_attachment'];
echo "<pre>";
print_r($attributePhoto);
echo "</pre>";*/

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

                                <video poster="<?= plugins_url( 'assets/img/video_poster.jpg', __FILE__ )?>" controls="controls" controlsList="nodownload" width="517" height="310">
                                    <source src="<?php echo $attributePhoto['project_attributes'][0]['file_attachment']; ?>" type="video/mp4">
                                </video>
                                <!-- <img src="http://localhost/wp/wp6/wp-content/plugins/ams-for-wordpress/assets/img/bg-image.png"> -->
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
                                        <h3>Author</h3>
                                        <p><a target='_blank' href='".site_url('/members/'.$arrayResult['project']['user_id'].'/details' )."'>".$arrayResult['project']['creator']."</a></p>
                                    </div>";
                                }

                                if($attributeCrewResult['project_attributes'][0]['value'])
                                {     
                                    echo "<div class='enrollment'>
                                        <h3>Director(s)</h3>
                                        <p>".$attributeCrewResult['project_attributes'][0]['value']."</p>
                                    </div>";
                                }    

                                if($attributeCrewResult['project_attributes'][1]['value'])
                                {
                                    echo "<div class='enrollment'>
                                        <h3>Writer(s)</h3>
                                        <p>".$attributeCrewResult['project_attributes'][1]['value']."</p>
                                    </div>";
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
                                 

                                <div class="videos prospace">
                                    <h3>Videos:</h3>
                                    <div class="text-sec">
                                        <?php
                                        foreach($attributePhoto['project_attributes'] as $x_value) 
                                        {
                                            if($x_value['project_attribute_type_name'] == "Video")
                                            {
                                                echo "<div class='column'>";
                                                echo "<video poster='".plugins_url( 'assets/img/video_poster.jpg', __FILE__ )."' controls='controls' controlsList='nodownload' width='180' height='150'>
                                                    <source src=".$x_value['file_attachment'] ." type='video/mp4'>
                                                </video>";
                                                echo "</div>";
                                            }
                                        }    
                                        ?>
                                        <!-- <div class="column">
                                            <img src="https://www.w3schools.com/howto/img_nature.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">
                                        </div>
                                        <div class="column">
                                            <img src="https://www.w3schools.com/howto/img_snow.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">
                                        </div>
                                        <div class="column">
                                            <img src="https://www.w3schools.com/howto/img_mountains.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">
                                        </div>
                                        <div class="column">
                                            <img src="https://www.w3schools.com/howto/img_lights.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">
                                        </div> -->
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
<?php
get_footer();
