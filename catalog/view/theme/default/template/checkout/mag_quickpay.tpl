<?php echo $header; ?>
<?php echo $text_checkout_payment_method = "Step 1: Choose Payment Method"; ?>
<?php echo $text_checkout_confirm = "Step 2: Confirm Order"; ?>
<div id="container" class="container j-container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li>
            <a href="<?php echo $breadcrumb['href']; ?>">
                <?php echo $breadcrumb[ 'text']; ?>
            </a>
        </li>
        <?php } ?>
    </ul>
    <div class="row">
        <?php echo $column_left; ?>
        <?php echo $column_right; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class='col-sm-6' ; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class='col-sm-9' ; ?>
        <?php } else { ?>
        <?php $class='col-sm-12' ; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
            <h1 class="heading-title">
			<?php echo $heading_title; ?>
			</h1>
            <?php echo $content_top; ?>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
						<?php echo $text_checkout_payment_method; ?>
						</h4>
                    </div>
                    <div class="panel-collapse collapse" id="collapse-payment-method">
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
						<?php echo $text_checkout_confirm; ?>
						</h4>
                    </div>
                    <div class="panel-collapse collapse" id="collapse-checkout-confirm">
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
<!--
$(document).ready(function() {
  $.ajax({
    url: 'index.php?route=checkout/payment_method',
    dataType: 'html',
    success: function(html) {
      $('#collapse-payment-method .panel-body').html(html);
      $('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');
      $('a[href=\'#collapse-payment-method\']').trigger('click');
      $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
  $.ajax({
    url: 'index.php?route=checkout/payment_address',
    dataType: 'html',
    success: function(html) {
      $('#collapse-payment-address .panel-body').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// Checkout
    
$(document).delegate('#button-payment-method', 'click', function() {
  $.ajax({
    url: 'index.php?route=checkout/payment_method/save',
    type: 'post',
    data: $('#collapse-payment-method input[type=\'radio\']:checked, #collapse-payment-method input[type=\'checkbox\']:checked, #collapse-payment-method textarea'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-payment-method').button('loading');
    },
    complete: function() {
      $('#button-payment-method').button('reset');
    },
    success: function(json) {
      $('.alert, .text-danger').remove();
      if (json['redirect']) {
        location = json['redirect'];
      } else if (json['error']) {
        if (json['error']['warning']) {
          $('#collapse-payment-method .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      } else {
        $.ajax({
          url: 'index.php?route=checkout/confirm',
          dataType: 'html',
          success: function(html) {
            $('#collapse-checkout-confirm .panel-body').html(html);
            $('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-confirm" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_confirm; ?> <i class="fa fa-caret-down"></i></a>');
            $('a[href=\'#collapse-checkout-confirm\']').trigger('click');
            loadAdresses();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

function loadAdresses() {
  $.ajax({
    url: "index.php?route=account/mag_customer/getAddresses",
    dataType: "json",
    success: function( json ) {
      var addressSelector = $('select[name="address_id"]');
      if ( !$.isEmptyObject(json.addresses) ) {
        //customer has pre-registered adresses
        $.data(document.body, 'addresses', json['addresses']);
        
        var html = '';
        for (var i in json.addresses) {
          var address = json.addresses[i];
          html += '<option value="' + address.address_id + '">' + address.firstname + ' ' + address.lastname + ", " + address.address_1 + ', ' + address.city + ', ' + address.zone + ', ' + address.country + '</option>';
        }
        
        $(addressSelector).html(html);
        
        $(addressSelector).val(json.default_address).trigger('change');
        $('input[name=address_option]').eq(1).attr('checked',true).trigger('change');
      } else {
        //customer has no  addresses
        $('input[name=address_option]').eq(0).attr('checked',true).trigger('change');
      }
    },
    error: function(xhr, textStatus, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}
$("#modal-address").on("hidden", function () {
  loadAddresses();
});
//-->
</script>
<?php echo $footer; ?>