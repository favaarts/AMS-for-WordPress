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

$bgcolor = get_option('wpams_button_colour_btn_label');
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

    <?php
    //Block option
    //$blockdata = get_sidebaroption();
    if (!isset($blockdata['eventsidebar']))
      {
    ?>
    <!-- Sidebar -->
    <div class="wp-block-column left-col" >
        <?php

        $locationArrayResult = get_eventlocation();
        /*echo $locationArrayResult['json']['locations'][0];
        echo "<pre>";
        print_r($locationArrayResult);
        echo "</pre>";*/
        if(!isset($locationArrayResult['error']))
        {
            
              
        ?>

            <div class="searchbox">
                <h4>Search By Programs Name</h4>
                <input type="text" class="searrch-input" name="getevent" id="getevent" onkeyup="fetchevent()"></input>
            </div>

          <div class="othersearch">

            <h4 class="othertitle" style="color: <?=$bgcolor?>">Filter</h4>  
            <div class="alltypeevent">
              <h4>Programs Type</h4>
              <select class='ul-cat-wrap' id='alltypeevent'>
                <option value="Events">Events</option>
                <option value="Workshops">Workshops</option>
                <option value="Classes">Classes</option>
              </select>
            </div>
            
            <div class="allstatus">
              <h4>Programs Status</h4>
              <select class='ul-cat-wrap' id='allstatus'>
                <option value="1">Active</option>
                <option value="2">Cancelled</option>
                <option value="3">Finished</option>
              </select>
            </div>

            <div class="evtlocation">
              <h4>Programs Location</h4>
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
              <input type="button" class="inputsearchbutton" id="searchdata" style="background-color: <?=$bgcolor?>" value="Search">
              <img class="buttonloader" src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>" >
            </div>  

          </div>  
      <?php } ?>    
            
    </div>  
    <!-- End sidebar -->
    <?php } ?>
    
    <div class="categorysearchdata right-col eventpage" >
        <div class="productdetail"></div>
        <div class="right-col-wrap">
            
        

        <?php
            $arrayResult = get_eventlisting(NULL);
            

            global $post;
            $pageid = $post->ID;
            $pageslug = $post->post_name;

            if($blockdata['radio_attr_event'] == "list_view")
        {
                foreach($arrayResult['programs'] as $x_value) 
                { 
                  if(isset($x_value['id']))
                  {

                   $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];  
                //List View
                echo "<div class='listview-events'>";
                  echo "<div class='productstyle-list-items'>";
                     
                      if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                      {                                    
                          echo "<div class='product-img'>";
                          ?>
                          <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/bg-image.png'; ?>">
                          <?php    
                          echo "</div>";
                      }
                      else
                      {
                           echo "<div class='product-img'>";
                              echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                           echo "</div>";
                      }

                      echo "<div class='product-content'>";
                        $date=$x_value['earliest_scheduled_program_date'];
                          if(empty($date))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            echo "<p class='product-date'><span class='datetitle'>Earliest Date: </span>".date('D, M d', strtotime($date))."</P>"; 
                          }
                          echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
                      echo "</div>";
                      
                        
                    echo "</div>";
                echo "</div>";
                //End list view
                   } // End if
                } // End foreach

        
        } // End list view if condition
        else
        { 
          
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
                              $date=$x_value['earliest_scheduled_program_date'];
                                if(empty($date))
                                {
                                  echo "<p>No Date Scheduled</P>";
                                }
                                else
                                {
                                  echo "<p><span class='datetitle'>Earliest Date: </span>".date('D, M d', strtotime($date))."</P>"; 
                                }
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                            echo "</div>";
                              
                            }
                        
                    echo "</div>";
                }
            }
                        
         //End grid view                
        } // End grid view else condition      
          ?>
            
       </div>  
            <input type="hidden" id="inputpageslug" value="<?php echo $pageslug; ?>">
            <input type="hidden" id="inputpageid" value="<?php echo $pageid; ?>">
            <div class="eventbutton">
                <p class="para"></p>
                <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>
                <?php
                if (!isset($blockdata['eventshowbutton']))
                {
                echo "<input type='button' id='seemore' style='background-color: $bgcolor' value='View More'>";
                }
                ?>  
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

   /*On serach ajax call*/

    $('#searchdata').click(function(){
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery('#evtlocation').val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();


      var eventperpg = <?php echo $blockdata['event_pagination']; ?>;
      console.log(eventperpg);

      jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg},
            beforeSend: function(){
            // Show image container
                jQuery(".buttonloader").css("display","initial");
            },
            success: function(data) {
              jQuery('.right-col-wrap').html(data);
              //jQuery('#seemore').hide();
              jQuery('#getevent').val('');
              jQuery(".buttonloader").css("display","none");
              AjaxInitProgram()
            }
        });
    });
    /*End On serach ajax call*/

   

  function AjaxInitProgram() {
    var totalprogram = jQuery("#totalprogram").val();
    total = totalprogram;
    console.log(total);
    if(total >= 10)
    {
      jQuery('#seemore').show();
      jQuery(".para").hide();
    }
  }  
    
   $('#seemore').click(function(){

     /* var position = $(window).scrollTop();
      var bottom = $(document).height() - $(window).height();*/
        //$('#seemore').hide();
        
        var numItems = jQuery('.productstyle').length;
        var listnumItems = jQuery('.listview-events').length;   
        
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
            jQuery(".para").text("No More Events found.");  
        }else{
            jQuery('#seemore').hide();   
            loadArticle(count);
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

     //
     var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery('#evtlocation').val();
     //

     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: { action: 'geteventonclick_action', page:pageNumber, eventperpg:eventperpg, eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslugname: slugvar, pageslugid: slugvarid},
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