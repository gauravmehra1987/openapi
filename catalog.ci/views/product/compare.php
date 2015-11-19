<div id="cont">

    <div class="breadcrumb" style="text-transform: capitalize;">
    <a href="<?php echo base_url(); ?>">Home</a> »
     <?php $i=1; foreach($products as $product){ ?>
        <?php echo $product->name; ?> <?php if(count($products) != $i++) echo 'vs'; ?>
     <?php } ?>
</div>
    
    <?php $this->load->view('product/blocks/compare'); ?>
    
    <div class="compare-list">
            <table>
                <thead>
                        <tr class="no-color">
                            <td class="_n">Mobiles to compare</td>
                            <?php foreach($products as $product){ ?>
                                <td><a href="/<?php echo $product->slug; ?>"><?php echo $product->name; ?></a></td>
                             <?php } ?>
                        </tr>

                        <tr class="no-color">
                            <td>Image</td>
                            <?php foreach($products as $product){ ?>
                                <td class="c-image">
                                    <img src="<?php echo $product->largethumb; ?>" />
                                 </td>
                             <?php } ?>
                        </tr>
                        
<!--                         <tr class="no-color">
                            <td>Rating</td>
                             <td><div class="review-rating r0"></div></td>
                             <td><div class="review-rating r10"></div></td>
                             <td><div class="review-rating r3"></div></td>
                             <td><div class="review-rating r1"></div></td>
                        </tr>-->

                        <tr class="hide">
                            <td>Description</td>
                            <?php foreach($products as $product){ ?>
                                <td><?php echo $product->auto_meta; ?></td>
                             <?php } ?>
                        </tr>

                        <tr class="hide">
                            <td>Manufacture</td>
                            <?php foreach($products as $product){ ?>
                                    <td><?php echo $product->manufacture; ?></td>
                             <?php } ?>
                        </tr>
                </thead>
                      <?php foreach ($product_attributes as $group=>$attribute_group) { ?>
                        <thead>
                          <tr>
                            <td class="compare-attribute" colspan="<?php echo count($products) + 1; ?>"><?php echo $attribute_group['name']; ?></td>
                          </tr>
                        </thead>
                        <?php foreach($attribute_group['attribute'] as $key => $attribute) { ?>
                        <tbody>
                          <tr>
                            <td><?php echo $attribute['name']; ?></td>

                            <?php foreach ($products as $product) { ?>
                            <?php if (isset($products[$product->product_id]->attributes[$group][$key])) { ?>
                            <td><?php echo $products[$product->product_id]->attributes[$group][$key]; ?></td>
                            <?php } else { ?>
                            <td></td>
                            <?php } ?>
                            <?php } ?>
                          </tr>
                        </tbody>
                        <?php } ?>
                        <?php } ?>

            </table>
    </div>
</div>
