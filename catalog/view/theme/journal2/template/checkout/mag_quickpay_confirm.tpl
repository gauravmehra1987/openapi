<?php if (!isset($redirect)) { ?>
<?php echo str_replace("btn btn-primary", "btn btn-primary button", $payment); ?>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
