<?php //this line is here for editor to know it is php code and format the document properly ?>

<?php if (isset($custom_options['select_term'])) { ?>
<?php $option = $custom_options['select_term']; ?>

<div class="options push-checkbox">
<div id="section-select-term" class="option form-group required option-radio">
	<label class="control-label">Select Term</label>
	<?php $first = true; ?>
	<?php foreach ( $option['product_option_value'] as $option_value ) { ?>
	<div id="input-option<?php echo $option['product_option_id']; ?>">
		<div class="radio">
		<label><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if ($first) { echo " checked='checked'"; $first = false; } ?> /><?php echo $option_value['name']; ?></label>
		</div>
	</div>
	<?php } //end foreach option value ?>
</div>
</div>

<?php } ?>

<div id="section-subscribe-renew" class= custom-option>
    <div class="form-group option-subscribe-renew">
        <label class="control-label">Select Status: Subscribe/New or Renew</label>
        <div id="subscribe-renew">
            <button class="button subscribe-renew-button unselected"><?php echo $button_subscribe; ?>
                <div class="radio" style="display:none;">
                    <input type="radio" name="option[10002]" value="New" />
                </div>
            </button>
            <button class="button subscribe-renew-button unselected"><?php echo $button_renew; ?>
				<div class="radio" style="display:none;">
					<input type="radio" name="option[10002]" value="Renew" />
				</div>
            </button>
        </div>
    </div>
</div>

<?php if (!$this->customer->isLogged()) { //if customer not logged in, ask for credentials ?>
<div class="custom-option" id="signin" style="display:none;">
<form role="form" id="form-signin" action="index.php?route=account/mag_customer/signin">
        <fieldset>
                <h2 class="secondary-title"><?php echo $text_signin; ?></h2>
                <div class="form-group required">
					<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
					<div class="col-sm-10">
						<input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-signin-email" class="form-control" />
					</div>
                </div>
                <div class="form-group required">
					<label class="col-sm-2 control-label" for="input-zippass"><?php echo $entry_zippass; ?></label>
					<div class="col-sm-10">
						<input type="text" name="zippass" value="" placeholder="<?php echo $entry_zippass; ?>" id="input-signin-zippass" class="form-control" />
						<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
					</div>
                </div>

                <div class="form-group">
                    <button id="button-signin" class="button pull-right" data-loading-text="<?php echo $text_signing_in; ?>" type="submit"><?php echo $button_signin; ?></button>
                </div>
        </fieldset>
</form>
</div>
<?php } // end if not logged in ?>



<?php if ( $this->config->get('config_store_id') == 1) { ?>
<div class="custom-option" id="gift-options" style="display:none;">
	<h2 class="secondary-title">Gift Announcement To Recipient?</h2>
	<label class="control-label">Would you like us to send your recipient a postcard in the mail 
	or email, announcing your gift?</label>
	<div>
		<label>
			<input name="announcement" type="radio" value="No" checked />No
		</label>
		<label>
			<input name="announcement" type="radio" value="Postcard"/>Postcard
		</label>
		<label>
			<input name="announcement" type="radio" value="E-mail" />E-mail
		</label>
	</div>
	<!--Email Announcement Delivery date-->
	<div id="pick-announcement-date" class="option form-group required" style="display:none;" >
				<label class="control-label" for="announcement-date">We will send the email gift card
	on the date you specify below</label>
				<input type="text" name="option[announcement-date]" value="" placeholder="YYYY-MM-DD" id="announcement-date" data-date-format="YYYY-MM-DD" class="form-control date" />
	</div>

	<!--Postcard image (design)-->
	<?php if (isset($custom_options['postcard_image']) && !empty ($custom_options['postcard_image']['product_option_value']) ) { //Postcard Design Select ?>

	<?php $option = $custom_options['postcard_image']; ?>

	<div id="postcard" style="display:none;" class="option form-group option-<?php echo $option['type']; ?>">
		<!--<h2 class="secondary-title"><?php echo $option['name']; ?></h2>-->
		<div class="required">
			<label class="control-label"><?php echo $option['name']; ?></label>
		</div>

		<div id="input-option<?php echo $option['product_option_id']; ?>">
		<?php foreach ($option['product_option_value'] as $option_value) { ?>
		<div class="radio-inline">
			<label>
				<div class="image-option-name">
					<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
					<?php echo $option_value['name']; ?>
					<?php if ($option_value['price']) { ?>
					<span> (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)</span>
					<?php } ?>
				</div>
				<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
			</label>
		</div>
		<?php } ?>
		</div>
	</div>

	<?php } //end Postcard Design Select ?>
</div>
<?php } //end if store_id = 1 ?>

