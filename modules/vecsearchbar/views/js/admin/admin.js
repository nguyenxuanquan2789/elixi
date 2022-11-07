jQuery(function($){
	$(document).ready(function(){
        var responsive = $('input[name="suggest_status"]');
        var current_responsive= $('input[name="suggest_status"]:checked').val();
        if(current_responsive == 0){
            $('.suggest-status').hide();  
        }
        
        responsive.change(function() {
            var value = $(this).val();
            if(value == 0){
                $('.suggest-status').hide();  
            }else{
                $('.suggest-status').show();  
            }
            
        });
    })
	var token_product = $('#admin_info').data('token_product'); 
    $('#product_name').autocomplete('ajax_products_list.php?token='+token_product+'&excludeIds=', {
        minChars: 1,
        autoFill: true,
        max:20,
        matchContains: true,
        mustMatch:true,
        scroll:false,
        cacheLength:0,
        extraParams:{ excludeIds:getMenuProductsIds()},
        formatItem: function(item) {
            if (item.length == 2) {
              return item[1]+' - '+item[0];  
            } else {
                return '--';
            }
        }
    }).result(function(event, data, formatted) {
        if (data == null || data.length != 2)
            return false;
        var productId = data[1];
        var productName = data[0];

        $('input[name=\'product_name\']').val('');
        $('#product' + productId).remove();

        var divProductName = $('#product-list');
        divProductName.append('<div id="product'+productId+'"><i class="icon-remove text-danger"></i>'+productName+'<input type="hidden" name="suggest_products[]" value="'+productId+'"/>');

        $('#product_name').setOptions({
            extraParams: {excludeIds : getMenuProductsIds()}
        });
    }); 

    $('#product-list').delegate('.icon-remove', 'click', function(){
        $(this).parent().remove();
    });

});

var getMenuProductsIds = function()
    {
        if (!$('#inputMenuProducts').val())
            return '-1';
        return $('#inputMenuProducts').val().replace(/\-/g,',');
    }