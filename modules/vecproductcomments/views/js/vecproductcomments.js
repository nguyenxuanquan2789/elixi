$(function() {
	$('input.star').rating();
	$('.auto-submit-star').rating();

	$("#new_comment_tab_btn").click(function() {
		$("#vec-product-comment-modal").modal('show');
	});

	$('#submitNewMessage').click(function(e) {
		e.preventDefault();

		// Form element
        url_options = '?';
        if (!vecproductcomments_url_rewrite)
            url_options = '&';

		$.ajax({
			url: vecproductcomments_controller_url + url_options + 'action=add_comment&secure_key=' + secure_key + '&rand=' + new Date().getTime(),
			data: $('#id_new_comment_form').serialize(),
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			dataType: "json",
			success: function(data){
				if (data.result)
				{
					$("#new_comment_form").hide(); 
					$("#result_comment").show();
				}
				else
				{
					$('#new_comment_form_error ul').html('');
					$.each(data.errors, function(index, value) {
						$('#new_comment_form_error ul').append('<li>'+value+'</li>');
					});
					$('#new_comment_form_error').slideDown('slow');
				}
			}
		});
		return false;
	});
});
// vecproductcomments
$(document).on('click','#product_comments_block_extra .comments_advices a', function(e){
	$('*[class^="tab-pane"]').removeClass('active');
	$('*[class^="tab-pane"]').removeClass('in');
	$('*[class^="collapse"]').removeClass('in');
	$('#collapseFive').addClass('in'); 
	$('div#product_comments_block_tab').addClass('active');
	$('div#product_comments_block_tab').addClass('in');

	$('ul.nav-tabs a[href^="#"]').removeClass('active');
	$('a[href="#product_comments_block_tab"]').addClass('active'); 
});