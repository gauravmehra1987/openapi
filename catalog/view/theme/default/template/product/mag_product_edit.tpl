<?php ?>
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Edit <?php echo $text_delivery_address; ?></h4>
    </div>
    <div class="modal-body">
      <form class="form-vertical" role="form" id="form-delivery-address" action="index.php?route=product/mag_product/validateAddress">
        <input type="hidden" id="hiddenAddress" value="" name="addressId">
        <input type="hidden" id="key" value="<?php echo $key; ?>" name="key">
        
        <input type="radio" <?php if(empty($addresses)) echo "checked=checked"; ?> name="address_option" value="0" /> I want to use a new address
        <br/>
        <input type="radio" <?php if($addresses) echo "checked=checked"; ?> name="address_option" value="1" /> I want to use an existing address
        <br/>

        <fieldset id='fieldset-address-selector'>
          <!--Address Selector-->
          <!--<legend class="select-address" style="display:none;"><?php echo $entry_select_address; ?></legend>-->
          <div class="select-address" class="form-group" style="display:none;">
            <label class="control-label" for="input-address-id"></label>
            <select name="address_id" class="form-control">
              <option value="0">
                <?php echo $text_select; ?>
              </option>
            </select>
          </div>
        </fieldset>
        <fieldset id="fieldset-address" style="display:none;">
          <!--First Name-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname">
              <?php echo $entry_firstname; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
            </div>
          </div>
          <!--Last Name-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname">
              <?php echo $entry_lastname; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
            </div>
          </div>
          <!--Phone-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-telephone">
              <?php echo $entry_telephone; ?>
            </label>
            <div class="col-sm-10">
              <input type="tel" name="telephone" value="" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
            </div>
          </div>
          <!--Company-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-company">
              <?php echo $entry_company; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="company" value="" placeholder="<?php echo $entry_company; ?>" id="input-company" class="form-control" />
            </div>
          </div>
          <!--Address 1-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address-1">
              <?php echo $entry_address_1; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="address_1" value="" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
            </div>
          </div>
          <!--Address 2-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-address-2">
              <?php echo $entry_address_2; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="address_2" value="" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" class="form-control" />
            </div>
          </div>
          <!--City-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city">
              <?php echo $entry_city; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="city" value="" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
            </div>
          </div>
          <!--Postcode-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode">
              <?php echo $entry_postcode; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="postcode" value="" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
            </div>
          </div>
          <!--Country-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-country">
              <?php echo $entry_country; ?>
            </label>
            <div class="col-sm-10">
              <select name="country_id" id="input-country" class="form-control">
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected">
                  <?php echo $country[ 'name']; ?>
                </option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>">
                  <?php echo $country[ 'name']; ?>
                </option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <!--Zone-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone">
              <?php echo $entry_zone; ?>
            </label>
            <div class="col-sm-10">
              <select name="zone_id" id="input-zone" class="form-control">
              </select>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <div class="custom-option" id="mag-to-cart" style="">
            <div class="form-group cart ">
              <button type="button" id="button-save-address" data-loading-text="Loading..." class="button">
                <span class="button-cart-text">Save Address</span>
              </button>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    <!--end #delivery-address-->

  </div>
</div>
<!--Mag Add to Cart-->

<script type="text/javascript">
<!--

