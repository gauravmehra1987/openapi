$(window).bind("load", function () {
	if ( $('a.secondary-menu-item-1').text() == "Login" ) {
		
		//$('.secondary-menu-item-1,.secondary-menu-item-2,.secondary-menu-item-3,.secondary-menu-item-4').attr('href', 'javascript:void(0);');
		
	  $('.secondary-menu-item-1,.secondary-menu-item-2,.secondary-menu-item-3,.secondary-menu-item-4').click(function(e) {
		e.preventDefault();
		$('.hovered_log').fadeIn('slow');
		var clickedLink = ($(this).attr('href')).toLowerCase();
		var thisLink = window.location.href;
		
		if ( clickedLink.indexOf( "route=account/login" ) != -1 || clickedLink.indexOf( "route=account/register" ) != -1 ) {
		  if ( thisLink.indexOf( "route=account/logout" ) != -1 ) {
			clickedLink = "index.php?route=account/account";
		  } else {
			clickedLink = '';
		  }
		}
		$('#clickedLink').val(clickedLink);
	  });
	}
	
	$('.secondary-menu-item-1').parent().parent().after('\
		<div class="hovered_log" style="display: none;"> \
			<input id="clickedLink" type="hidden" value="">\
			<form id="header-signin" class="form_al" action="index.php?route=account/mag_customer/signin" method="post" enctype="multipart/form-data"> \
				<span class="cross">X</span> \
				<div class="block"> \
					<label>Email</label> \
					<input type="text" name="email" placeholder="Email" id="header-signin-email"> \
				</div> \
				<div class="block block_margin"> \
					<label>Zip Code/Post Code</label> \
					<input type="text" name="zippass" placeholder="ZIP code" id="header-signin-zippass"> \
				</div> \
				<div class="block block_small" style="margin-left:3px;padding:0px;margin-top:5px;" id="header-signin-submit"> \
					<input class="btn_logon" type="submit" value="Submit" id="button-signin-submit"> \
				</div> \
				<p class="help_p">Need help? click <a href="index.php?route=account/forgotten">here</a> for assistance</p> \
			</form> \
		</div>'
	);
	
	$('#header-signin').submit(function(e) {
		var postData = $(this).serializeArray();
		var formURL = $(this).attr("action");
		
		$.ajax({
			url: formURL,
			type: "POST",
			data : postData,
			beforeSend: function() {
				$('#button-signin-submit').attr('disabled', true).button('loading');
			},
			complete: function() {
				$('#button-signin-submit').attr('disabled', false).button('reset');
			},
			success:function(json, textStatus, jqXHR) {
				$('.alert, .text-danger').remove();
				$('.has-error').removeClass('has-error');
				
				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					for (i in json['error']) {
						$('#header-signin-submit').after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
									
					// Highlight any found errors
					$('.text-danger').parent().addClass('has-error');
				} else { 
					//if no redirect and no errors
					$(".hovered_log").fadeOut("slow");
					var clickedLink = $('#clickedLink').val();
					if (!clickedLink) {
					  window.location.reload(true);
					} else {
					  window.location = clickedLink;
					}
					//changing text and links
// 					$('a.secondary-menu-item-1').attr('href', 'index.php?route=account/account');
// 					$('a.secondary-menu-item-2').attr('href', 'index.php?route=account/logout');
// 					$('a.secondary-menu-item-1 span').text("My Account").attr('href', 'index.php?route=account/account');
// 					$('a.secondary-menu-item-2 span').text("Logout").attr('href', 'index.php?route=account/logout');
// 					$('.secondary-menu-item-1,.secondary-menu-item-2').unbind('click');
// 					//updating mini - cart
// 					$('#cart ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, textStatus, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
		e.preventDefault();
	});

});

$(document).on('click', '.cross', function () {
	$(".hovered_log").fadeOut("slow");
});