<div class="cart-address">
<small><?php echo $address['firstname'] . ' ' . $address['lastname']; ?></small><br/>
<small><?php echo $address['address_1']; ?>, <?php echo $address['address_2']; ?></small><br/>
<small><?php echo $address['city']; ?>, <?php echo $address['country_id']; ?></small><br/>
<a pid="<?php echo $product_id; ?>" class="_ua" id="<?php echo $address['address_id']; ?>" href="#<?php echo $address['address_id']; ?>">Change Address</a>
</div>
