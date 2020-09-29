<?php

function amseventlisting_function( $slug ) {
    ob_start();  
    ?>

<div id="category" class="category cat-wrap">

  
<div class="entry-content main-content-wrap">
 
<!-- ======================================================================
notes::
main-content - this class is for two columns.
main-content main-content-three-col - this class is for three columns.
main-content main-content-four-col - this class is for four columns.
======================================================================  -->

<div class="wp-block-columns main-content main-content-four-col" >
   
  <input type="hidden" name="slugurl" id="slugurl" value="<?=$post_slug?>">  



    <div class="categorysearchdata right-col" >
        <div class="productdetail"></div>
        <div class="right-col-wrap">
            
        

        <?php
            $arrayResult = get_eventlisting();

            /*echo "<pre>";
            print_r($arrayResult['programs']);
            echo "</pre>";*/

            

            foreach($arrayResult['programs'] as $x_value) { 

                if(isset($x_value['id']))
                {
                    
                    echo "<div class='productstyle'>";
                    
                        if(isset($x_value['name']))
                        {
                            $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];

                            echo "<a href=''> <p class='product-title 123'>". $assetstitle ."</p> </a>";
                            
                              
                            }

                        

                        
                    echo "</div>";
                }
            }
                        
            
          ?>
       </div>   
    </div> 
    
</div>
     
</div>



<script type="text/javascript">
jQuery(document).ready(function($) {


   

});    
</script>

  
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}
add_shortcode('amseventlisting', 'amseventlisting_function');

?>