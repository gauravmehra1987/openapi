        <div class="t-brand">
            <h2 class="bh"><span>Top Brands</span></h2>  
             <a href="<?php echo site_url('mobile/samsung-mobile-phones-price-list'); ?>" title="Samsung"><img src="<?php img_src('Samsung.jpg'); ?>" alt="Samsung"/></a>            
             <a href="<?php echo site_url('mobile/apple-mobile-phones-price-list'); ?>" title="apple"><img src="<?php img_src('apple.jpg'); ?>" alt="apple"/></a>
             <a href="<?php echo site_url('mobile/nokia-mobile-phones-price-list'); ?>" title="nokia"><img src="<?php img_src('nokia.jpg'); ?>" alt="nokia"/></a>
             <a href="<?php echo site_url('mobile/micromax-mobile-phones-price-list'); ?>" title="micromax"><img src="<?php img_src('micromax.jpg'); ?>" alt="micromax"/></a>           
             <a href="<?php echo site_url('mobile/htc-mobile-phones-price-list'); ?>" title="htc"><img src="<?php img_src('HTC.jpg'); ?>" alt="htc"/></a>
             <a href="<?php echo site_url('mobile/lg-mobile-phones-price-list'); ?>" title="lg"><img src="<?php img_src('lg.jpg'); ?>" alt="lg"/></a>
             <a href="<?php echo site_url('mobile/sony-mobile-phones-price-list'); ?>" title="sony"><img src="<?php img_src('sony.jpg'); ?>" alt="sony"/></a>
             <a href="<?php echo site_url('mobile/blackberry-mobile-phones-price-list'); ?>" title="blackberry"><img src="<?php img_src('blackberry.jpg'); ?>" alt="blackberry"/></a>
         </div>


   
</div> 
</div>
<div id="ftr">
        <div class="fc">
              <div class="column">
                  <ul> 
                      <li><a href="<?php echo site_url('contact-us'); ?>">Contact Us</a></li>
                      <li class="hide"> <div class="followus hide">follow us on 
                                <div class="social">
                                  <a title="facebook" href="https://www.facebook.com/priceoye" target="_blank"><img alt="facebook" src="<?php img_src('fb.png'); ?>"/></a>
                                  <a title="twitter" href="https://twitter.com/priceoyecom" target="_blank"><img alt="twitter" src="<?php img_src('twitter.png'); ?>"/></a>     
                                  <a title="gplus" href="https://plus.google.com/108903039774117047704" target="_blank"><img alt="g_plus" src="<?php img_src('g_plus.png'); ?>"/></a>
                                  <a title="pinterest" href="http://www.pinterest.com/priceoye/" target="_blank"><img alt="pinterest" src="<?php img_src('pinterest.png'); ?>"/></a>  
                                </div>  
                    </div>
                      </li>
                   </ul>

                   
              </div> 
              
             <div id="powered"> 
                  
                 <span>Copyright &copy; 2014 by priceoye.com &nbsp;|&nbsp;All Rights Reserved.</span>
                 
             </div>
           </div>
        </div>

        <div class="share-sticky">
              <div class="fb-like" data-href="<?php echo current_url(); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
              <a href="https://twitter.com/share" class="twitter-share-button" data-via="priceoyecom" data-related="priceoyecom" data-dnt="true">Tweet</a> 
              <div class="g-plusone" data-size="medium"></div>
        </div>
<!--        <div class="share-sticky1">
             <div class="fb-like" data-href="http://priceoye.com" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
              <a href="https://twitter.com/share" class="twitter-share-button" data-via="priceoyecom" data-related="priceoyecom" data-dnt="true">Tweet</a> 
              <div class="g-plusone" data-size="medium"></div>
        </div>-->
<script type="text/javascript">
    <?php if(isset($fbconnect_url)): ?>
var fbl='<?php echo $fbconnect_url; ?>';
$('.fba').attr('href',fbl);
<?php endif; ?>
    $('input[type=checkbox]').each(function(){
        var val=$(this).attr('id');
        var html='<label for="'+val+'"><span></span></label>';
        $(this).attr('for',val).after(html);
    });
    
    $('input[type=radio]').each(function(){
        var val=$(this).val();
        var html='<label for="'+val+'"><span></span></label>';
        $(this).attr('id',val).after(html);
    });
    
</script>
<div class="_ld">Loading...</div>
<!-- Place this tag where you want the +1 button to render. -->

<!-- g+1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>
