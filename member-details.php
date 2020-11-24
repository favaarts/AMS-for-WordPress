<?php
get_header();  ?>
<link rel="stylesheet" href="<?= plugins_url( 'assets/css/flexboxgrid.css', __FILE__ ); ?>"/>
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
                                        $dummy_image = plugins_url( 'assets/img/bg-image.png', __FILE__ );

                                        if (isset($arrayResult['error'])) {
                                            echo "<p class='centertext'>" . $arrayResult['error'] . "</p>";
                                        } elseif ($arrayResult == NULL && $arrayResult == "") {
                                            echo "<p class='centertext'>Something went wrong! Please check subdomain and API key </p>";
                                        } else {
                                            $member = $arrayResult['user'];
                                        ?>
                                        <br> <br>
                                        <div class="fx-row">
                                            <div class="fx-col-xs-12 fx-col-sm-12 fx-col-md-3 member-image-section">
                                                <img class="member-image" src="<?= $member['photo'] ?>" onerror='this.src="<?= $dummy_image ?>"'  alt="<?= $member["email"] ?>">
                                            </div>
                                            <div class="fx-col-xs-12 fx-col-sm-12 fx-col-md-9">
                                                <div class="name">
                                                    <h3> <?= $member["first_name"] ?> <?= $member["last_name"] ?></h3>
                                                    <p> 
                                                        <strong>Job Position:</strong> <?= getDataOrDash($member["job_position"]) ?>
                                                        <br>
                                                        <strong>Organization:</strong> <?= getDataOrDash($member["organization_or_name"]) ?>
                                                        <br>
                                                        <strong>City:</strong> <?= getDataOrDash($member["city"]) ?>
                                                        <br>
                                                        <strong>Bio:</strong> 
                                                        <?php 
                                                            if ( !is_null($member) & $member['bio_link'] != "") {
                                                                echo "<a href='".$member['bio_link']."'>".$member['bio_link']."</a>";
                                                            } else {
                                                                echo "-";
                                                            }
                                                        ?>
                                                        <br>
                                                        <strong>Member since:</strong>
                                                        <?php
                                                            $join_date = strtotime($member['created_at']);
                                                            $newformat = date('M Y', $join_date);
                                                            echo $newformat;
                                                        ?>
                                                    </p>
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
