var vecCustomerSignin = {
	showModal : function(){
		$('.vec-quicklogin-modal').modal('show');
		$('.vec-quicklogin-modal').on('hidden.bs.modal', function () {
            $('.vec-quicklogin-modal').modal('hide');
        });

        $('.vec-quicklogin-modal').on('hidden.bs.modal', function () {
			$('.vec-quicklogin-modal .modal-header').find('h3').hide();
			$('.login-title').show();
			$('.vec-quicklogin-form .vec-login-form').show();
			$('.vec-quicklogin-form .vec-resetpass-form').hide();
		})
	},
	ajaxLogin: function(){
		var email 	  = $('.vec-email-login').val(),
			password  = $('.vec-pass-login').val(),
			loginForm = $('.login-form-content');
		$.ajax({
			type: 'POST',
			headers: {"cache-control": "no-cache"},
			url: csi_ajax_url,
			async: true,
			cache: false,
			data: {
				"ajax": 1,
				"action": "customer-login",
				"email": email,
				"password" : password,
			},
			success: function (result)
			{
				loginForm.find('.vec-form-msg').removeClass('has-danger').empty();

				var object_result = $.parseJSON(result);

				if (object_result.errors.length)
				{		
					loginForm.find('.vec-form-msg').addClass('has-danger');
					$.each(object_result.errors,function(key, val){
						loginForm.find('.vec-form-msg').append('<label class="form-control-label">'+val+'</label>')
					})
				}
				else
				{
					loginForm.find('.vec-form-msg').addClass('has-success').append('<label class="form-control-label"><strong>'+object_result.success[0]+'</strong></label>');
					loginForm.find('.lql-form-content-element').slideUp(function(){
						$(this).remove();
					});
					if (csi_redirect == '2')
					{
						window.location.replace(csi_myaccount_url);
					}
					else
					{
						location.reload();
					}
				}											
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				console.log("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
			}
		});
	},
	resetPassword : function(email){
		var email 	  = $('.vec-email-reset').val(),
			loginForm = $('.vec-resetpass-form');
		$.ajax({
			type: 'POST',
			headers: {"cache-control": "no-cache"},
			url: csi_ajax_url,
			async: true,
			cache: false,
			data: {
				"ajax": 1,
				"action": "reset-pass",
				"email": email,
			},
			success: function (result)
			{
				loginForm.find('.vec-form-msg').removeClass('has-danger').empty();
				var object_result = $.parseJSON(result);
				
				if (object_result.errors.length)
				{
					loginForm.find('.vec-form-msg').addClass('has-danger');
					$.each(object_result.errors,function(key, val){
						loginForm.find('.vec-form-msg').append('<label class="form-control-label">'+val+'</label>')
					})
				}
				else
				{
					loginForm.find('.vec-form-msg').addClass('has-success').append('<label class="form-control-label">'+object_result.success[0]+'<strong>'+ email +'</strong></label>');
					loginForm.find('.form-group').hide();
					loginForm.find('.back-login-btn').show();
				}											
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				console.log("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
			}
		});
	}
}

$(document).ready(function(){
	$('.vec-customersignin a.login').on('click', function(e){
		e.preventDefault();
		vecCustomerSignin.showModal();
	})
	$('.vec-login-btn').on('click', function(e){
		e.preventDefault();
		vecCustomerSignin.ajaxLogin();
	})
	$('.vec-forgot-password').on('click', function(e){
		e.preventDefault();
		$('.vec-quicklogin-modal .modal-header').find('h3').hide();
		$('.vec-login-form').hide();
		$('.vec-resetpass-form, .reset-title').show();
	})
	$('.vec-reset-btn').on('click', function(e){
		e.preventDefault();
		vecCustomerSignin.resetPassword();
	})
	$('.back-login-btn').on('click', function(e){
		e.preventDefault();
		$('.vec-login-form, .login-title').show();
		$('.vec-resetpass-form, .reset-title').hide();
	})
})