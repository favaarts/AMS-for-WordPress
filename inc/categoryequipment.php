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
======================================================================  -->

<div class="wp-block-columns main-content main-content-three-col" >
    


    <div class="wp-block-column left-col" >
        <div class="searchbox">
            <h4>Search Box</h4>
            <input type="text" class="searrch-input" name="keyword" id="keyword" onkeyup="fetchequipment()"></input>
        </div>

        <?php

        $catArrayResult = get_sidebarcategory();

        // Comparison function 
        function date_compare($element1, $element2) { 
            return strcmp($element1[1],$element2[1]); 
        }
        // Sort the array  
        usort($catArrayResult['json']['categories'], 'date_compare');

         foreach($catArrayResult as $catjson_value) {
                
                foreach($catjson_value as $cat => $cat_value) { 
                 
                    if($cat === 'categories') {
                        echo '<h4>Categories</h4>';
                         echo "<ul class='ul-cat-wrap getcategoryid'>";
                        foreach($cat_value as $c => $c_value) {
                            echo "<li>";
                            ?>

                            <a href='' data-cateid='<?= $c_value[0] ?>' onclick='return categorydata(<?= $c_value[0] ?>)'><?= $c_value[1]?></a>

                            
                            
                            <?php   
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                    
                }
            }

        ?>
    </div>  




    <div class="categorysearchdata right-col" >
        <div class="productdetail"></div>
        <div class="right-col-wrap">
            
        

         <?php
            
            global $post;
            $pageslug = $post->post_name;

            $arrayResult = get_apirequest(NULL,NULL,NULL);

            foreach($arrayResult as $json_value) {

                foreach($json_value as $x_value) { 

                    if(isset($x_value['id']))
                    {
                        
                        echo "<div class='productstyle'>";
                        
                            if(isset($x_value['name']))
                            {
                                echo "<a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
                                
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
                                    echo "<p><span class='label label-success btn-common'>Available</span></p>";
                                    else
                                    {
                                        echo "<p><span class='label label-danger btn-common'>Unavailable</span></p>";
                                    }
                                    
                                echo "</div>";    
                                }

                                echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";

                                echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";
                             
                                echo "</div>";
                            
                        echo "</div>";
                    }

                }
            }
          ?>
       </div>   
    </div> 
    <input type="hidden" id="inputpageslug" value="<?php echo $pageslug; ?>">
</div>
    <div class="loaderdiv">
        <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['equipment_items_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>
    <div>    
</div>




<?php
function myscript() {
?>
<script type="text/javascript">
jQuery(document).ready(function($) {


   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");


   $(window).scroll(function(){
     if( $(window).scrollTop() + window.innerHeight >= document.body.scrollHeight ) { 
      if (count > total){
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
}
add_action('wp_footer', 'myscript');

?>
    
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}
add_shortcode('amscategoryequipment', 'amscategoryequipment_function');

?>