<?php //hidden option that contains address_id ?>
<div style="display:none;">
      <input type="text" id="hiddenAddress" value="" name="option[10001]">
</div>

<div class="custom-option" id="delivery-address" style="display:none;">
<form class="form-vertical" role="form" id="form-delivery-address" action="index.php?route=product/mag_product/validateAddress">
        <h2 class="secondary-title"><?php echo $text_delivery_address; ?></h2>
		<div class="radio">
			<label>
				<input type="radio" <?php if(empty($addresses)) echo "checked=checked"; ?> name="address_option" value="0" /> I want to use a new address
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" <?php if($addresses) echo "checked=checked"; ?> name="address_option" value="1" /> I want to use an existing address
			</label>
        </div>
		<fieldset id='fieldset-address-selector'>
                <!--Address Selector-->
                <!--<legend class="select-address" style="display:none;"><?php echo $entry_select_address; ?></legend>-->
                <div class="select-address" class="form-group" style="display:none;">
                        <label class="control-label" for="input-address-id"></label>
                        <select name="address_id" class="form-control">
                                <option value="0"><?php echo $text_select; ?></option>
                        </select>
                </div>
        </fieldset>
        <fieldset id="fieldset-address" style="display:none;">
                <!--First Name-->
                <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                        <div class="col-sm-10">
                                <input type="text" name="firstname" value="" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                        </div>
                </div>
                <!--Last Name-->
                <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                        <div class="col-sm-10">
                                <input type="text" name="lastname" value="" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                        </div>
                </div>
                <!--Phone-->
                <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                        <div class="col-sm-10">
                                <input type="tel" name="telephone" value="" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                        </div>
                </div>
                <!--Company-->
                <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-company"><?php echo $entry_company; ?></label>
                        <div class="col-sm-10">
                                <input type="text" name="company" value="" placeholder="<?php echo $entry_company; ?>" id="input-company" class="form-control" />
                        </div>
                </div>
                <!--Address 1-->
                <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-address-1"><?php echo $entry_address_1; ?></label>
                        <div class="col-sm-10">
                                <input type="text" name="address_1" value="" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
                        </div>
                </div>
                <!--Address 2-->
                <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-address-2"><?php echo $entry_address_2; ?></label>
                        <div class="col-sm-10">
                                <input type="text" name="address_2" value="" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" class="form-control" />
                        </div>
                </div>
                <!--City-->
                <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-city"><?php echo $entry_city; ?></label>
                        <div class="col-sm-10">
                                <input type="text" name="city" value="" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
                        </div>
                </div>
                <!--Postcode-->
                <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
                        <div class="col-sm-10">
                                <input type="text" name="postcode" value="" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
                        </div>
                </div>
                <!--Country-->
                <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
                        <div class="col-sm-10">
                                <select name="country_id" id="input-country" class="form-control">
                                        <?php foreach ($countries as $country) { ?>
                                        <?php if ($country['country_id'] == $country_id) { ?>
                                        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                </select>
                        </div>
                </div>
                <!--Zone-->
                <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
                        <div class="col-sm-10">
                                <select name="zone_id" id="input-zone" class="form-control">
                                </select>
                        </div>
                </div>
        </fieldset>
        <fieldset>
        <div class="custom-option" id="mag-to-cart">
                <div class="form-group">
                        <button type="button" id="button-mag-cart" data-loading-text="<?php echo $text_loading; ?>" class="button"><span class="button-cart-text"><?php echo $button_cart; ?></span></button>
                </div>
        </div>
        </fieldset>
</form>
</div> <!--end #delivery-address-->



<!--Mag Add to Cart-->

<script type="text/javascript"><!--

$('#button-mag-cart').click(function(e) {
	var addressId = $('#form-delivery-address :input[name="address_id"]').val();
	var useExistingAddress = $('input[name=address_option]:checked').val();
	
	if ( addressId > 0 && useExistingAddress == 1 ) {
		//some existing address is selected
        
		$('#hiddenAddress').val(addressId + "|" + $('select[name="address_id"] option:selected').text());
		
		//add-to-cart
		$('#button-cart').trigger('click');
		
	} else {
		//new address - need to validate and create address
		
		var postData = $('#form-delivery-address').serializeArray();
		var formURL = $('#form-delivery-address').attr("action");
		
		$.ajax({
			url: formURL,
			type: "POST",
			data : postData,
			beforeSend: function() {
				$('#button-mag-cart').attr('disabled', true).button('loading');
			},
			complete: function() {
				$('#button-mag-cart').attr('disabled', false).button('reset');
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
					//add-to-cart
					$('#button-cart').trigger('click');
				}
			},
			error: function(xhr, textStatus, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	} //end else
	e.preventDefault();
});

function loadZones(countryId, activeZoneId){
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + countryId,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
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
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('select[name=\'country_id\']').on('change', function() {
	loadZones($(this).val(), 0);
});

$('.subscribe-renew-button').click(function() {

	if ( $(this).hasClass('unselected') ) {
		
		$(this).removeClass('unselected').addClass('selected').find('input').prop('checked', true);
		
		$('#subscribe-renew').fadeOut();
		
		$(this).addClass('selected').find('input').prop('checked', true);
		var selectedValue = $('#subscribe-renew input:checked').val();
		
		$('#section-subscribe-renew label').text('Status: ' + selectedValue);
		
		if ( $('a.secondary-menu-item-1').text() == "Login" ) {
			//customer not logged in
			$('#signin').slideDown();
		} else {
			//customer is logged already
			loadAdresses();
			$('#delivery-address, #gift-options, #mag-to-cart').slideDown();
		}
	}
	$("html, body").animate({ scrollTop: $('#section-subscribe-renew').offset().top - 120 }, "slow");
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

$('input[name=address_option]').change(function(){
    if($(this).val()==1){
        $('.select-address').slideDown();
        $('#fieldset-address').slideUp();
    }else{
        $('#fieldset-address').slideDown();
        $('.select-address').slideUp();
    }
});

$('#form-signin').submit(function(e) {
	var postData = $(this).serializeArray();
	var formURL = $(this).attr("action");
	$.ajax({
		url: formURL,
		type: "POST",
		data : postData,
		beforeSend: function() {
			$('#button-signin').attr('disabled', true).button('loading');
		},
		complete: function() {
			$('#button-signin').attr('disabled', false).button('reset');
		},
		success:function(json, textStatus, jqXHR) {
			$('.alert, .text-danger').remove();
			$('.has-error').removeClass('has-error');
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				for (i in json['error']) {
					var element = $('#input-signin-' + i.replace('_', '-'));
					
					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}
								
				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			} else { //if no redirect and no errors
				$('#signin').slideUp();
				//prefilling postcode with alerady entered zip code
				$('input[name="postcode"]').val($('input[name="zippass"]').val());
				loadAdresses();
				$('#delivery-address, #gift-options, #mag-to-cart').slideDown();
				//changing text and links in header
				$('a.secondary-menu-item-1').attr('href', 'index.php?route=account/account');
				$('a.secondary-menu-item-2').attr('href', 'index.php?route=account/logout');
				$('a.secondary-menu-item-1 span').text("My Account").attr('href', 'index.php?route=account/account');
				$('a.secondary-menu-item-2 span').text("Logout").attr('href', 'index.php?route=account/logout');
				$('.secondary-menu-item-1,.secondary-menu-item-2').unbind('click');
				//updating mini-cart
				$('#cart ul').load('index.php?route=common/cart/info ul li');
				$('#cart-total').html(json['total']);
			}
		},
		error: function(xhr, textStatus, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	e.preventDefault();
});

//--></script>
		
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
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
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>

<script type="text/javascript"><!--

$('input[name="announcement"]').on('click', function(){
	
	if ( $(this).val() == "No" ) {
		$('#postcard').slideUp();
		$('#pick-announcement-date').slideUp();
	} else if ( $(this).val() == "Postcard" ) {
		$('#postcard').slideDown();
		$('#pick-announcement-date').slideUp();
	} else if ( $(this).val() == "E-mail" ) {
		$('#postcard').slideUp();
		$('#pick-announcement-date').slideDown();
	}
});
//--></script>