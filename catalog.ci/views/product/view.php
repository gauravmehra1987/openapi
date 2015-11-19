<!-- start cont  -->
<div id="cont">               
<div class="breadcrumb">
    <a href="<?php echo base_url(); ?>">Home</a> » <a href="<?php echo site_url('mobile/'.$cat->slug); ?>"><?php echo $cat->name; ?></a> » <?php echo $product->name; ?></div>
<?php $this->load->view('product/blocks/compare'); ?>
<div class="product-info" style="float:left">
    <div class="left"> 
        <div class="image-wrap">
        <a title="" rel="<?php echo $product->name; ?>" href="#">
            <img id="image" alt="<?php echo $product->name; ?>" class="large" title="<?php echo $product->name; ?>" src="<?php echo $product->largethumb; ?>"/>
        </a>
        <ul id="thumblist">
            <?php  foreach($product->images as $image): ?>
            <li><a title="<?php echo $product->name; ?>" href="<?php echo $image['lthumb']; ?>" ><img  title="<?php echo $product->name; ?>" src="<?php echo $image['thumb']; ?>"/></a></li>
            <?php endforeach; ?>
        </ul>
        </div>
        <div class="desc">
        <h2 class="bh"><span>Description:</span></h2>
        <p><?php echo $product->auto_meta; ?></p>
        </div>

        
   <div class="hide">
        <div class="review-ratings">
        <div class="col-3">
        <div class="rt-count"><?php echo $total_reviews; ?> reviews</div>
        <ul>
        <?php foreach($review_stat as $key=>$stat): ?>
            <li class="r-count">
                <a title="" href="#">
                <span class="counter-label"><?php echo $key; ?></span>
                <span style="height:17px;width:92px;background-color:#ececec;float:left;" class="counter-back">
                        <span style="width: <?php if($stat){ echo $stat['average']; }else echo "0"; ?>%;height:17px;background-color:#fddb5a;float:
                left;" class="counter-bar">  </span></span>
                 <span style="margin-left:5px;" class="counter-count"><?php if($stat){ echo $stat['count']; }else echo "0"; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
        </div>
        <div class="col-3">
            <div class="ar">Average Rating</div>
            <div class="ui-rate"></div>
<!--            <span>3.6 out of 5 stars</span>-->
            
            <a class="_wr button"><span>write review »</span></a>
           
        </div>
        </div>   
        <div class="ratings">
            
            <span class="success"></span>
            <h2 class="rh hide">Latest Reviews <?php echo $product->name; ?></h2> 
            
            <a class="_wr button" href="#"><span>write review »</span></a>
            
            <div id="reviews"> </div>            
        </div>	
   </div>
        <?php if($similarScreen): ?>
        <div class="box bp smp">
            <h2 class="bh"><span>Phones with</span>Similar Screen Size</h2>
            <ul>
                <?php foreach($similarScreen as $item): ?>
                    <li style="float: left; list-style: none outside none;" id="ls1">    
                    <span class="img"><a href="<?php echo site_url($item->slug); ?>"><img src="<?php echo $item->image; ?>"></a></span>                
                    <div class="info"><div class="name"><a href="<?php echo site_url($item->slug); ?>"><?php echo $item->name; ?></a></div></div>     
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if($similarOs): ?>
        <div class="box bp smp">
            <h2 class="bh"><span>Phones With</span>Similar OS</h2>
            <ul>
                <?php foreach($similarOs as $item): ?>
                    <li style="float: left; list-style: none outside none;" id="ls1">    
                    <span class="img"><a href="<?php echo site_url($item->slug); ?>"><img src="<?php echo $item->image; ?>"></a></span>                
                    <div class="info"><div class="name"><a href="<?php echo site_url($item->slug); ?>"><?php echo $item->name; ?></a></div></div>     
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
         <?php endif; ?>
        
        <?php if($latest): ?>
        <div class="box bp smp">
            <h2 class="bh"><span>Latest</span>Phones</h2>
            <ul>
                <?php foreach($latest as $item): ?>
                    <li style="float: left; list-style: none outside none;" id="ls1">    
                    <span class="img"><a href="<?php echo site_url($item->slug); ?>"><img src="<?php echo $item->image; ?>"></a></span>                
                    <div class="info"><div class="name"><a href="<?php echo site_url($item->slug); ?>"><?php echo $item->name; ?></a></div></div>     
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
         <?php endif; ?>
    </div>


    <div class="right">
     <h1><?php echo $product->name; ?></h1>
     <h2 class="rh">Your Rating</h2>
      <div class="ui-rate"></div>
      <div class="ui-rstat">
          <?php if($rating['total']): echo $rating['avg']; ?> - User rating based on <?php echo $rating['total'] . ' rating'; endif; ?>
      </div>
      <br/>
    
     <table class="specs">
          <?php  foreach($product->attributes as $groups): ?>
         <tr><td class="title" colspan="2"><?php echo $groups['name']; ?></td></tr>
         <?php if($groups['attribute']): foreach($groups['attribute'] as $attribute): ?>
         <tr><td><?php echo $attribute['name']; ?></td><td><?php echo $attribute['text']; ?></td></tr>
         <?php endforeach; endif; ?>
         <?php endforeach; ?>
     </table>
    </div>
