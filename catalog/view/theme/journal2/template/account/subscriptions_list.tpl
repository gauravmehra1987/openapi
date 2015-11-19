<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?> order-list">
      <h1 class="heading-title"><?php echo $heading_title; ?></h1>
	  <p><?php echo $text_note; ?></p><p>&nbsp;</p>
      <?php echo $content_top; ?>
      <?php if ($order_products) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover list">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_subscription_number; ?></td>
              <td class="text-left"><?php echo $column_magazine; ?></td>
              <td class="text-left"><?php echo $column_start_issue; ?></td>
              <td class="text-right"><?php echo $column_exp_date; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php $num=1; foreach ($order_products as $product) { ?>
            <tr>
              <td class="text-left"><?php echo $num; ?></td>
              <td class="text-left"><?php echo $product['name']; ?></td>
              <td class="text-left"><?php echo $product['start_issue']; ?></td>
              <td class="text-right"><?php echo $product['exp_date']; ?></td>
              <td class="text-right"><a style="display: none" href="" data-toggle="tooltip" title="<?php echo $btn_change_address; ?>" class="button button_green"><?php echo $btn_change_address; ?></a> <a href="<?php echo $product['renew_link']; ?>" data-toggle="tooltip" title="<?php echo $btn_renew; ?>" class="button"><?php echo $btn_renew; ?></a> <a href="<?php echo $product['gift_link']; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $btn_give_gift; ?>" class="button"><?php echo $btn_give_gift; ?></a></td>
            </tr>
            <?php $num++; } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary button"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>