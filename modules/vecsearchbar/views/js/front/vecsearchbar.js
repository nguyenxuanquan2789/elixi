/* global $ */
$(document).ready(function () {
	var flagSearch = false;
    $('.search-input').on('click', function(e){
    	e.stopPropagation();
    	var searchWidget = $(this).parents('.vec-search-widget'),
    		searchContainer = searchWidget.find('.search-container');
    	if(searchContainer.hasClass('js-dropdown')) return;

    	if($('.vec-overlay').hasClass('open')) return;
    
	    $('.vec-overlay').addClass('open');
	    $('body').addClass('search-open');
	    let searchSuggest = searchWidget.find('.search-suggest'),
	    	resultShow = searchSuggest.find('.suggest-ajax-results');
	    searchSuggest.removeClass('unvisible');

        if($('.search-suggest-products').length > 0 && $('.search-suggest-products').data('id_products').length > 0){
            ajaxSuggestProducts();
        }
	       
    	if(!flagSearch){
    		ajaxSearch(searchWidget);
    		flagSearch = true;
    	}
    	
    })
    $('.search-toggle').on('click', function(e){
    	e.preventDefault();
    	var searchWidget = $(this).parents('.vec-search-widget');
    	setTimeout(function() {
            searchWidget.find('.search-input').focus();
            if($('.vec-overlay').hasClass('open')) return;
    
		    $('.vec-overlay').addClass('open');
		    $('body').addClass('search-open');
		    let searchSuggest = searchWidget.find('.search-suggest'),
		    	searchContent = searchWidget.find('.search-content'),
		    	resultShow = searchSuggest.find('.suggest-ajax-results');
		    searchSuggest.removeClass('unvisible');
		    searchContent.removeClass('unvisible');

	        if($('.search-suggest-products').length > 0 && $('.search-suggest-products').data('id_products').length > 0){
	            ajaxSuggestProducts();
	        }
		        
		    
	    	if(!flagSearch){
	    		ajaxSearch(searchWidget);
	    		flagSearch = true;
	    	}
        },10);
    	if(!flagSearch){
    		ajaxSearch(searchWidget);
    		flagSearch = true;
    	}
    })
});

var ajaxSearch = function(searchWidget){
    let searchContainer = searchWidget.find('.search-container'),
        searchDropdown = searchWidget.find('.dropdown-menu'), 
        searchContent = searchWidget.find('.search-content'),
        searchForm    = searchContent.find('.search-form'),
        searchSuggest = searchContent.find('.search-suggest'),
        searchAjaxResults = searchSuggest.find('.suggest-ajax-results'),
        searchInput   = searchForm.find('.search-input'),
        searchURL     = searchForm.data('search-controller-url'),
        controller    = searchForm.find('input[name="controller"]').val(),
        order         = searchForm.find('input[name="order"]').val(),
        searchClear   = searchForm.find('.search-clear');

    $('.search-cat-value').on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id'),
            text = $(this).html();
        $('input[name="cat"]').val(id);
        $('.search-category-items > a > span').html(text)
    })
    
    if(searchContainer.hasClass('search-topbar') || searchContainer.hasClass('search-dropdown')){
        $('body').on('click', '.search-toggle', function(){
            searchContent.removeClass('unvisible');
        })
        $('.dialog-close-button').on('click', function(){
        	$('.search-toggle').dropdown('toggle');
            searchContent.addClass('unvisible');
            $('.vec-overlay').removeClass('open');
        	$('body').removeClass('search-open')
        })
        
    };
    searchInput
        .keyup(function() {
            if($(this).val().length >= 3){
                searchClear.removeClass('unvisible').addClass('loading_search');
                searchAjaxResults.html('').addClass('loading');
                searchSuggest.find('.suggest-content').addClass('unvisible');
                clearTimeout(timer);
                var timer = setTimeout(function() {
                    var limit = 10;
                    if(vecsearch.limit){
                        var limit = vecsearch.limit;
                    }
                    var data = {
                        's': searchInput.val(),
                        'resultsPerPage': limit,
                        'cat': $('#search-cat').val()
                    };
                    
                    $.post(searchURL, data , null, 'json')
                        .then(function (resp) {
                            searchClear.removeClass('loading_search');
                            var html = '';
                            html += '<div class="result-content">';
                                if(resp.products && resp.products.length > 0 ){
                                    for(var i=0 ; i<resp.products.length ; i++){
                                        html += '<div class="search-item">';
                                            html += '<a href="' + resp.products[i].url + '">';
                                                html += '<img src="'+ resp.products[i].cover.small.url +'" alt="" />';
                                                html += '<div class="product-infos">';
                                                    html += '<p class="product_name">'+ resp.products[i].name +'</p>';
                                                    if(resp.products[i].has_discount){
                                                        html += '<span class="product_old_price">'+ resp.products[i].regular_price +'</span>';
                                                    }
                                                    html += '<span class="product_price">'+ resp.products[i].price +'</span>';
                                                html += '</div>';
                                            html += '</a>';
                                        html += '</div>';   
                                    }
                                    html += '<a href="'+ searchURL +'?order='+ order +'&s='+ searchInput.val() +'">'+ vecsearch.view_more +'</a>';
                                }else{
                                    html += vecsearch.search_not_found;
                                }
                                
                            html += '</div>';
                            searchAjaxResults.removeClass('unvisible');
                            searchAjaxResults.html(html);

                        })
                });
            }else{
                searchAjaxResults.html('');
                searchClear.addClass('unvisible');
            }
        });

    searchClear.click(function(e){
        e.stopPropagation();
        e.preventDefault();
        searchInput.val('');
        searchAjaxResults.html('');
        searchSuggest.find('.suggest-content').removeClass('unvisible');
        $(this).addClass('unvisible');
        setTimeout(function() {
            searchInput.focus();
            searchInputClick();
        },10);
    });
    searchDropdown.on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
    })
    $('body').on('click', function(e) {
        searchDropdown.removeClass('dropdown-showed');
        searchSuggest.addClass('unvisible');
        $('.vec-overlay').removeClass('open');
        $('body').removeClass('search-open');     
    });
};

var ajaxSuggestProducts = function(){

	if(!$('.search-suggest-products').is(':empty')) return; 

	$('.search-suggest-products').addClass('loading');
    var ids = $('.search-suggest-products').data('id_products');
    var baseDir = vectheme.baseDir;
    $.ajax({
        type: 'POST',
        url: baseDir + 'module/vecsearchbar/suggestProducts',
        data: 'id_products=' + ids,
        dataType: 'json',
        success: function(data)
        {   
            var html = '<div class="search-suggest-products">';
            for(var i=0 ; i<data.products.length ; i++){
            	html += '<div class="search-item">';
                    html += '<a href="' + data.products[i].url + '">';
                        html += '<img src="'+ data.products[i].cover.small.url +'" alt="" />';
                        html += '<div class="product-infos">';
                            html += '<p class="product_name">'+ data.products[i].name +'</p>';
                            if(data.products[i].has_discount){
                                html += '<span class="product_old_price">'+ data.products[i].regular_price +'</span>';
                            }
                            html += '<span class="product_price">'+ data.products[i].price +'</span>';
                        html += '</div>';
                    html += '</a>';
                html += '</div>';
            }
            html += '</div>';
            $('.search-suggest-products').removeClass('loading');
            $('.search-suggest-products').append(html);
        }
    });
}