$(document).ready(function (e) {
	if(vecpopup.pp_start){
		setTimeout(function() {
			$('.modal').modal('hide');
			prestashop.emit('hidden_canvas_widget', null);
			$("#vecpopupnewsletter").modal({show: true});
		}, vecpopup.time_delay);
	}
	
	$('.ajax-newsletter').on('submit', function (e) {
		e.preventDefault();
		subscribe_newlleter($(this));
	});
	
	$('body').on('hidden.bs.modal','#vecpopupnewsletter',(function() {
		if($('#newsletter_popup_dont_show_again').is(':checked')){
			setcookiepopup();
		}
	}));
	
	$('body').on('click','.js-open-newsletter',(function(event) {
		event.preventDefault();
		$("#vecpopupnewsletter").modal({show: true});
	}));
});

function setcookiepopup() {
  var name = 'has_cookiepopupn';
  var value = '1';
  var expire = new Date();
  expire.setMonth(expire.getMonth()+12);
  document.cookie = name + "=" + escape(value) +";path=/;" + ((expire==null)?"" : ("; expires=" + expire.toGMTString()))
}

function subscribe_newlleter($el){
	
	if($el.hasClass("pp-processing")){
		return;
	}
	
	$el.addClass("pp-processing");
	
	$.ajax({
		type: 'POST',
		dataType: 'JSON',
		cache: false,		
		data: $el.serialize(),
		url: vecpopup.ajax,		
		beforeSend: function(){
			$el.find('[name=submitNewsletter]').addClass('processing');
			$el.find(".send-response").html('');
		},
		complete: function() {
			$el.removeClass("pp-processing");
			$el.find('[name=submitNewsletter]').removeClass('processing');
		},
		success: function(data) {
			if (data.nw_error) {
				$el.find(".send-response").html('<p class="alert alert-danger block_newsletter_alert">' + data.msg + '</p>');
			} else {
				$el.find(".send-response").html('<p class="alert alert-success block_newsletter_alert">' + data.msg + '</p>');
				$el.find("[name=email]").val('');
				$el.find('[type="submit"]').attr('disabled', 'disabled');
				$el.find("[name=psgdpr_consent_checkbox]").prop( "checked", false );
			}
		},
		error: function (err) {
			console.log(err);
		}
	});
}