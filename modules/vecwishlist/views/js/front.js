$(document).ready(function() {
	
	wishlistRefreshStatus();

	$('body').on('show.bs.modal','.quickview',(function() {
		wishlistRefreshStatus();
	}));
	
    prestashop.on('updatedProductList', function (e) {
        wishlistRefreshStatus();
    });
	
    prestashop.on('updatedProduct', function (e) {
        wishlistRefreshStatus();
    });
	
    prestashop.on('updatedProductAjax', function (e) {
        wishlistRefreshStatus();
    });
	
	$('body').on('click', '.js-wishlist-add', function (e) {
		var self = this;
		prestashop.emit('clickWishListAdd', {
			dataset: self.dataset,
			self: self
		});
		e.preventDefault();
	});

	$('body').on('click', '.js-wishlist-remove', function (e) {
		var self = this;
		prestashop.emit('clickWishListRemove', {
			dataset: self.dataset
		});
		e.preventDefault();
	});

	$('body').on('click', '.js-wishlist-remove-all', function (e) {
		var self = this;
		prestashop.emit('clickWishListRemoveAll', {
			dataset: self.dataset
		});
		e.preventDefault();
	});

	prestashop.on('clickWishListAdd', function (el) {
		
		if($('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).hasClass("loading")){
			return;
		}
		
		var data = {
			'process': 'add',
			'ajax': 1,
			'idProduct': el.dataset.idProduct,
			'idProductAttribute': el.dataset.idProductAttribute
		};
		
		$('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).addClass('loading');
		
		$.post(wishListVar.actions, data, null, 'json').then(function (resp) {
			if(!resp.is_logged){
				if($('.vec-quicklogin-modal').length > 0){
					$('.vec-quicklogin-modal .modal-header').find('h3').hide();
					$('.wishlist-title').show();
					//Remove quick view modal
					$('.quickview').modal('hide');
					$('.quickview').on('hidden.bs.modal', function () {
			        	$('.quickview').remove();
			      	});	
			      	//Show login modal
					$('.vec-quicklogin-modal').modal('show');
				}else{
					var html = '';
					html += '<div class="modal" id="wishlistModal">';
					html += '<div class="modal-dialog animationShowPopup animated"><div class="modal-content">';
						html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
						html += '<div class="modal-body">';
						html += wishListVar.loggin_required_text;
						html += '<a class="btn-secondary" href="'+ wishListVar.login_url +'" class="login_text">'+ wishListVar.loggin_text +'</a>'
						html += '</div>';
					html += '</div></div></div>';
					$("body").append(html);

					if($('.quickview').length > 0){
						$('.quickview').modal('hide');
						$('.quickview').on('hidden.bs.modal', function () {
				        	$('.quickview').remove();
				        	$('#wishlistModal').modal('show');
				      	});	
					}else{
						$('#wishlistModal').modal('show');
					}
					
					$('#wishlistModal').on('hidden.bs.modal', function () {
			        	$('#wishlistModal').remove();
			      	});
				}
				$('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).removeClass('loading');
			}else{
				wishListVar.ids = resp.productsIds;
				$('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).removeClass('loading');
				wishlistRefreshStatus();	
			}
			
		}).fail(function (resp) {
			prestashop.emit('handleError', { eventType: 'clickWishListAdd', resp: resp });
		});
	});

	prestashop.on('clickWishListRemove', function (el) {

		if($('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).hasClass("loading")){
			return;
		}
		
		var data = {
			'process': 'remove',
			'ajax': 1,
			'idProduct': el.dataset.idProduct,
			'idProductAttribute': el.dataset.idProductAttribute
		};
		
		$('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).addClass('loading');
		
		$.post(wishListVar.actions, data, null, 'json').then(function (resp) {		
			
			if(!resp.is_logged){
				if($('.vec-quicklogin-modal').length > 0){
					$('.vec-quicklogin-modal .modal-header').find('h3').hide();
					$('.wishlist-title').show();
					$('.vec-quicklogin-modal').modal('show');
				}else{
					var html = '';
					html += '<div class="modal" id="wishlistModal">';
					html += '<div class="modal-dialog animationShowPopup animated"><div class="modal-content">';
						html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
						html += '<div class="modal-body">';
						html += wishListVar.loggin_required_text;
						html += '<a class="btn-secondary" href="'+ wishListVar.login_url +'" class="login_text">'+ wishListVar.loggin_text +'</a>'
						html += '</div>';
					html += '</div></div></div>';
					$("body").append(html);

					if($('.quickview').length > 0){
						$('.quickview').modal('hide')
						$('.quickview').on('hidden.bs.modal', function () {
				        	$('.quickview').remove();
				        	$('#wishlistModal').modal('show');
				      	});	
					}else{
						$('#wishlistModal').modal('show');
					}
					
					$('#wishlistModal').on('hidden.bs.modal', function () {
			        	$('#wishlistModal').remove();
			      	});
				}
			}else{
				$('.js-wishlist-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).remove();
				wishListVar.ids = resp.productsIds;
				wishlistRefreshStatus();
				if (wishListVar.ids.length == 0) {
					$('#js-wishlist-table').remove();
					$('#js-wishlist-warning').show();
				}
			}
			$('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).removeClass('loading');
								
		}).fail(function (resp) {
			prestashop.emit('handleError', { eventType: 'clickWishListRemove', resp: resp });
		});
	});

	prestashop.on('clickWishListRemoveAll', function (el) {
		
		if($('.js-wishlist-remove-all').hasClass('loading')){
			return;
		}
		
		var data = {
			'process': 'removeAll',
			'ajax': 1
		};
		
		$('.js-wishlist-remove-all').addClass('loading');
		
		$.post(wishListVar.actions, data, null, 'json').then(function (resp) {
			wishListVar.ids = resp.productsIds;
			wishlistRefreshStatus();
			
			$('#js-wishlist-table').remove();
			$('#js-wishlist-warning').show();
		}).fail(function (resp) {
			prestashop.emit('handleError', { eventType: 'clickWishListRemoveAll', resp: resp });
		});
	});
		
		
	function wishlistRefreshStatus()
	{
		$('.js-wishlist').each(function(){
			
			var $el = $(this);
			var $idProduct = $el.data('id-product');
			var $idProductAttribute = $el.data('id-product-attribute');		
			
			if (wishListVar.ids.includes($idProduct + '-' + $idProductAttribute)){
				$el.removeClass('js-wishlist-add').addClass('added').addClass('js-wishlist-remove');
				$el.attr('href', wishListVar.url);
				$el.find('.text').text(wishListVar.alert.view);
				if (typeof $(this).attr('data-original-title') !== typeof undefined && $(this).attr('data-original-title') !== false) {
					$el.attr('data-original-title', wishListVar.alert.view);
				}else{
					$el.attr('title', wishListVar.alert.view);
				}
			}else{
				$el.addClass('js-wishlist-add').removeClass('added').removeClass('js-wishlist-remove');
				$el.find('.text').text(wishListVar.alert.add);
				if (typeof $(this).attr('data-original-title') !== typeof undefined && $(this).attr('data-original-title') !== false) {
					$el.attr('data-original-title', wishListVar.alert.add);
				}else{
					$el.attr('title', wishListVar.alert.add);
				}
			}
			
			$el.addClass('js-wishlist-btn-'+$idProduct + '-' + $idProductAttribute);
			
		});
		
		$('.js-wishlist-count').text(wishListVar.ids.length);
						
	}
	
	$('#wishlist-copy-btn').on('click', function () {

		var $this = $(this);

		$this.closest('.input-group').find('input.js-to-copy').select();

		if (document.execCommand('copy')) {
			$this.text($this.data('textCopied'));
			setTimeout(function () {
				$this.text($this.data('textCopy'));
			}, 1500);
		}
		
	});
	
});