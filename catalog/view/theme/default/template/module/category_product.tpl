 <div class="container">
 <div class="row wow bounceInUp animated animated" id="filters">
      <ul class="list-unstyled">
    <?php foreach($categories as $cat): ?>
        <li data-filter='.<?php echo $cat['class']; ?>'><?php echo $cat['name']; ?></li>
    <?php endforeach; ?>
      </ul>
  </div>


    <div class="row"> 
      <div class="masonry">
      <?php foreach ($products as $product) { ?>
      <div class="col-lg-3 col-md-3 col-sm-6 <?php echo $product['class']; ?> col-xs-12">
        <div class="product-thumb transition">
          <div class="image"><a href="<?php echo $product['href']; ?>" class="hvr-bounce-in"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
          <div class="caption">
            <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
            
            <?php if ($product['rating']) { ?>
            <div class="rating">
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($product['rating'] < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } ?>
              <?php } ?>
            </div>
            <?php } ?>
            <?php if ($product['price']) { ?>
            <p class="price">
              <?php if (!$product['special']) { ?>
              <?php echo $product['price']; ?>
              <?php } else { ?>
              <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
              <?php } ?>
              <?php if ($product['tax']) { ?>
              <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
              <?php } ?>
            </p>
            <?php } ?>
          </div>
          <div class="button-group">
            <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
          </div>
        </div>
      </div>
      <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://isotope.metafizzy.co/isotope.pkgd.min.js"></script>
<script type="text/javascript">
    var $container = $('.masonry').isotope({
      // options
    });
    // filter items on button click
    $('#filters').on( 'click', 'li', function() {
        $('#filters li').removeClass('active');
        $(this).addClass('active');
      var filterValue = $(this).attr('data-filter');
      $container.isotope({ filter: filterValue });
    });
    $('#filters li:first-child').trigger('click');
</script>
