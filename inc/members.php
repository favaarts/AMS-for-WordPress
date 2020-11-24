<?php

function members_function($slug)
{
    global $post;
    $post_slug = $post->post_name;
    $blocks = parse_blocks($post->post_content);
    $catArrayResult = get_member_types();
    $member_type = get_query_var("member_type");
    $arrayResult = get_members($member_type, NULL);
    $layout_type = $blocks[0]['attrs']['layout_type'];
    
    ob_start();
?>
    <link rel="stylesheet" href="<?= plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/flexboxgrid.css' ?>" />
    <div class="fx-row">
        <div class="fx-col-xs-12 fx-col-sm-12 fx-col-md-3">
            <div class="search">
                <h4>Search</h4>
                <input type="text" style="width:100%" class="search-input" name="keyword" id="keyword" onkeyup="searchMembers()"></input>
                <br>
                <div class="mobileviewonly" style="margin-top:1rem;">
                    <select class='ul-cat-wrap' id='cagegorydata'>
                        <option value="<?= site_url($post_slug) ?>">All Members</option>
                        <?php
                            foreach ($catArrayResult['member_types'] as $c => $c_value) {
                                echo "<option  value='" . site_url('/' . $post_slug . '/?member_type=' . $c_value['id']) . "'>" . $c_value['name'] . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <br>
                <div class="categories getcategoryid">
                    <h4> Member types </h4>
                    <ul>
                        <li> <a href="<?= site_url('/' . $post_slug ) ?>">All Members</a> </li>
                        <?php
                            foreach ($catArrayResult['member_types'] as $c => $c_value) {
                            ?>
                            <li>
                                <a 
                                    href='<?= site_url('/' . $post_slug . '/?member_type='. $c_value['id']); ?>'
                                    <?php if ($c_value["id"] == $member_type) {
                                        echo "class='active-member-type'";
                                    } 
                                    ?>>
                                    <?= $c_value['name'] ?> </a>
                            </li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="fx-col-xs-12 fx-col-sm-12 fx-col-md-9">

            <?php if($layout_type == "list_view") { ?>
            <div class="members-list">
                <?php
                    $dummy_image = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/bg-image.png';
                    foreach ($arrayResult["users"] as $member) {
                ?>
                    <a class="member-item" href="<?= site_url('/members/'.$member["id"].'/details' )?>">
                        <div class="fx-row member-entry">
                            <div class="fx-col-xs-12 fx-col-sm-3 fx-col-md-3 user-image">
                                <img src="<?= $member['photo'] ?>" onerror='this.src="<?= $dummy_image ?>"'  alt="<?= $member["email"] ?>" style="height:150px; border-radius:5px">
                            </div>
                            <div class="fx-col-xs-12 fx-col-sm-9 fx-col-md-9">
                                <div class="name">
                                    <h5> <?= $member["first_name"] ?> <?= $member["last_name"] ?></h5>
                                    <p> 
                                        <strong>Job Position:</strong> <?= getDataOrDash($member["job_position"]) ?>
                                        <br>
                                        <strong>City:</strong> <?= getDataOrDash($member["city"]) ?>
                                        <br>
                                        <strong>Member since:</strong>
                                        <?php
                                            $join_date = strtotime($member['created_at']);
                                            $newformat = date('M Y', $join_date);
                                            echo $newformat;
                                        ?>
                                        <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php
                    }
                ?>
            </div>

            <?php } else { ?>

            <div class="fx-row members-list <?= $layout_type ?>">
                <?php
                    $dummy_image = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/bg-image.png';
                    $grid_size_class = "fx-col-xs-12 fx-col-sm-6 fx-col-md-4 fx-col-lg-3";
                    if ($layout_type == 'two_fx-col') {
                        $grid_size_class = "fx-col-xs-12 fx-col-sm-6 fx-col-md-6 fx-col-lg-6";
                    } else if($layout_type == 'three') {
                        $grid_size_class = "fx-col-xs-12 fx-col-sm-6 fx-col-md-4 fx-col-lg-4";
                    }
                    foreach ($arrayResult["users"] as $member) {
                ?>
                    <div class="<?= $grid_size_class ?> member-grid-entry">
                        <div class="member">
                            <a class="member-item" href="<?= site_url('/members/'.$member["id"].'/details' )?>">
                                <div class="fx-col-lg-12 member-overlay"></div>
                                <img class="member-image-tile" src="<?= $member['photo'] ?>" onerror='this.src="<?= $dummy_image ?>"'  alt="<?= $member["email"] ?>">
                                <div class="member-details fadeIn-bottom">
                                    <h4 class="member-title"><?= $member["first_name"] ?> <?= $member["last_name"] ?></h3>
                                    <p class="member-text"><?= getDataOrDash($member["job_position"]) ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>

            <?php  } ?>

            <!-- HHHHHHH -->

            <?php if (!isset($catArrayResult['error'])) { ?>
                <div class="loaderdiv">
                    <a id="inifiniteLoader" style="display:none;" data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>"><img src="<?php echo esc_url(plugins_url('assets/img/loader.svg', dirname(__FILE__))) ?>"></a>
                </div>
            <?php } ?>
        </div>
    </div>



<script type="text/javascript">
    let member_type = '<?= $member_type ?>';
    let total = parseInt('<?= $arrayResult['meta']['total_count']; ?>');
    let slugname = '<?= $post_slug ?>';
    jQuery(document).ready(function($) {
        var count = 2;
        console.log(total);

        $(window).scroll(function() {
            if (jQuery('#keyword').val() === "") {
                if ($(window).scrollTop() + window.innerHeight >= document.body.scrollHeight - 400) {
                    var numItems = jQuery('.member-entry').length;
                    if (numItems >= total) {
                        return false;
                    } else {
                        loadArticle(count);
                    }
                    count++;
                }
            }
        });


        function loadArticle(pageNumber) {
            $('a#inifiniteLoader').show();
            var slugvar = $('#inputpageslug').val();

            $.ajax({
                url: amsjs_ajax_url.ajaxurl,
                type: 'POST',
                data: "action=member_ajax&page=" + pageNumber + "&slugname=" + slugname + "&member_type="+member_type,
                beforeSend: function() {
                    // Show image container
                    $("#inifiniteLoader").show();
                },
                success: function(html) {
                    jQuery('#inifiniteLoader').hide('1000');
                    jQuery('.members-list').append(html);
                }
            });
            return false;
        }

        $('#cagegorydata').on('change', function() {
            var url = $(this).val(); // get selected value
            if (url) { // require a URL
                window.location = url; // redirect
            }
            return false;
        });

    });
    function searchMembers()
    {
        event.preventDefault();
        var slugurl = jQuery('#slugurl').val();
        var catid = jQuery('#categoryid').val();

        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'member_ajax', query: jQuery('#keyword').val(), member_type: member_type, slugname: slugname },
            success: function(data) {
                jQuery('.members-list').html(data);
            }
        });
    }
</script>

<?php
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}
    add_shortcode('members_list', 'members_function');
?>
