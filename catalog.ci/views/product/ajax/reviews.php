<?php if($reviews): ?>
<ul>
    <?php foreach ($reviews as $item): ?>
    <li>
        <a title="<?php echo $item->firstname . ' '. $item->lastname; ?>" href="#">
            <img width="64" height="64" title="<?php echo $item->title; ?>" src="/image/c/?src=../<?php echo $item->image; ?>&w=64&h=64">
        </a>
        <span><?php echo $item->firstname . ' '. $item->lastname; ?> </span>
        <div class="rt s<?php echo $item->rating; ?>"></div>     
         <p class="title"><?php echo $item->title; ?></p>
        <p><?php echo $item->text; ?></p>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; 1-984 ?>