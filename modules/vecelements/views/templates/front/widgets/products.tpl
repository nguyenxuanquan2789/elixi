<div class="products-widget-grid items-desktop-{$columns.desktop} items-tablet-{$columns.tablet} items-mobile-{$columns.mobile}">
    {foreach from=$products item="product" key="position"}
        <div class="product-grid-item column-product">   
            {include file="catalog/_partials/miniatures/product.tpl" product=$product position=$position}    
        </div> 
    {/foreach}
</div>