</div>
                 
<div class="wrbox modal">
<span class="b-close">X</span>
             
 <form method="post" id="post-review">
     <span class="loading">Please wait...</span>
     <h2>Give your review and ratings points to this product.</h2>
     <table id="user-review-form">
        <tbody>
            <tr>
                <td class="">
                    <label>Name:</label>
                </td>
                <td class="user-input">
                    <input type="text" value="" class="txt" name="name" id="title" size="40">
                    <div class="info">(Your full name : eg. Gaurav Mehra)</div>
                </td>
            </tr>
            <tr>
            <td class="">
                <label>Review Title:</label>
            </td>
            <td class="user-input">
                <input type="text" value="" class="txt" name="title" id="title" size="40">
                <div class="info">(Maximum 20 words)</div>
            </td>
            </tr>
        <tr>
            <td class="lbl boldtext valign-top">

                <label for="review_text" class="lastUnit fk-label">
                    <div class="fk-review-steps fk-review-step2 unit"></div>
                    Your Review:
                </label>

            </td>
            <td class="user-input">
                <div>
               
                    <textarea name="review_text" id="text" cols="" rows=""></textarea>
                     <div class="info">
                            <strong>Please do not include:</strong> HTML, references to other retailers, pricing, personal information, any profane, inflammatory or copyrighted comments, or any copied content.
                      </div>
                    <div id="review_text_help_message" class="help_message lpadding5 info">
                        (Please make sure your review contains at least 100 characters.)
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="lbl boldtext">
                <label class="lastUnit">
                    <div class="fk-review-steps fk-review-step3 unit fk-label"></div>
                    Your Rating:
                </label>
            </td>
            <td class="user-input">
                <input type="radio" value="1" name="rate" />1
                <input type="radio" value="2" name="rate" />2
                <input type="radio" value="3" name="rate" />3
                <input type="radio" value="4" name="rate" />4
                <input type="radio" value="5" name="rate" />5
            </td>
        </tr>
           <tr><td><a href="#" onclick="" id="s" class="btn btn-blue button"><span>Submit</span></a></td></tr>
    </tbody></table>
 </form></div>

</div>
      <script type="text/javascript">
          var ajax='<?php echo site_url('ajax/pro'); ?>';
          $('._wr').click(function(e){ e.preventDefault();
              $('.wrbox').bPopup({
                  //onClose: function() {content.empty();}
              });
          });
          $(document).ready(function(){
              $('#post-review').submit(function(e){
                  e.preventDefault();
                    $.ajax({
                        url: ajax,
                        type: 'post',
                        data:{id:<?php echo $product->product_id; ?>,rate:$('input[name=rate]:checked').val(),action:'write_review','title':$('#title').val(),'text':$('#text').val()},
                        dataType: 'json',
                        beforeSend: function() {
                                $('.loading').show();
                        },	
                        complete: function() {
                                 
                        },			
                        success: function(json) {
                          
                            $('.loading').hide();
                            $('.b-close').trigger('click',true);
                            $('.success').html('Your review has been submitted and wating for approval');
                        }
                    });
                  return false;
              });
              
          });
          var _pagi='1:5';
          $.post(ajax,{pagi:_pagi,action:'fetch_reviews',id:<?php echo $product->product_id; ?>},function(r){ r = $.parseJSON(r);
//            if(r.status){
                $('#reviews').html(r.html);
//            }
          });
          var org=$('#image').attr('src');
          
$('#thumblist li a').click(function(e){ e.preventDefault();
             $('#image').attr('src',$(this).attr('href'));
          });
          
          $(document).ready(function(){
              $('.ui-rate').raty({ 
                  score: '<?php echo $rating['avg']; ?>',
                  number: 5,
                  path: 'static/image/r',
                  click: function(score, evt) {
                        $.post(ajax,{'star':score,action:'_srate',id:<?php echo $product->product_id; ?>},function(r){ r=$.parseJSON(r);
                            $('.ui-rstat').html(r.r.avg+'- User rating based on '+r.r.total+' ratings: ');
                        });
                        $('.ui-rate').raty('readOnly', true);    
                  }                  
              });
          })
          
      </script>

      <script type="text/javascript" src="<?php echo site_url('static/js/jquery.bpopup.min.js'); ?>"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<?php add_scripts(array('static/js/r/rate')); ?>