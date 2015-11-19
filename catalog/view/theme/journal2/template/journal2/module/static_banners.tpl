<div class="box static-banners <?php echo $hide_on_mobile_class; ?> <?php echo isset($gutter_on_class) ? $gutter_on_class : ''; ?>" style="<?php echo isset($css) ? $css : ''; ?>">
    <div>
    <?php if ($title): ?>
    <div class="box-heading"><?php echo $title; ?></div>
    <?php endif; ?>
<?php foreach ($sections as $section): ?>
    <div class="static-banner <?php echo $grid_classes; ?>">
        <?php if ($section['link']): ?>
        <a href="<?php echo $section['link']; ?>" <?php echo $section['target']; ?>> <span class="banner-overlay" style="<?php echo $image_border; ?>; <?php if ($bgcolor): ?> background-color: <?php echo $bgcolor; ?> <?php endif; ?>"><?php echo $icon; ?></span><img style="<?php echo $image_border; ?>" src="<?php echo $section['image']; ?>" width="<?php echo $section['image_width']; ?>" height="<?php echo $section['image_height']; ?>" alt="<?php echo $section['image_title']; ?>" /></a>
        <?php else: ?>
        <img style="<?php echo $image_border; ?>" src="<?php echo $section['image']; ?>" alt="<?php echo $section['image_title']; ?>" width="<?php echo $section['image_width']; ?>" height="<?php echo $section['image_height']; ?>" />
        <?php endif; ?>
    </div>
<?php endforeach; ?>
    </div>
</div>