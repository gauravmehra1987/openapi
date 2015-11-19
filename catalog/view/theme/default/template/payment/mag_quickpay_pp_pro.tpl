<?php ?>
<form class="form-horizontal">
  <fieldset id="payment">
    <legend><?php echo $text_credit_card; ?></legend>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-type"><?php echo $entry_cc_type; ?></label>
      <div class="col-sm-10">
        <select name="cc_type" id="input-cc-type" class="form-control">
          <?php foreach ($cards as $card) { ?>
          <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>
      <div class="col-sm-10">
        <input type="text" name="cc_number" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input-cc-number" class="form-control" />
      </div>
    </div>
    <!--hiding optional input-->
    <div class="form-group" style="display:none">
      <label class="col-sm-2 control-label" for="input-cc-start-date"><span data-toggle="tooltip" title="<?php echo $help_start_date; ?>"><?php echo $entry_cc_start_date; ?></span></label>
      <div class="col-sm-3">
        <select name="cc_start_date_month" id="input-cc-start-date" class="form-control">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-3">
        <select name="cc_start_date_year" class="form-control">
          <?php foreach ($year_valid as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?></label>
      <div class="col-sm-6">
        <select name="cc_expire_date_month" id="input-cc-expire-date" class="form-control">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        <select name="cc_expire_date_year" class="form-control">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
      <div class="col-sm-10">
        <input type="text" name="cc_cvv2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-cc-issue"><span data-toggle="tooltip" title="<?php echo $help_issue; ?>"><?php echo $entry_cc_issue; ?></span></label>
      <div class="col-sm-10">
        <input type="text" name="cc_issue" value="" placeholder="<?php echo $entry_cc_issue; ?>" id="input-cc-issue" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-address-id"><span data-toggle="tooltip" title="Billing address has to match the one registered with your credit card">Choose Billing Address</span></label>
      <div class="col-sm-10">
        <select name="address_id" class="form-control">
          <option value="0">
            Select Address
          </option>
        </select>
        <button class="button" id="change-address">New Billing Address</button>
      </div>
    </div>
  </fieldset>
</form>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>

<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $.ajax({
    url: 'index.php?route=payment/mag_pp_pro/send',
    type: 'post',
    data: $('#payment :input'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-confirm').attr('disabled', true);
      $('#payment').before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
      console.log($('#payment :input'));
    },
    complete: function() {
      $('#button-confirm').attr('disabled', false);
      $('.attention').remove();
    },
    success: function(json) {
      if (json['error']) {
        alert(json['error']);
      }

      if (json['success']) {
        location = json['success'];
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

    
$('#change-address').click(function(e){ e.preventDefault();
    
  $.ajax({
    url: 'index.php?route=payment/mag_payment_address',
    dataType: 'html',
    success: function(html) {
      $html = '\
      <div id="modal-address" class="modal"> \
        <div class="modal-dialog">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h4 class="modal-title">Add Address</h4>\
            </div>\
            <div class="modal-body">\
              <div class="panel panel-default">\
                <div class="panel-collapse" id="collapse-payment-address">\
                  <div class="panel-body">'+html+'</div>\
                </div>\
              </div>\
            </div>\
          </div>\
        </div>\
      </div>';
                
      $('#modal-address').remove();
      $('body').append($html);

      $('#modal-address').modal('show');
      $('#collapse-payment-address').slideDown();
      $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Payment Address <i class="fa fa-caret-down"></i></a>');
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});


//--></script>