$('#button-save-address').click(function (e) {
  e.preventDefault();
  
  var addressId = $('#form-delivery-address :input[name="address_id"]').val();
  var useExistingAddress = $('input[name=address_option]:checked').val();
  var key = $('#key').val();
  
  if ( addressId > 0 && useExistingAddress == 1 ) {
    //some existing address is selected
    $('#hiddenAddress').val(addressId + "|" + $('select[name="address_id"] option:selected').text());
    doChangeAddress();
    
  } else {
    //new address - need to validate and create address

    var postData = $('#form-delivery-address').serializeArray();
    var formURL = $('#form-delivery-address').attr("action"); //index.php?route=product/mag_product/validateAddress
    
    $.ajax({
        url: formURL,
        type: "POST",
        data : postData,
        beforeSend: function() {
          $('#button-save-address').attr('disabled', true).button('loading');
        },
        complete: function() {
          $('#button-save-address').attr('disabled', false).button('reset');
        },
        success:function(json, textStatus, jqXHR) {
          $('.alert, .text-danger').remove();
          $('.has-error').removeClass('has-error');
          
          if (json['redirect']) {
            location = json['redirect'];
          
          } else if (json['error']) {
            for (i in json['error']) {
                var element = $('#input-' + i.replace('_', '-'));
                
                if ($(element).parent().hasClass('input-group')) {
                    $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                } else {
                    $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                }
            }
                            
            // Highlight any found errors
            $('.text-danger').parent().addClass('has-error');
        
          } else { 
            //if no redirect and no errors (e.i. address validated and added)
            var address = json['address_data'];
            var formattedAddress = address.firstname + ' ' + address.lastname + ", " + address.address_1 + ', ' + address.city + ', ' + address.zone + ', ' + address.country;
            $('#hiddenAddress').val(json['address_id'] + "|" + formattedAddress);
            
            //change address
            doChangeAddress();
          }
        },
        error: function(xhr, textStatus, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); //end ajax
  } //end creating new address
});    
    
function doChangeAddress() {
  
  var postData = {
    address : $('#hiddenAddress').val(),
    key : $('#key').val()
  }
  $.post('index.php?route=product/mag_product/changeAddress', postData, function (r) {
    $('#modal-address').modal('hide');
    location.reload();
  });
}    

function loadZones(countryId, activeZoneId) {
  $.ajax({
    url: 'index.php?route=account/account/country&country_id=' + countryId,
    dataType: 'json',
    beforeSend: function () {
      $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
    },
    complete: function () {
      $('.fa-spin').remove();
    },
    success: function (json) {
      if (json['postcode_required'] == '1') {
        $('input[name=\'postcode\']').parent().parent().addClass('required');
      } else {
        $('input[name=\'postcode\']').parent().parent().removeClass('required');
      }

      html = '<option value=""><?php echo $text_select; ?></option>';

      if (json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
          html += '<option value="' + json['zone'][i]['zone_id'] + '"';

          if (json['zone'][i]['zone_id'] == activeZoneId) {
            html += ' selected="selected"';
          }

          html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
      }

      $('select[name=\'zone_id\']').html(html);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$('select[name=\'country_id\']').on('change', function () {
  loadZones($(this).val(), 0);
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
			}else{
				//customer has no  addresses
				$('input[name=address_option]').eq(0).attr('checked',true).trigger('change');
			}
		},
		error: function(xhr, textStatus, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('select[name="address_id"]').change(function () {

  var addressData = $.data(document.body, 'addresses');
  var selectedAddressId = $(this).val();
  var form = document.getElementById("form-delivery-address");

  if (selectedAddressId == -1 || selectedAddressId == 0) {
    var countryId = $(':input[name="country_id"]').val();
    var zoneId = $(':input[name="zone_id"]').val();

    form.reset();

    $(':input[name="country_id"]').val(countryId);

    loadZones(countryId, zoneId);

    //		$('#fieldset-address :input').prop('disabled', false);

  } else {
    for (var i in addressData) {
      if (addressData[i].address_id == selectedAddressId) {
        //fill form with selected address data
        var data = addressData[i];
        for (var n in data) {
          $(':input[name="' + n + '"]').val(data[n]);
        }
        loadZones(data.country_id, data.zone_id);
        break;
      }
    }
  }
});

loadAdresses();
//-->
</script>

<script type="text/javascript">
<!--
$('select[name=\'country_id\']').on('change', function () {
  $.ajax({
    url: 'index.php?route=account/account/country&country_id=' + this.value,
    dataType: 'json',
    beforeSend: function () {
      $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
    },
    complete: function () {
      $('.fa-spin').remove();
    },
    success: function (json) {
      if (json['postcode_required'] == '1') {
        $('input[name=\'postcode\']').parent().parent().addClass('required');
      } else {
        $('input[name=\'postcode\']').parent().parent().removeClass('required');
      }

      html = '<option value=""><?php echo $text_select; ?></option>';

      if (json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
          html += '<option value="' + json['zone'][i]['zone_id'] + '"';

          if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
            html += ' selected="selected"';
          }

          html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
      }

      $('select[name=\'zone_id\']').html(html);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('input[name=address_option]').change(function () {
  if ($(this).val() == 1) {
    $('.select-address').slideDown();
    $('#fieldset-address').slideUp();
  } else {
    $('#fieldset-address').slideDown();
    $('.select-address').slideUp();
  }
});
$('select[name=\'country_id\']').trigger('change');
//-->
</script>