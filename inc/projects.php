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

    <div class="wp-block-column left-col col-fit" >
        
        <div class="assetssidebar">
            <div class="searchbox">
                <h4>Search</h4>
                <input type="text" class="searrch-input" name="keyword" id="getproject" onkeyup="fetchproject()"></input>
            </div>
        </div>    
        
    </div>  

  	<!-- Projects -->
  	<div class="projectslisting right-col" >       
	    <div class="right-col-wrap">
	        
	        <?php

	        $arrayResult = get_projectlisting(NULL);

	        //$projects = get_projectlisting($member_id);
	       /* echo "<pre>";
	        print_r($arrayResult['projects']);
	        echo "</pre>";*/

	        foreach($arrayResult['projects'] as $x_value) 
	        {
	        
	        $synopsis = mb_strimwidth($x_value['synopsis'], 0, 150, '...');
	        	
	        echo "<div class='listview-assets'>";
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
	</div> 
  	<!-- End projects -->

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

add_shortcode('amsproject', 'amsprojects_function');

?>
