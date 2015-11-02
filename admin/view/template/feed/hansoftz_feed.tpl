<?php echo $header; ?><?php echo $column_left; ?>
<?php 
function _x($v,$_xx){  global $settings;
    return (isset($_xx[$v]))?$_xx[$v]:'';
}
?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" id="button-import" data-toggle="tooltip" title="<?php echo $button_import; ?>" class="btn btn-success"><i class="fa fa fa-upload"></i></button>
        <button type="submit" form="form-google-base" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-google-base" class="form-horizontal">
            
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-data-feed">Flipkart Feed Uri: </label>
              <div class="col-sm-10">
                  <input value="<?php echo _x('hansoftz_feed_fk_feed_uri',$_xx); ?>" class="form-control" name="hansoftz_feed_fk_feed_uri" />
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-data-feed">Flipkart Affiliate Tracking ID: </label>
              <div class="col-sm-10">
                  <input class="form-control" name="hansoftz_feed_fk_tracking_id" value="<?php echo _x('hansoftz_feed_fk_tracking_id',$_xx); ?>" />
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-data-feed">Flipkart Token: </label>
              <div class="col-sm-10">
                  <input class="form-control" name="hansoftz_feed_fk_token" value="<?php echo _x('hansoftz_feed_fk_token',$_xx); ?>" />
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-data-feed">Flipkart API version: </label>
              <div class="col-sm-10">
                  <input class="form-control" name="hansoftz_feed_fk_api_version" value="<?php echo _x('hansoftz_feed_fk_api_version',$_xx); ?>" />
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-data-feed">Category Slug Identifier: </label>
              <div class="col-sm-10">
                  <textarea class="form-control" name="hansoftz_feed_fk_cats"><?php echo _x('hansoftz_feed_fk_cats',$_xx); ?></textarea>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="col-sm-10">
                <select name="hansoftz_feed_fk_feed_status" id="input-status" class="form-control">
                  
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  
                </select>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
// Google Category
$('input[name=\'hansoftz_feed_category\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=feed/hansoftz_feed/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['hansoftz_feed_category_id']
                    }
                }));
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'select': function(item) {
        $(this).val(item['label']);
        $('input[name=\'hansoftz_feed_category_id\']').val(item['value']);
  }
});

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			},
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
	},
	'select': function(item) {
        $(this).val(item['label']);
        $('input[name="category_id"]').val(item['value']);
    }
});

$('#category').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#category').load(this.href);
});

$('#category').load('index.php?route=feed/hansoftz_feed/category&token=<?php echo $token; ?>');

$('#button-category-add').on('click', function() {
	$.ajax({
		url: 'index.php?route=feed/hansoftz_feed/addcategory&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'hansoftz_feed_category_id=' + $('input[name=\'hansoftz_feed_category_id\']').val() + '&category_id=' + $('input[name=\'category_id\']').val(),
		beforeSend: function() {
			$('#button-category-add').button('loading');
		},
		complete: function() {
			$('#button-category-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#category').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#category').load('index.php?route=feed/hansoftz_feed/category&token=<?php echo $token; ?>');

				$('#category').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('input[name=\'category\']').val('');
                $('input[name=\'category_id\']').val('');
                $('input[name=\'hansoftz_feed_category\']').val('');
                $('input[name=\'hansoftz_feed_category_id\']').val('');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#category').delegate('.btn-danger', 'click', function() {
	var node = this;

	$.ajax({
		url: 'index.php?route=feed/hansoftz_feed/removecategory&token=<?php echo $token; ?>',
		type: 'post',
		data: 'category_id=' + encodeURIComponent(this.value),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			// Check for errors
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

            if (json['success']) {
				$('#category').load('index.php?route=feed/hansoftz_feed/category&token=<?php echo $token; ?>');

				$('#category').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-import').on('click', function() {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=feed/hansoftz_feed/import&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-import').button('loading');
				},
				complete: function() {
					$('#button-import').button('reset');
				},
				success: function(json) {
					$('.alert').remove();

                    if (json['error']) {
        				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        			}

        			if (json['success']) {
        				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        			}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
</div>
<?php echo $footer; ?>
