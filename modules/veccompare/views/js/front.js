var vecCompare = {
    addCompare: function(selector, id){
        selector.addClass('loading');
        $.ajax({
            type: 'POST',
            url: veccompare.baseDir + 'module/veccompare/actions',
            dataType: 'json',
            data: {
                action : 'add',
                id: id,
                ajax : true
            },
            success: function(data)
            {  
                veccompare.nbProducts++;
                $('.js-compare-count, #qmcompare-count').text(veccompare.nbProducts);
                selector.removeClass('loading');
                selector.addClass('cmp_added').removeClass('js-compare-add').addClass('js-compare-remove');
                selector.attr('title',veccompare.remove_text);
            }
        })
        
    },
    removeCompare: function(selector, id){
        selector.addClass('loading');
        $.ajax({
            type: 'POST',
            url: veccompare.baseDir + 'module/veccompare/actions',
            dataType: 'json',
            data: {
                action : 'remove',
                id: id,
                ajax : true
            },
            success: function(data)
            {
                $('.js-veccompare-product-' + id).remove();
                veccompare.nbProducts--;
                $('.js-compare-count, #qmcompare-count').text(veccompare.nbProducts);

                if (veccompare.nbProducts == 0) {
                    $('#veccompare-table').remove();
                    $('#veccompare-warning').removeClass('hidden-xs-up');
                }
                selector.removeClass('loading');
                selector.removeClass('cmp_added').removeClass('js-compare-remove').addClass('js-compare-add');
                selector.attr('title',veccompare.add_text);
            }
        })
        
    },
    removeAllCompare: function(){
        $('.js-compare-remove-all').addClass('loading');
        $.ajax({
            type: 'POST',
            url: veccompare.baseDir + 'module/veccompare/actions',
            dataType: 'json',
            data: {
                action : 'removeAll',
                ajax : true
            },
            success: function(data)

            {
                $('.js-compare-count, #qmcompare-count').text(0);
                $('.js-compare-remove-all').removeClass('loading');
                $('#veccompare-table').remove();
                $('#veccompare-warning').removeClass('hidden-xs-up');
            }
        })
    },
    checkCompare : function (){
        var target = $('.js-compare-add');
        var compareList = veccompare.idProducts;
        target.each(function(){
            var $id = $(this).data('id-product');
            var flag = false;
            $.each( compareList, function( key, value ) {
              if($id == value) {
                flag = true;
              };
            });
            if(flag) {
                $(this).addClass('cmp_added').removeClass('js-compare-add').addClass('js-compare-remove');
                $(this).attr('title',veccompare.remove_text);
            }
        })
    },
};

$(document).ready(function () { 
    vecCompare.checkCompare();
    $('.js-compare-count, #qmcompare-count').text(veccompare.nbProducts);
    $('body').on('click', '.js-compare-remove-all', function (event) {
        vecCompare.removeAllCompare();
        event.preventDefault();
    });
    $("body").on('click', '.js-compare-add', function(e) {
        console.log($(this));
        e.preventDefault();
        if($(this).hasClass('loading')) return; 

        var id = $(this).data('id-product');
        vecCompare.addCompare($(this), id);
    }); 
    $("body").on('click', '.js-compare-remove', function(e) {
        console.log($(this));
        e.preventDefault();
        if($(this).hasClass('loading')) return; 

        var id = $(this).data('id-product');
        vecCompare.removeCompare($(this), id);
    }); 
});

