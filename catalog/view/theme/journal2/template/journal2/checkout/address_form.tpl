<div class="<?php echo $is_logged_in ? 'checkout-content' : ''; ?> checkout-<?php echo $type; ?>-form">
    <?php if ($is_logged_in): ?>
    <h2 class="secondary-title"><?php echo $type === 'payment' ? 'Billing Address' :'Delivery Address'; ?> </h2>
    <?php endif; ?>
    <form class="form-horizontal form-<?php echo $type; ?>">
        <?php if ($addresses) { ?>
            <div class="radio">
                <label>
                    <input type="radio" name="<?php echo $type; ?>_address" value="existing" checked="checked" />
                    <?php echo $text_address_existing; ?>
                </label>
            </div>
            <div id="<?php echo $type; ?>-existing">
                <select name="<?php echo $type; ?>_address_id" class="form-control">
                    <?php foreach ($addresses as $address) { ?>
                        <?php if ($address['address_id'] == $address_id) { ?>
                            <option value="<?php echo $address['address_id']; ?>"
                                    selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>
                                , <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>
                                , <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
                        <?php } else { ?>
                            <option
                                value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>
                                , <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>
                                , <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="<?php echo $type; ?>_address" value="new"/>
                    <?php echo $text_address_new; ?></label>
            </div>
        <?php } ?>
        <div id="<?php echo $type; ?>-new" style="display: <?php echo($addresses ? 'none' : 'block'); ?>;">
            <?php if ($name): ?>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-firstname"><?php echo $entry_firstname; ?></label>

                    <input type="text" name="<?php echo $type; ?>_firstname" value="" placeholder="<?php echo $entry_firstname; ?>"
                           id="input-<?php echo $type; ?>-firstname" class="form-control"/>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-lastname"><?php echo $entry_lastname; ?></label>

                    <input type="text" name="<?php echo $type; ?>_lastname" value="" placeholder="<?php echo $entry_lastname; ?>"
                           id="input-<?php echo $type; ?>-lastname" class="form-control"/>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-company"><?php echo $entry_company; ?></label>

                    <input type="text" name="<?php echo $type; ?>_company" value="" placeholder="<?php echo $entry_company; ?>"
                           id="input-<?php echo $type; ?>-company" class="form-control"/>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-address-1"><?php echo $entry_address_1; ?></label>

                    <input type="text" name="<?php echo $type; ?>_address_1" value="" placeholder="<?php echo $entry_address_1; ?>"
                           id="input-<?php echo $type; ?>-address-1" class="form-control"/>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-address-2"><?php echo $entry_address_2; ?></label>

                    <input type="text" name="<?php echo $type; ?>_address_2" value="" placeholder="<?php echo $entry_address_2; ?>"
                           id="input-<?php echo $type; ?>-address-2" class="form-control"/>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-city"><?php echo $entry_city; ?></label>

                    <input type="text" name="<?php echo $type; ?>_city" value="" placeholder="<?php echo $entry_city; ?>" id="input-<?php echo $type; ?>-city"
                           class="form-control"/>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-postcode"><?php echo $entry_postcode; ?></label>

                    <input type="text" name="<?php echo $type; ?>_postcode" value="" placeholder="<?php echo $entry_postcode; ?>"
                           id="input-<?php echo $type; ?>-postcode" class="form-control"/>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-country"><?php echo $entry_country; ?></label>
                <div class="col-sm-10">

                    <select name="<?php echo $type; ?>_country_id" id="input-<?php echo $type; ?>-country" class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($countries as $country) { ?>
                            <?php if ($country['country_id'] == $country_id) { ?>
                                <option value="<?php echo $country['country_id']; ?>"
                                        selected="selected"><?php echo $country['name']; ?></option>
                            <?php } else { ?>
                                <option
                                    value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-<?php echo $type; ?>-zone"><?php echo $entry_zone; ?></label>
                <div class="col-sm-10">

                <select name="<?php echo $type; ?>_zone_id" id="input-<?php echo $type; ?>-zone" class="form-control">
                    </select>
                    </div>
            </div>
            <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'address') { ?>
                    <?php if ($custom_field['type'] == 'select') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"
                                   for="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>

                                <select name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]"
                                        id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                                        class="form-control">
                                    <option value=""><?php echo $text_select; ?></option>
                                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                                        <option
                                            value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                                    <?php } ?>
                                </select>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'radio') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>

                                <div id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio"
                                                       name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]"
                                                       value="<?php echo $custom_field_value['custom_field_value_id']; ?>"/>
                                                <?php echo $custom_field_value['name']; ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'checkbox') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>

                                <div id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"
                                                       name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>][]"
                                                       value="<?php echo $custom_field_value['custom_field_value_id']; ?>"/>
                                                <?php echo $custom_field_value['name']; ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'text') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"
                                   for="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>

                                <input type="text" name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]"
                                       value="<?php echo $custom_field['value']; ?>"
                                       placeholder="<?php echo $custom_field['name']; ?>"
                                       id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                                       class="form-control"/>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'textarea') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"
                                   for="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>

                                <textarea name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5"
                                          placeholder="<?php echo $custom_field['name']; ?>"
                                          id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                                          class="form-control"><?php echo $custom_field['value']; ?></textarea>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'file') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>

                                <button type="button"
                                        id="button-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                                        data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i
                                        class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                                <input type="hidden" name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]"
                                       value=""
                                       id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"/>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'date') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"
                                   for="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>

                                <div class="input-group date">
                                    <input type="text" name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]"
                                           value="<?php echo $custom_field['value']; ?>"
                                           placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD"
                                           id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                                           class="form-control"/>
              <span class="input-group-btn">
              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
              </span></div>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'time') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"
                                   for="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>

                                <div class="input-group time">
                                    <input type="text" name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]"
                                           value="<?php echo $custom_field['value']; ?>"
                                           placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm"
                                           id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                                           class="form-control"/>
              <span class="input-group-btn">
              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
              </span></div>
                        </div>
                    <?php } ?>
                    <?php if ($custom_field['type'] == 'datetime') { ?>
                        <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field"
                             data-sort="<?php echo $custom_field['sort_order']; ?>">
                            <label class="col-sm-2 control-label"
                                   for="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>

                                <div class="input-group datetime">
                                    <input type="text" name="<?php echo $type; ?>_custom_field[<?php echo $custom_field['custom_field_id']; ?>]"
                                           value="<?php echo $custom_field['value']; ?>"
                                           placeholder="<?php echo $custom_field['name']; ?>"
                                           data-date-format="YYYY-MM-DD HH:mm"
                                           id="input-<?php echo $type; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                                           class="form-control"/>
              <span class="input-group-btn">
              <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
              </span></div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
    </form>
    <script>
        $('form.form-<?php echo $type; ?> input[name="<?php echo $type; ?>_address"]').change(function () {
            if (this.value == 'new') {
                $('#<?php echo $type; ?>-existing').hide();
                $('#<?php echo $type; ?>-new').show();
            } else {
                $('#<?php echo $type; ?>-existing').show();
                $('#<?php echo $type; ?>-new').hide();
            }
            $(document).trigger('journal_checkout_address_changed', '<?php echo $type; ?>');
        });

        $('form.form-<?php echo $type; ?> select[name=\'<?php echo $type; ?>_country_id\']').on('change', function(e, first) {
            if (!this.value) return;
            $.ajax({
                url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
                dataType: 'json',
                beforeSend: function() {
                    $('form.form-<?php echo $type; ?> select[name=\'<?php echo $type; ?>_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                },
                complete: function() {
                    $('.fa-spin').remove();
                },
                success: function(json) {
                    $('.fa-spin').remove();

                    if (json['postcode_required'] == '1') {
                        $('form.form-<?php echo $type; ?> input[name=\'<?php echo $type; ?>_postcode\']').parent().addClass('required');
                    } else {
                        $('form.form-<?php echo $type; ?> input[name=\'<?php echo $type; ?>_postcode\']').parent().removeClass('required');
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

                    $('form.form-<?php echo $type; ?> select[name=\'<?php echo $type; ?>_zone_id\']').html(html);

                    if (!first) {
                        $(document).trigger('journal_checkout_address_changed', '<?php echo $type; ?>');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }).trigger('change', true);

        $('form.form-<?php echo $type; ?> select[name=\'<?php echo $type; ?>_zone_id\']').on('change', function() {
            $(document).trigger('journal_checkout_address_changed', '<?php echo $type; ?>');
        });

        $('form.form-<?php echo $type; ?> select[name=\'<?php echo $type; ?>_address_id\']').on('change', function() {
            $(document).trigger('journal_checkout_address_changed', '<?php echo $type; ?>');
        });
    </script>
</div>