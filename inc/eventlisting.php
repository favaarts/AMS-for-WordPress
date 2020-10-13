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

<?php

$blockdata = get_sidebaroption();

$gridlayout = $blockdata['radio_attr_event'];

if($gridlayout == "four_col")
{
   $blockclass = 'main-content-four-col';
   $eventperpage = 4;
}
elseif($gridlayout == "two_col")
{
  $blockclass = '';
  $eventperpage = 2;
}
else
{
   $blockclass = 'main-content-three-col';
   $eventperpage = 3;
}

?>

<div class="wp-block-columns main-content <?= $blockclass; ?>" >
   
  <input type="hidden" name="slugurl" id="slugurl" value="<?=$post_slug?>">  

    <!-- Sidebar -->
    <div class="wp-block-column left-col" >
        <?php

        $locationArrayResult = get_eventlocation();
        $bgcolor = get_option('wpams_button_colour_btn_label');
        /*echo $locationArrayResult['json']['locations'][0];
        echo "<pre>";
        print_r($locationArrayResult);
        echo "</pre>";*/
        if(!isset($locationArrayResult['error']))
        {
            
              
        ?>

            <div class="searchbox">
                <h4>Search By Event Name</h4>
                <input type="text" class="searrch-input" name="getevent" id="getevent" onkeyup="fetchevent()"></input>
            </div>

          <div class="othersearch">

            <h4 class="othertitle" style="color: <?=$bgcolor?>">Filter</h4>  
            <div class="alltypeevent">
              <h4>Events Type</h4>
              <select class='ul-cat-wrap' id='alltypeevent'>
                <option value="Events">Events</option>
                <option value="Workshops">Workshops</option>
                <option value="Classes">Classes</option>
              </select>
            </div>
            
            <div class="allstatus">
              <h4>Events Status</h4>
              <select class='ul-cat-wrap' id='allstatus'>
                <option value="1">Active</option>
                <option value="2">Cancelled</option>
                <option value="3">Finished</option>
              </select>
            </div>

            <div class="evtlocation">
              <h4>Events Location</h4>
              <select class='ul-cat-wrap' id='evtlocation'>
                <option value="">All Location</option>
                <?php
                foreach($locationArrayResult['json']['locations'] as $c => $c_value) {
                  echo "<option  value='".$c_value."'>".$c_value."</option>";     
                }
              ?>
              </select>
            </div>
            <div class="searchbutton">
              <input type="button" class="inputsearchbutton" id="searchdata" style="background-color: <?=$bgcolor?>" value="Search" onclick="fetchotherevents()">
            </div>  

          </div>  
      <?php } ?>    
            
    </div>  
    <!-- End sidebar -->

    <div class="categorysearchdata right-col eventpage" >
        <div class="productdetail"></div>
        <div class="right-col-wrap">
            
        

        <?php
            $arrayResult = get_eventlisting(NULL);
            

            global $post;
            $pageid = $post->ID;
            $pageslug = $post->post_name;

            foreach($arrayResult['programs'] as $x_value) { 

                if(isset($x_value['id']))
                {
                    
                    echo "<div class='productstyle eventlayout'>";
                    
                        if(isset($x_value['name']))
                        {
                            $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];


                            if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                            {                                    
                                echo "<div class='product-img-wrap'>";
                                ?>
                                <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/bg-image.png'; ?>">
                                <?php    
                                echo "</div>";
                            }
                            else
                            {
                                 echo "<div class='eventlayout-image'>";
                                    echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                                 echo "</div>";
                            }

                            echo "<div class='eventtitle'>";
                            $date=date_create($arrayResult['program']['created_at']);
                                echo "<p>".date_format($date, 'D, M d')."</P>"; 
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                            echo "</div>";
                              
                            }
                        
                    echo "</div>";
                }
            }
                        
            
          ?>
            
       </div>  
            <input type="hidden" id="inputpageslug" value="<?php echo $pageslug; ?>">
            <input type="hidden" id="inputpageid" value="<?php echo $pageid; ?>">
            <div class="eventbutton">
                <p class="para"></p>
                <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>
                <input type="button" id="seemore" style="background-color: <?=$bgcolor?>" value="See More">  
            </div> 
    </div> 
    
</div>
     
</div>



<script type="text/javascript">
jQuery(document).ready(function($) {

   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   //var total = 22;
   
   $('#inifiniteLoader').hide();
   console.log(total);

   $('#seemore').click(function(){

     /* var position = $(window).scrollTop();
      var bottom = $(document).height() - $(window).height();*/
        //$('#seemore').hide();
        
        var numItems = jQuery('.productstyle').length;  
        console.log(numItems);
        if (numItems >= total){
            jQuery('#seemore').hide();
            jQuery(".para").text("No More Events found.");  
          return false;
        }else{
            jQuery('#seemore').hide();   
          loadArticle(count);
          //
        }
        count++;
        
   });


    function loadArticle(pageNumber){
     //$('a#inifiniteLoader').show();
     var eventperpg = <?php echo $eventperpage; ?>;
     /*console.log(amsjs_ajax_url.ajaxurl);
     console.log("hello");*/
     var slugvar = $('#inputpageslug').val();
     var slugvarid = $('#inputpageid').val();

     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: "action=geteventonclick_action&page="+ pageNumber + "&loop_file=loop&pageslugname="+slugvar+"&pageslugid="+slugvarid+"&eventperpg="+eventperpg,
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
add_shortcode('amseventlisting', 'amseventlisting_function');

?>