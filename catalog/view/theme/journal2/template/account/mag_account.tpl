<?php echo $header; ?>
<style>
.account-summary{position:relative;margin:0 12px;margin-bottom:20px}
.account-summary img{padding:0px 13px;position:absolute;top:-5px;}
.account-summary p{margin-top:0px}
.account-summary ul{margin-top:-13px}
.account-summary ul li{list-style:none;border-bottom:1px solid #fff;margin-left:-40px;padding:5px;background-color:#efeeee;padding-left:20px}
.account-summary ul li:last-child{border-bottom:none}
.account-summary-heading{background:#01B3E1;text-transform:uppercase;padding:5px 40px 15px 40px;text-align:left;font-size:14px;line-height:25px;color:#FFF;margin-top:-12px}
.secondary-title {color:black;margin-bottom:20px!important;}
@media screen and (max-width: 760px) {
.account-summary{margin:12px 0!important;}
}
</style>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
   <h2 class="secondary-title">Account Summary</h2>
	<div class="account-summary column menu xs-100 sm-100 md-30 lg-30 xl-30">
			<p> <img src="catalog/view/theme/journal2/image/account/account.png"> </p><h3 class="account-summary-heading"><?php echo $text_my_account; ?></h3><p></p>
			 <ul>
			 	<li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
                                <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>	
			 	<li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>	
			 </ul>	
	</div>
		


	<div class="account-summary column menu xs-100 sm-100 md-30 lg-30 xl-30">
			<p> <img src="catalog/view/theme/journal2/image/account/orders.jpg"> </p><h3 class="account-summary-heading"><?php echo $text_my_orders; ?></h3><p></p>
			<ul>
				<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>	
				<li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>	
			</ul>	
	</div>
		

	<div class="account-summary column menu xs-100 sm-100 md-30 lg-30 xl-30">
			<p> <img src="catalog/view/theme/journal2/image/account/subscriptions.png"> </p><h3 class="account-summary-heading"><?php echo $text_my_newsletter; ?></h3><p></p>
			<ul>
			 	<li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>	
			 </ul>	
	</div>
	
	<div class="account-summary column menu xs-100 sm-100 md-30 lg-30 xl-30" style="clear:left;">
			<p> <img src="catalog/view/theme/journal2/image/account/coupons.jpg"> </p><h3 class="account-summary-heading"> MY COUPONS/CREDIT</h3><p></p>
			<ul>
			 	<li><a href="index.php?route=account/coupons">View Credits</a></li>	
			 </ul>	
	</div>	
		
     <!-- <h2 class="secondary-title"><?php echo $text_my_orders; ?></h2>
      <div class="content my-orders">
      <ul class="list-unstyled">
        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
        <?php if ($reward) { ?>
        <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
        <?php } ?>
        <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
        <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
        <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
      </ul>
      </div> -->
      
      
      <!--<h2 class="secondary-title"><?php echo $text_my_newsletter; ?></h2>
      <div class="content my-newsletter">
      <ul class="list-unstyled">
        <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
      </ul>
      </div>-->    
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>