<?php

function amscategoryequipment_function( $slug ) {
    ob_start();  
    ?>

<div id="category" class="category cat-wrap">

  
<div class="entry-content main-content-wrap">
 
<!-- ======================================================================
notes::
main-content - this class is for two columns.
main-content main-content-three-col - this class is for three columns.
main-content main-content-four-col - this class is for three columns.
======================================================================  -->

<div class="wp-block-columns main-content main-content-three-col" >
    <?php
    global $post;
    $post_slug = $post->post_name;
    ?>
  <input type="hidden" name="slugurl" id="slugurl" value="<?=$post_slug?>">  

  <?php
  //Block option
  $blockdata = get_sidebaroption();
  if (!isset($blockdata['sidebaroption']))
    {
  ?>
    <div class="wp-block-column left-col" >
        <?php

        
        

        $catArrayResult = get_sidebarcategory();

        if(!isset($catArrayResult['error']))
        {
            
              
        ?>

            <div class="searchbox">
                <h4>Search Box</h4>
                <input type="text" class="searrch-input" name="keyword" id="keyword" onkeyup="fetchequipment()"></input>
            </div>

            <?php
                  

            global $post;
           
            $pageslug = $post->post_name;

            

            // Comparison function 
            if(!function_exists('dateCompare'))
            {    
                function dateCompare($element1, $element2) { 
                    
                    return strcmp($element1[1],$element2[1]); 
                }
                usort($catArrayResult['json']['categories'], 'dateCompare');
            }    
 
            

            foreach($catArrayResult as $catjson_value) {
                foreach($catjson_value as $cat => $cat_value) { 
                 
                    if($cat === 'categories') {
                        echo '<h4>Categories</h4>';
                         echo "<ul class='ul-cat-wrap getcategoryid'>";
                        foreach($cat_value as $c => $c_value) {
                            echo "<li>";
                            ?>

                            <a href='<?= site_url('/'.$pageslug.'/'.$c_value[1]); ?>'><?= $c_value[1]?></a>

                            
                            
                            <?php   
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                }
            }

            
        }    

        ?>
    </div>  

  <?php
    // End sidebar block option
    }
  ?>  



    <div class="categorysearchdata right-col" >
        <div class="productdetail"></div>
        <div class="right-col-wrap">
            
        

        <?php
            $arrayResult = get_apirequest($catid,NULL,NULL);
            
            if(isset($arrayResult['error']))
            {   
                 echo "<p class='centertext'>".$arrayResult['error']."</p>";
            } 
            elseif($arrayResult == NULL && $arrayResult == "")
            {
                echo "<p class='centertext'> Something went wrong! Please check subdomain and API key </p>";    
            }
            else
            {
            
                global $post;
                $pageslug = $post->post_name;
                $bgcolor = get_option('wpams_button_colour_btn_label');

                $arrayResult = get_apirequest(NULL,NULL,NULL);

                foreach($arrayResult as $json_value) {

                    foreach($json_value as $x_value) { 

                        if(isset($x_value['id']))
                        {
                            
                            echo "<div class='productstyle'>";
                            
                                if(isset($x_value['name']))
                                {
                                    $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];

                                    echo "<a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$x_value['id'])."'> <p class='product-title 123'>". $assetstitle ."</p> </a>";
                                    
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
                                         echo "<span class='label label-success btn-common' style='background-color: $bgcolor;'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$x_value['id'])."'>Available</a></span>";
                                        else
                                        {
                                          echo "<span class='label label-danger btn-common'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$x_value['id'])."'>Unavailable</a></span>";
                                        }
                                        
                                    echo "</div>";    
                                    }

                                echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";
                                         
                                echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";

                                
                            echo "</div>";
                        }
                    }
                }

            }    
          ?>
       </div>   
    </div> 
    <input type="hidden" id="inputpageslug" value="<?php echo $pageslug; ?>">
</div>
    <?php
    if(!isset($catArrayResult['error']))
    {
    ?>
    <div class="loaderdiv">
        <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>
    <div>    
    <?php } ?>    
</div>



<script type="text/javascript">
jQuery(document).ready(function($) {


   var count = 1;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   console.log(total);

   $(window).scroll(function(){
     if( $(window).scrollTop() + window.innerHeight >= document.body.scrollHeight ) { 
      var numItems = jQuery('.productstyle').length;  
      console.log(numItems);
      if (numItems >= total){
        return false;
      }else{
        loadArticle(count);
      }
      count++;
     }
   });


   function loadArticle(pageNumber){
     $('a#inifiniteLoader').show();

     /*console.log(amsjs_ajax_url.ajaxurl);
     console.log("hello");*/
     var slugvar = $('#inputpageslug').val();

     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: "action=infinitescroll_action&page="+ pageNumber + "&loop_file=loop&slugname="+slugvar,
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

    

});    
</script>

  
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}
add_shortcode('amscategoryequipment', 'amscategoryequipment_function');

?>