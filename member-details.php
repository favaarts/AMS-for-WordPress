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
                                                        <div class="card-header" id="headingtwo" style='color: <?=$bgcolor;?>'>
                                                          
                                                            <p class="title-tag mb-0"  data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true" aria-controls="collapsetwo">
                                                              Bio
                                                            </p>
                                                         
                                                        </div>

                                                        <div id="collapsetwo" class="collapse show" aria-labelledby="headingtwo" data-parent="#accordionExample">
                                                          <div class="card-body-wrap">
                                                            <p><?= getDataOrDash($member["bio_link"]) ?></p>
                                                          </div>
                                                        </div>
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
                                                        <strong>Address:</strong> <?= getDataOrDash($member["address"]) ?>
                                                        <br>
                                                        <strong>Member since:</strong>
                                                        <?php
                                                            $join_date = strtotime($member['created_at']);
                                                            $newformat = date('M Y', $join_date);
                                                            echo $newformat;
                                                        ?>
                                                    </p>
                                                </div>
                                                 
                                                <div class="tab-wraps">
                                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                                        <!-- <li class="nav-item">
                                                        <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Photos</a>
                                                        </li> -->
                                                        <li class="nav-item">
                                                        <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Filmography</a>
                                                        </li>
                                                        <li class="nav-item">
                                                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Downloads </a>
                                                        </li>
                                                        <li class="nav-item">
                                                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-project" role="tab" aria-controls="pills-contact" aria-selected="false">Projects</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="pills-tabContent">
                                                      <!-- <div class="tab-pane fade " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                        <div class="row">
                                                            
                                                        </div>
                                                      </div> -->
                                                      <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it t
                                                        </p>
                                                      </div>
                                                      <div class="tab-pane tabdownload fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
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
                                                      </div>
                                                      <div class="tab-pane fade" id="pills-project" role="tabpanel" aria-labelledby="pills-contact-tab">
                                                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it t</p>
                                                      </div>

                                                    </div>
                                                </div>
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

<?php
get_footer();