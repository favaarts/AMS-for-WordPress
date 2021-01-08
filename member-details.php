<?php
get_header();  

$bgcolor = get_option('wpams_button_colour_btn_label');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.min.css" integrity="sha512-YHuwZabI2zi0k7c9vtg8dK/63QB0hLvD4thw44dFo/TfBFVVQOqEG9WpviaEpbyvgOIYLXF1n7xDUfU3GDs0sw==" crossorigin="anonymous" />
<div class="container-wrap">
    <div class="site-content">
        <!-- site-content" -->
        <div class="no-sidebar ams-content">
            <div class="wrap1">
                <div id="member-details-area" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <!-- Entry content -->
                        <div class="entry-content">
                            <div class="wp-block-columns main-content main-content-three-col">
                                <div class="categorysearchdata right-col">

                                        <?php

                                        global $wp, $wpdb;

                                        $member_id = $wp->query_vars['member_id'];

                                        $landingurl = get_option('wpams_landing_url_btn_label');
                                        $bgcolor = get_option('wpams_button_colour_btn_label');
                                        $targeturl = get_option('url_window');

                                        $arrayResult = get_members(NULL, $member_id);
                                        $dummy_image = "https://ssl.gstatic.com/images/branding/product/1x/avatar_square_blue_512dp.png";

                                        if (isset($arrayResult['error'])) {
                                            echo "<p class='centertext'>" . $arrayResult['error'] . "</p>";
                                        } elseif ($arrayResult == NULL && $arrayResult == "") {
                                            echo "<p class='centertext'>Something went wrong! Please check subdomain and API key </p>";
                                        } else {
                                            $member = $arrayResult['user'];
                                        ?>
                                        <br> <br>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-3 member-image-section">
                                                <img class="member-image" src="<?= $member['photo'] ?>" onerror='this.src="<?= $dummy_image ?>"'  alt="<?= $member["email"] ?>">
                                                <div class="personal-wrap specializations">
                                                    <div class="title">
                                                        <span style='color: <?=$bgcolor;?>'>Specializations</span>
                                                        <p class="sub-title">Cinematographer</p>
                                                    </div>
                                                </div>    
                                                <div class="accordian-wrap">
                                                    <div class="accordion" id="accordionExample">
                                                      <div class="card">
                                                        <!-- New accordion -->
                                                        <div class="biocontainer">
                                                            <div class="headingtwonewdiv"><span class="title" style='color: <?=$bgcolor;?>'>Bio</span>
                                                            <span class="headicon" style='color: <?=$bgcolor;?>'>-</span>
                                                            </div>
                                                            <div class="content">
                                                                <div class="card-body-wrap">
                                                                <p><?= getDataOrDash($member["bio_link"]) ?></p>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <!-- End New accordion -->

                                                      </div> 
                                                    </div>
                                                </div>

                                                <div class="personal-wrap">
                                                    
                                                    <div class="title">
                                                        <span style='color: <?=$bgcolor;?>'>Contact</span>
                                                      <div class="detailcontact">
                                                        <p><strong>Email: </strong><?php echo $member["email"]; ?></p>    
                                                        <p><strong>Mobile: </strong><?php echo $member["mobile_phone"]; ?></p> 
                                                    <div class="sociallink">    
                                                    <?php
                                                    if($member["website"])
                                                    {
                                                    echo "<a class='sub-title' href=".$member['website']."><img src=".plugins_url( 'assets/img/web.png', __FILE__ )."></a>";
                                                    }
                                                                                                    
                                                    if($member['data']['twitter'])
                                                    {
                                                    echo "<a class='sub-title' href=".$member['data']['twitter'] ."><img src=".plugins_url( 'assets/img/tw.png', __FILE__ )."></a>";
                                                    }

                                                    if($member['data']['instagram'])
                                                    {
                                                    echo "<a class='sub-title' href=".$member['data']['instagram']."><img src=".plugins_url( 'assets/img/insta.png', __FILE__ )."></a>";
                                                    }

                                                    if($member['data']['facebook'])
                                                    {
                                                    echo "<a class='sub-title' href=".getDataOrDash($member['data']['facebook'])."><img src=".plugins_url( 'assets/img/fb.png', __FILE__ )."></a>";
                                                    }
                                                    ?>   
                                                    </div>

                                                      </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-9 member-detail-right">
                                                <div class="name">
                                                    <h3> <?= $member["first_name"] ?> <?= $member["last_name"] ?></h3>
                                                    <p class="basic-info"> 
                                                        <strong>Job Position:</strong> <?= getDataOrDash($member["job_position"]) ?>
                                                        <br>
                                                        <strong>Location:</strong> <?= getDataOrDash($member["city"]) ?>
                                                        <br>
                                                        <strong>Organization:</strong> <?= getDataOrDash($member["organization_or_name"]) ?>
                                                        <br>
                                                        <strong>Member since:</strong>
                                                        <?php
                                                            $join_date = strtotime($member['created_at']);
                                                            $newformat = date('M Y', $join_date);
                                                            echo $newformat;
                                                        ?>
                                                    </p>
                                                </div>
                                                 
                                                 <!-- Start html tab --> 
                                                <div class="event-detail-sec">
                                                <div class="memberdetail">    
                                                <div class="tabs effect-3">
                                                    <input type="radio" id="tab-1" name="tab-effect-3" checked="checked">
                                                    <span style="color: <?=$bgcolor;?>">Filmography</span>
                                                    <input type="radio" id="tab-2" name="tab-effect-3">
                                                    <span style="color: <?=$bgcolor;?>">Downloads</span>
                                                    <input type="radio" id="tab-3" name="tab-effect-3">
                                                    <span style="color: <?=$bgcolor;?>">Projects</span>
                                                    <div class="line ease" style="background-color: <?=$bgcolor;?>"></div>
                                                    <div class="tab-content">
                                                    <section id="tab-item-1">
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it t
                                                        </p>
                                                    </section>
                                                    <section id="tab-item-2" class="tabdownload" style="">
                                                        <div class="downloadimg">
                                                          <?php 
                                                           if($member['file_attachments'][0]['file']['url'])   
                                                           {
                                                            echo "<a target='_blank' href=" .$member['file_attachments'][0]['file']['url'] ."><img src=". plugins_url( 'assets/img/download.png', __FILE__ )."></a>";
                                                           }
                                                           else
                                                           {
                                                             echo "<img src=". plugins_url( 'assets/img/download.png', __FILE__ ).">";
                                                           }
                                                            
                                                           ?> 
                                                        </div>
                                                    </section>
                                                    <section id="tab-item-3" class="memberproject memberprojectlisting" style="">
                                                    <?php
                                                        //echo $member_id;
                                                        $projects = get_projectlisting($member_id);
                                                        /*echo "<pre>";
                                                        print_r($projects);
                                                        echo "</pre>";*/
                                                        foreach($projects['projects'] as $x_value) 
                                                {

                                                    $synopsis = mb_strimwidth($x_value['synopsis'], 0, 150, '...');
                                                        
                                                    echo "<div class='listview-project'>";
                                                    echo "<div class='assets-list-items'>";
                                                    

                                                    if($x_value['thumbnail'] == NULL || $x_value['thumbnail'] == "")
                                                      {                                    
                                                          echo "<div class='product-img'>";
                                                          
                                                          echo "<img src=". plugins_url( '../assets/img/bg-image.png', __FILE__ ) .">";
                                                            
                                                          echo "</div>";
                                                      }
                                                      else
                                                      {
                                                           echo "<div class='product-img'>";
                                                              echo "<div class='productthumb'>";  
                                                              echo "<img src=".$x_value['thumbnail'].">";
                                                              echo "</div>";
                                                           echo "</div>";
                                                      }

                                                    
                                                    echo "<div class='assetsproduct-content'><a href='javascript:void(0)'>";
                                                    echo  "<p class='product-title'> ". $x_value['name'] ;
                                                    if($x_value['completed_year'])
                                                    {
                                                      echo " (".$x_value['completed_year'].")";
                                                    }
                                                    echo "</p>";
                                                    echo  "</a>";
                                                    echo "<div class='assetsprice'>";
                                                    echo    "<p class='memberprice'><strong>Created By</strong> - ". $x_value['creator']. "</p>";

                                                    if($synopsis != NULL)
                                                    {
                                                    echo "<p class='price-non-mem'><strong>Synopsis</strong> - ". $synopsis ."</p>";
                                                    }
                                                    else
                                                    {
                                                        $attributeResult = get_projectattributes($x_value['id']);
                                                        if($attributeResult['project_attributes'][0]['value'] != NULL)
                                                        {

                                                        echo "<p class='price-non-mem'><strong>".$attributeResult['project_attributes'][0]['project_attribute_type_name']."</strong> - ". $attributeResult['project_attributes'][0]['value'] ."</p>";
                                                        }
                                                    }
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                }
                                                ?>
                                                    </section>
                                                    </div>
                                                </div>
                                                </div>
                                                </div>    
                                                <!-- end html tab -->   
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        ?>

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

jQuery(".headingtwonewdiv").click(function () {

    $header = jQuery(this);
    $headicon = jQuery(".headicon");
    
    $contentabc = $header.next();
    
    $contentabc.slideToggle(500, function () {

        $headicon.text(function () {
            return $contentabc.is(":visible") ? "-" : "+";
        });
    });

});


</script> 
<?php
get_footer();