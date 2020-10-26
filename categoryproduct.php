<?php

get_header();  ?>
<div class="container-wrap">
  <div class="site-content site-main"> <!-- site-content" -->
   <div class="container no-sidebar ams-content">
    <div class="wrap">
      <div id="primary" class="content-area">  <!-- primary" --> 
        <div id="category" class="category cat-wrap"> <!-- category cat-wrap" --> 
          <div class="entry-content main-content-wrap"> <!-- entry-content main-content-wrap" --> 
           <?php
            global $wpdb;
            $url = home_url( $wp->request );
            $parts = explode("/", $url);
            $pageslugnew = $parts[count($parts) - 2];
            ?>
          <input type="hidden" name="slugurl" id="slugurl" value="<?=$pageslugnew?>"> 
          <!-- ======================================================================
          notes::
          main-content - this class is for two columns.
          main-content main-content-three-col - this class is for three columns.
          ======================================================================  -->
          <?php
          $catArrayResult = get_sidebarcategory();
          if(isset($catArrayResult['error']))
          {   
          echo "<p class='centertext'>".$catArrayResult['error']."</p>";
          } 
          elseif($catArrayResult == NULL && $catArrayResult == "")
          {
          echo "<p class='centertext'> Something went wrong! Please check subdomain and API key. </p>";    
          }
          else
          {
                $query = "SELECT ID FROM wp_posts WHERE post_name = '$pageslugnew' ";
                $post_id = $wpdb->get_var($query);
                $post = get_post($post_id);
                $blocks = parse_blocks($post->post_content);
                $blockname = $blocks[0]['attrs'];

                $gridlayout = $blocks[0]['attrs']['radio_attr'];

                if($gridlayout == "four_col")
                {
                   $blockclass = 'main-content-four-col';
                }
                elseif($gridlayout == "two_col")
                {
                  $blockclass = '';
                }
                else
                {
                   $blockclass = 'main-content-three-col';
                }
          ?>
          
              <div class="wp-block-columns main-content <?= $blockclass; ?>" >
              
              <?php
                // Show and view shortcode sidebar block action  
                /*$query = "SELECT ID FROM wp_posts WHERE post_name = '$pageslugnew' ";
                $post_id = $wpdb->get_var($query);
                $post = get_post($post_id);
                $blocks = parse_blocks($post->post_content);
                $blockname = $blocks[0]['attrs'];*/

                // Check if sidebar block "ON" or "OFF"
                if (!isset($blockname['sidebaroption']))
                {
              ?>

              <div class="wp-block-column left-col col-fit" >
                
                <div class="searchbox">
                  <h4>Search Box</h4>
                  <input type="text" class="searrch-input" name="keyword" id="keyword" onkeyup="fetchequipment()"></input>
                </div>
                
                <!-- Mobile view only -->
                <div class="mobileviewonly">
                  <select class='ul-cat-wrap' id='cagegorydata'>
                  
                    <?php

                      if(empty($blocks[0]['attrs']['all_items_url']))
                      {
                        echo "<option value=".site_url($pageslugnew).">All Items</option>";
                      }
                      else
                      {
                        $customurl = site_url($pageslugnew)."/".$blocks[0]['attrs']['all_items_url'];
                        echo "<option value=".$customurl.">All Items</option>";
                      }

                      foreach($catArrayResult['categories'] as $c => $c_value) {
                        echo "<option  value='".site_url('/'.$pageslugnew.'/'.$c_value['name'])."'>".$c_value['name']."</option>";     
                      }

                  ?>
                  </select>
                </div>
                <!-- Mobile view only --> 

                <?php
                global $wp;
                $url = home_url( $wp->request );
                $parts = explode("/", $url);
                $pageslug = $parts[count($parts) - 2];
                
                $post_data = get_page_by_path($pageslug);
                $pageid = $post_data->ID;
                
                // get slug
                $categoryinurl = $wp->query_vars['categoryslug'];
                $category = preg_replace("/[^a-zA-Z]+/", " ", $categoryinurl);
                // End get slug
                
                // Use this function for ascending order
                if(!function_exists('dateCompare'))
                {    
                  function dateCompare($element1, $element2) { 
                      
                      return strcmp($element1['name'],$element2['name']); 
                  }
                  
                  usort($catArrayResult['categories'], 'dateCompare');
                }    
                // End ascending function

                
                  // Get sidebar category
                  
                      echo '<h4>Categories</h4>';
                      echo "<ul class='ul-cat-wrap getcategoryid'>";
                      if(empty($blocks[0]['attrs']['all_items_url']))
                      {
                        echo "<li><a href='".site_url($pageslug)."'>All Items</a></li>";
                      }
                      else
                      {
                        $customurl = site_url($pageslug)."/".$blocks[0]['attrs']['all_items_url'];
                        echo "<li><a href='".$customurl."'>All Items</a></li>";
                      }
                      
                      foreach($catArrayResult['categories'] as $c => $c_value) {
                          if($c_value['bookable_by_admin_only'] != 1)
                          {
                            echo "<li>";
                            ?>
                            <a href='<?= site_url('/'.$pageslug.'/'.$c_value['name']); ?>'><?= $c_value['name']?></a>
            
                            <?php   
                            echo "</li>";
                          }
                      }
                      echo "</ul>";
                          
                  // End get sidebar category
                
                ?>
              </div>  
              
              <?php } 
              // End Show and view shortcode sidebar block action 
              ?>
              
              
              <div class="categorysearchdata right-col" >
              <div class="productdetail"></div>
              <div class="right-col-wrap">
              
              
              
              <?php
              
              
              global $array;
              $arraynew = $catArrayResult['categories'];
              
              function searchForId($category, $array) {
                  foreach($array as $c_value) {
                      if ($c_value['name'] === $category) {
                          return $c_value['id'];
                      }
                  }
                  return null;
              }    
              
              $catid = searchForId($category, $arraynew);
              $bgcolor = get_option('wpams_button_colour_btn_label');
              
              $arrayResult = get_apirequest($catid,NULL,NULL);
              //
              if($blocks[0]['attrs']['radio_attr'] == "list_view")
              {  
                foreach($arrayResult as $json_value) {
                
                    foreach($json_value as $x_value) { 
                
                        if(isset($x_value['id']))
                        {
                            
                            echo "<div class='listview-assets'>";
                            
                              echo   "<div class='assets-list-items'>";
                                  if($x_value['photo'] == NULL || $x_value['photo'] == "")
                                  {                                    
                                      echo "<div class='product-img'>";
                                          echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";
                                       echo "</div>";
                                  }
                                  else
                                  {
                                   echo "<div class='product-img'>";
                                      echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                                   echo "</div>";
                                  }
                                  echo"<div class='assetsproduct-content'>"; 
                                    $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];
                                    echo "<a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                                    
                                    echo"<div class='assetsprice'>" ;
                                    if (!isset($blocks[0]['attrs']['member']))
                                    {     
                                    echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";
                                    }
                                    if(!isset($blocks[0]['attrs']['nonmember']))          
                                    {         
                                    echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";
                                    }
                                    echo"</div>"; 
                                    
                                    if($x_value['status_text'] == "Active")
                                    {  
                                     echo "<span class='assetsproductlabel label-success btn-common' style='background-color: $bgcolor;'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Available</a></span>";
                                    } 
                                    else
                                    {
                                      echo "<span class='label label-danger btn-common'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Unavailable</a></span>";
                                    }
                                    
                                  echo "</div>";
                              echo "</div>";

                            echo "</div>";
                        }
                    }
                }
              }
              else 
              {

                foreach($arrayResult as $json_value) {
                
                    foreach($json_value as $x_value) { 
                
                        if(isset($x_value['id']))
                        {
                            
                            echo "<div class='productstyle'>";
                            
                                if(isset($x_value['name']))
                                {
                                  $assetstitle = (strlen($x_value['name']) > 34) ? substr($x_value['name'],0,34).'..' : $x_value['name'];
                                  
                                    echo "<a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                                    
                                    if($x_value['photo'] == NULL || $x_value['photo'] == "")
                                    {                                    
                                        echo "<div class='product-img-wrap'>";
                                            echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";
                                         echo "</div>";
                                    }
                                    else
                                    {
                                     echo "<div class='product-img-wrap'>";
                                        echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                                     echo "</div>";
                                    }
                
                                    echo "<div class='bottom-fix'>"; 
                                    if($x_value['status_text'] == "Active")
                                        echo "<span class='label label-success btn-common' style='background-color: $bgcolor;'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Available</a></span>";
                                        else
                                        {
                                            echo "<span class='label label-danger btn-common'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Unavailable</a></span>";
                                        }
                                        
                                    echo "</div>";    
                                    }
                                 if (!isset($blocks[0]['attrs']['member']))
                                {
                                echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";
                                 }
                                if (!isset($blocks[0]['attrs']['nonmember']))
                                {          
                                echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";
                                }
                                
                            echo "</div>";
                        }
                    }
                }

              }  
              ?>
              </div>   
              </div> 
              <input type="hidden" id="inputpageslug" value="<?php echo $pageslug; ?>">
              <input type="hidden" id="categoryid" value="<?php echo $catid; ?>">
              <input type="hidden" id="pageurl" value="<?php echo urldecode($url); ?>">
              </div>
              <div class="loaderdiv">
              <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo plugin_dir_url( __FILE__ ).'assets/img/loader.svg' ?>" ></a>
              </div>    
          
          
          <?php } ?>
          </div> <!-- entry-content main-content-wrap" --> 
       </div> <!-- category cat-wrap" --> 
      </div> <!-- primary" --> 
    </div>
   </div>
  </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('a#inifiniteLoader').hide();

  // option selected
  var newurl = $('#pageurl').val();
  jQuery('#cagegorydata').val(newurl).attr('selected','selected');   
  // End option selected
   
   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   console.log(total);

   $(window).scroll(function(){
     if( $(window).scrollTop() + window.innerHeight >= document.body.scrollHeight - 400 ) { 
      var numItems = jQuery('.productstyle').length;
      var numItems = jQuery('.listview-assets').length;   
      console.log(numItems);
      if (numItems >= total){
        return false;
      }else{
        loadArticle(count);
      }
      console.log(count);
      count++;
     }
   });


   function loadArticle(pageNumber){
     $('a#inifiniteLoader').show();

     /*console.log(amsjs_ajax_url.ajaxurl);
     console.log("hello");*/
     var slugvar = $('#inputpageslug').val();
     var catid = $('#categoryid').val();

     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: "action=infinitescroll_action&page="+ pageNumber + "&catid="+catid+"&slugname="+slugvar,
       beforeSend: function(){
        // Show image container
        $("#inifiniteLoader").show();
       },
       success: function (html) {
         jQuery('#inifiniteLoader').hide('1000');
         jQuery('.right-col-wrap').append(html);
       }
     });
     return false;
   }

    $('#cagegorydata').on('change', function () {
        var url = $(this).val(); // get selected value
        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    }); 

});    
</script>
<?php
get_footer();