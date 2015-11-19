<?php foreach ($modules as $module) { ?>
<?php $css = $this->journal2->settings->get('module_' . $module['type']. '_' . $module['module_id']); ?>
<div class="<?php echo $module['type']; ?>" style="<?php echo $css; ?>"><?php echo $module['module']; ?></div>
<?php } ?>