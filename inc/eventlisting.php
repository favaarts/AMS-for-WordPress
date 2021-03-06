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
if(empty($bgcolor))
{
    $bgcolor = "#337AB7";
}


$gridlayout = $blockdata['radio_attr_event'];

if($blockdata['event_pagination'] != NULL)
{
  $pagination = $blockdata['event_pagination'];
}
else
{
  $pagination = 8;
}

if($gridlayout == "four_col")
{
   $blockclass = 'main-content-four-col';
   $eventperpage = $pagination;
}
elseif($gridlayout == "two_col")
{
  $blockclass = '';
  $eventperpage = $pagination;
}
else
{
   $blockclass = 'main-content-three-col';
   $eventperpage = $pagination;
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
                <h4>Search</h4>
                <input type="text" class="searrch-input" name="getevent" id="getevent"></input>
            </div>

          <div class="othersearch">

           <!--<h4 class="othertitle" style="color: <?=$bgcolor?>">Filter By</h4></br>-->

            <div class="alltypeevent">
              <h4>Type</h4>
              <select class='ul-cat-wrap' id='alltypeevent'>
                <option value="All">All Programs</option>
                <option value="Events">Events</option>
                <option value="Workshops">Workshops</option>
                <option value="Classes">Classes</option>
              </select>
            </div>
            
            <div class="allstatus">
              <h4>Status</h4>
              <select class='ul-cat-wrap' id='allstatus'>
                <option value="1">Active</option>
                <option value="2">Cancelled</option>
                <option value="3">Finished</option>
              </select>
            </div>

            <div class="evtlocation">
              <h4>Location</h4>
              <select class='ul-cat-wrap' id='evtlocation'>
                <option value="">All Locations</option>
                <?php
                foreach($locationArrayResult['json']['locations'] as $c => $c_value) {
                  echo "<option  value='".$c_value."'>".$c_value."</option>";     
                }
              ?>
              </select>
            </div>

            <?php
            if (!isset($blockdata['tagsevents']))
            {
            ?>
            <div class="taglabels">
              <h4>Labels</h4>
              <select class='ul-cat-wrap' id='taglabels'>
                <option value="">Select Labels</option>
                <?php 
                $geteventtags = get_eventorganizationtags();
                foreach($geteventtags['organization_tags'] as $c_value) 
                {
                  echo "<option value='".$c_value['name']."'>".$c_value['name']."</option>";
                }  
                ?>
              </select>
            </div>
            <?php 
            } 

            if (isset($blockdata['organizationevents']))
            {
            ?>
            <div class="taglabels">
              <h4>Organizations</h4>
              <select class='ul-cat-wrap' id='organizations'>
                <option value="">Select Organizations</option>
                <?php 
                $geteventtags = get_organizationevents();
                foreach($geteventtags['organizations'] as $c_value) 
                {
                  echo "<option value='".$c_value['id']."'>".$c_value['full_name']."</option>";
                }  
                ?>
              </select>
            </div>
            <?php } ?>
            <div class="searchbutton">
              <!-- <input type="button" class="inputsearchbutton" id="searchdata" style="background-color: <?=$bgcolor?>" value="Search"> -->
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
                     
                      
                        // Check if organization toogle is ON
                        if (isset($blockdata['organizationevents']))
                        {
                          if(empty($x_value['organization_logo']))
                          {
                            echo "<div class='product-img-wrap'>";
                              echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                            echo "</div>";  
                          }
                          else 
                          {
                            echo "<div class='product-img'>";
                              echo "<img class='organizationlogo' src=".$x_value['organization_logo'] .">";
                            echo "</div>";  
                          }
                        }
                        else
                        {
                          if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                          {
                            echo "<div class='product-img-wrap'>";
                              echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                            echo "</div>";  
                          } 
                          else
                          {
                            echo "<div class='product-img'>";
                              echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                            echo "</div>";  
                          }
                        }     
                      
                          
                      /*if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                      {                                    
                          
                      }
                      else
                      {
                           echo "<div class='product-img'>";
                              echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                           echo "</div>";
                      }*/

                      echo "<div class='product-content'>";
                        echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
                          
                          if (!isset($blockdata['displaypastevents']))
                          {
                            $date=$x_value['earliest_scheduled_program_date'];
                          }
                          else
                          {
                            $date=$x_value['upcoming_scheduled_program_date'];
                          }

                          if(empty($date))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            echo "<p class='product-date'><span class='datetitle'><strong>Start Day: </strong> </span>".date('D, M d Y', strtotime($date))."</P>"; 
                          }

                          if($x_value['location'])
                          {
                            echo "<p class='locationname'><strong>Location: </strong>".$x_value['location']."</p>";
                          }
                          
                          //earlybird_cutoff
                          $earlybirddate=$x_value['earlybird_cutoff'];
                          if(empty($earlybirddate))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            echo "<p class='product-date'><span class='datetitle'><strong>Early Bird Registration Deadline: </strong> </span>".date('D, M d Y', strtotime($earlybirddate))."</P>"; 
                          }

                          //drop_cutoff
                          $dropdate=$x_value['drop_cutoff'];
                          if(empty($dropdate))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            echo "<p class='product-date'><span class='datetitle'><strong>Final Registration Deadline: </strong> </span>".date('D, M d Y', strtotime($dropdate))."</P>"; 
                          }
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

                            // Check if organization toogle is ON
                            if (isset($blockdata['organizationevents']))
                            {
                              if(empty($x_value['organization_logo']))
                              {
                                echo "<div class='eventlayout-image'>";
                                  echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                                echo "</div>";
                              }
                              else 
                              {
                                echo "<div class='product-img-wrap'>";
                                  echo "<img class='organizationlogo' src=".$x_value['organization_logo'] .">";
                                echo "</div>";  
                              }
                            }
                            else
                            {
                              if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                              {
                                echo "<div class='product-img-wrap'>";
                                  echo "<img src=".plugin_dir_url( dirname( __FILE__ ) ) ."assets/img/bg-image.png>";
                                echo "</div>";  
                              } 
                              else
                              {
                                echo "<div class='eventlayout-image'>";
                                  echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                                echo "</div>";  
                              }
                            }

                            /*if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                            {                                    
                                
                            }
                            else
                            {
                                 echo "<div class='eventlayout-image'>";
                                    echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                                 echo "</div>";
                            }*/

                            echo "<div class='eventtitle'>";
                              
                              if (!isset($blockdata['displaypastevents']))
                              {
                                $date=$x_value['earliest_scheduled_program_date'];
                              }
                              else
                              {
                                $date=$x_value['upcoming_scheduled_program_date'];
                              }

                                if(empty($date))
                                {
                                  echo "<p>No Date Scheduled</P>";
                                }
                                else
                                {
                                  echo "<p><span class='datetitle'><strong>Earliest Date: </strong> </span>".date('D, M d', strtotime($date))."</P>"; 
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

   /*Dropdown Ajax call*/
   $("#alltypeevent").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = $(this).val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery('#evtlocation').val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

      // Testing
        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'get_eventlocation', eventtype: eventtype, eventstatus: eventstatus},
            success: function(data) {
              jQuery('#evtlocation').html(data);
              //jQuery('#seemore').hide();
            }
        });
      //

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

    $("#allstatus").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = $(this).val();
      var evtlocation = jQuery('#evtlocation').val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();


      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

      // Testing
        jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'get_eventlocation', eventtype: eventtype, eventstatus: eventstatus},
            success: function(data) {
              jQuery('#evtlocation').html(data);
              //jQuery('#seemore').hide();
            }
        });
      //

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

    $("#evtlocation").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = $(this).val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();

      var eventperpg = <?php echo $pagination; ?>;
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
   /* End Dropdown ajax*/

  /*Select Labels*/
    $("#taglabels").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery("#evtlocation").val();
      var taglabels = jQuery(this).val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

      jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg,taglabels: taglabels},
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
    /*End Select Labels*/ 

    /*Select organizations*/
    $("#organizations").change(function() {
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery("#evtlocation").val();
      var taglabels = jQuery("#taglabels").val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();
      var organizations = jQuery('#organizations').val();

      var eventperpg = <?php echo $pagination; ?>;
      console.log(eventperpg);

      jQuery.ajax({
            url: amsjs_ajax_url.ajaxurl,
            type: 'post',
            data: { action: 'searcheventdata_action', eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid,eventperpg: eventperpg,taglabels: taglabels, organizations: organizations},
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
    /*End Select organizations*/

  // On keyup
  $("#getevent").keyup(function(){

    count = 2;     
    event.preventDefault();

    var getevent = jQuery('#getevent').val();
    var eventtype = jQuery('#alltypeevent').val();
    var eventstatus = jQuery('#allstatus').val();
    var evtlocation = jQuery("#evtlocation").val();
    var taglabels = jQuery("#taglabels").val();
    var pageslug = jQuery('#inputpageslug').val();
    var pageid = jQuery('#inputpageid').val();
    var organizations = jQuery('#organizations').val();

    var eventperpg = <?php echo $pagination; ?>;
    console.log(eventperpg);

    jQuery.ajax({
        url: amsjs_ajax_url.ajaxurl,
        type: 'post',
        data: { action: 'searcheventdata_action', getevent: getevent, organizations: organizations, eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslug: pageslug, pageid: pageid, eventperpg: eventperpg},
        success: function(data) {
          
          jQuery('.right-col-wrap').html(data);
          jQuery('#seemore').hide();
        }
    });

  });
  // End keyup

    /*On serach ajax call =====================*/
    $('#searchdata').click(function(){
      count = 2;     
      event.preventDefault();
      
      var eventtype = jQuery('#alltypeevent').val();
      var eventstatus = jQuery('#allstatus').val();
      var evtlocation = jQuery('#evtlocation').val();
      var pageslug = jQuery('#inputpageslug').val();
      var pageid = jQuery('#inputpageid').val();


      var eventperpg = <?php echo $pagination; ?>;
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
    /*End On serach ajax call =================*/

   

  function AjaxInitProgram() {
    var totalprogram = jQuery("#totalprogram").val();
    total = totalprogram;
    console.log(total);
    if(total >= 10)
    {
      jQuery('#seemore').show();
      jQuery(".para").hide();
    }
    else
    {
      jQuery('#seemore').hide();
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
      var taglabels = jQuery("#taglabels").val();
      var organizations = jQuery('#organizations').val();
     //

     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: { action: 'geteventonclick_action', page:pageNumber, eventperpg:eventperpg, eventtype: eventtype, eventstatus: eventstatus, evtlocation: evtlocation, pageslugname: slugvar, pageslugid: slugvarid, taglabels: taglabels, organizations: organizations},
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
