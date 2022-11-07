
document.addEventListener("DOMContentLoaded", function (event) {
        $('textarea[name^=content_]').each(function (i, tag) {
        
            var button_html = $('#edit_with_crazy').html();
    
            $(button_html).insertBefore(tag);
      
        });
});