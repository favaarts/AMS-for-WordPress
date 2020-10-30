<?php
function getDataOrDash($data) {
    if (isset($data) & !is_null($data) & $data != "") {
        return $data;
    }
    return "-";
}
get_header();  ?>
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
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-9">
                                                <div class="name">
                                                    <h3> <?= $member["first_name"] ?> <?= $member["last_name"] ?></h3>
                                                    <p> 
                                                        <strong>Job Position:</strong> <?= getDataOrDash($member["job_position"]) ?>
                                                        <br>
                                                        <strong>Organization:</strong> <?= getDataOrDash($member["organization_or_name"]) ?>
                                                        <br>
                                                        <strong>Address:</strong> <?= getDataOrDash($member["address"]) ?>
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
