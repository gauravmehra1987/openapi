<div class="sf-price box" data-min-price="<?php echo $min_price; ?>" data-max-price="<?php echo $max_price; ?>">
    <div class="box-heading"><?php echo $this->journal2->settings->get('filter_price_text', $text_price); ?></div>
    <div class="box-content">
        <section class="slider" data-min-value="<?php echo $min_price; ?>" data-max-value="<?php echo $max_price; ?>"></section>
    </div>
</div>