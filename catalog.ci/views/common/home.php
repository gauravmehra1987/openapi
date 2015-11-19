<style type="text/css">
    .xX{ float: left; width: 100%; padding: 3x;}
    .xX ul li{list-style: none; float: left; width: 150px; height: 150px;}
    .xX ul{padding-top: 10px;}
    .hD{border-bottom:2px; font-size: 16px; padding-bottom: 3px; }
    .ctr{display: table;text-align: center; padding: 2px 0}
    #hd .menu{ display:none!important;}
</style>
  <?php add_stylesheet(array('css/jquery.bxslider')); ?>
  <?php add_scripts(array('static/js/jquery.bxslider.min')); ?>

            <script type="text/javascript">
               $(document).ready(function(){
                  $('.bxslider').bxSlider({mode:'fade',speed:5000,delay:5000,auto:'true',controls:false, pager:false});
                });
            </script>
               <!-- start sS -->
                <div id="sS">                    
                    <ul class="bxslider">
                        
                        <li><img src="<?php img_src('banner.jpg'); ?>"/></li>
                        <li><img src="<?php img_src('banner.jpg'); ?>"/></li>

                    </ul>                    
                    <form method="post" action="<?php echo site_url('search'); ?>">
                        <span class="drop hide"></span>
                        <div class="select-menu">
                            <div class="checkbox">
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            <span class="clr"></span> 
                            
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            <span class="clr"></span> 
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            <span class="clr"></span> 
                            <input id="check1" type="checkbox" name="check" value="check1"/>  
                            <label for="check1">Checkbox No. 1</label>
                            <span class="clr"></span>                            
                            <input id="check2" type="checkbox" name="check" value="check2"/>  
                            <label for="check2">Checkbox No. 2</label>
                            
                            </div>
                            
                        </div>
                        <input type="text" class="tf" name="q" value="" placeholder="Search here"/>
                        <input type="submit" value="search" class="s-btn"/>
		    </form>

                 </div>
<div id="cont">   
       
    <div class="box">
        <h2 class="bh"><span>Popular Mobiles</span></h2>     
        <div class="bp hbp"> 
            <ul>
            <?php foreach($featured_products as $product): ?>
            <li>
                <div class="img1"><a href="<?php echo site_url($product->slug); ?>"><img title="<?php echo $product->name; ?>" src="<?php echo $product->image; ?>" width="100" height="125" /></a></div>
                <div class="name"><a href="<?php echo site_url($product->slug); ?>"><?php echo $product->name; ?></a></div>
             </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    
    <div class="box">
        <h2 class="bh"><span>Recently Launched Mobiles</span> </h2>        
        <div class="bp hbp">
        <ul>
        <?php foreach($latest_products as $product): ?>
        <li>
            <div class="img1"><a href="<?php echo site_url($product->slug); ?>"><img title="<?php echo $product->name; ?>" src="<?php echo $product->image; ?>" width="100" height="125" /></a></div>
            <div class="name"><a href="<?php echo site_url($product->slug); ?>"><?php echo $product->name; ?></a></div>
        </li>
        <?php endforeach; ?>
        </ul>
        </div>
    </div>

</div>
               
               <script type="text/javascript">
                   var ajax='<?php echo site_url('ajax/pro'); ?>';
                  
                   $('input[name=q]').autocomplete({delay: 400,
                       source:function(request, response){
                           $.ajax({
                            url: ajax,
                            data:{action:'_auto',_s:request.term},
                            dataType: 'json',
                            method:'post',
                            success: function(json) {		
                                if(json.p)
                                    response($.map(json.p, function(item) {
                                            return {
                                                    label: item.name,
                                                    value: item.name
                                            }
                                    }));
                            }
                       });
                            
                       },
                        select: function(event, ui) {
//                                console.log(ui.item.label);
//                                return false;
                        },
                      focus: function(event, ui) {
                            return false;
                      }
                   })
           </script>