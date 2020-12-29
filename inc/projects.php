<?php

function amsprojects_function( $slug ) {
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


<div class="wp-block-columns main-content <?= $blockclass; ?>" >

  <?php

  $blockdata = get_sidebaroption();
          
  if (!isset($blockdata['projectsidebar']))
  {        
  ?>
    <div class="wp-block-column left-col col-fit" >
        
        <div class="assetssidebar">
            <div class="searchbox">
                <h4>Search</h4>
                <input type="text" class="searrch-input" name="keyword" id="getproject" onkeyup="fetchproject()"></input>
            </div>
        </div>    
        
    </div>  
  <?php } ?> 
   
  	<!-- Projects -->
  	<div class="projectslisting right-col" >       
	    <div class="right-col-wrap">
	        
	        <?php

	        $arrayResult = get_projectlisting(NULL);
	        $bgcolor = get_option('wpams_button_colour_btn_label');


	        //$projects = get_projectlisting($member_id);
	       /* echo "<pre>";
	        print_r($arrayResult['projects']);
	        echo "</pre>";*/

	        foreach($arrayResult['projects'] as $x_value) 
	        {
	        
	        $synopsis = mb_strimwidth($x_value['synopsis'], 0, 150, '...');
	        	
	        echo "<div class='listview-project'>";
			echo "<div class='assets-list-items'>";
			

			if($x_value['thumbnail'] == NULL || $x_value['thumbnail'] == "")
	          {                                    
	              echo "<div class='product-img'>";

	              echo "<img src=". esc_url( plugins_url( 'assets/img/bg-image.png', dirname(__FILE__) ) ) .">";
	                
	              echo "</div>";
	          }
	          else
	          {
	               echo "<div class='product-img'>";
	                  echo "<img src=".$x_value['thumbnail'].">";
	               echo "</div>";
	          }

			
			echo "<div class='assetsproduct-content'><a href='#'>";
			echo  "<p class='product-title'>".$x_value['name']. " (2005)</p>";
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

				/*echo "<pre>";
				print_r($attributeResult);
				echo "</pre>";*/
			}
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";

	        
	    	}
	        ?>

	        
	    </div> 

	    <div class="projectbutton">
            
            <p class="para"></p>
            <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>   

            <?php
            
            echo "<input type='button' id='seemore' style='background-color: $bgcolor' value='View More'>";
            
            ?>  
        </div>   

	</div> 
  	<!-- End projects -->

</div>
   
</div>



<script type="text/javascript">
jQuery(document).ready(function($) {

   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   $('#inifiniteLoader').hide();

    $('#seemore').click(function(){

     /* var position = $(window).scrollTop();
      var bottom = $(document).height() - $(window).height();*/
        //$('#seemore').hide();
        
        var numItems = jQuery('.productstyle').length;
        var listnumItems = jQuery('.listview-project').length;   
        
        var totalItems = "";
        if(numItems != '')  
        {
          totalItems = numItems;
        }
        else
        {
          totalItems = listnumItems;
        }

        console.log(totalItems);
        console.log(total);


        if (totalItems >= total){
            jQuery('#seemore').hide();
            jQuery(".para").text("No More Projects found.");  
        }else{
            jQuery('#seemore').hide();   
            loadArticle(count);
        }
        count++;
        

        return false;
    });


    function loadArticle(pageNumber){
     //$('a#inifiniteLoader').show();

     /*console.log(amsjs_ajax_url.ajaxurl);
     console.log("hello");*/

     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: { action: 'getprojectonclick_action', page:pageNumber},
       beforeSend: function(){
        // Show image container
            $("#inifiniteLoader").show();
       },
       success: function (html) {
         jQuery('#inifiniteLoader').hide('1000');
         jQuery('.right-col-wrap').append(html);
         jQuery('#seemore').show();
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

add_shortcode('amsproject', 'amsprojects_function');

?>
