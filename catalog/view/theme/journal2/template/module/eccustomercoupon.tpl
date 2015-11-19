<?php
	if($show_only_link):
?>
<h2><span><?php echo $text_coupons;?></span></h2>
<div class="content">
    <ul class="list-unstyled">
      <li><a href="<?php echo $list_coupons_link; ?>"><?php echo $text_view_list_coupons;?></a></li>
    </ul>
</div>
<?php else: ?>
<a href="<?php echo $list_coupons_link; ?>"><?php echo $text_view_list_coupons;?></a>
<?php endif; ?>