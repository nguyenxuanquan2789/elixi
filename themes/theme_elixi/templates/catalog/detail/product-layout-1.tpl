<div class="col-md-6">
    {block name='page_content_container'}
        <section class="page-content" id="content">
        {block name='page_content'}
            {block name='product_cover_thumbnails'}
                {include file='catalog/_partials/product-cover-thumbnails.tpl'}
            {/block}
        {/block}
        </section>
    {/block}
</div>
<div class="col-md-6">
    {block name='page_header_container'}
        {block name='page_header'}
            <h1 class="h1 namne_details" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
        {/block}
    {/block}
    {if isset($product_manufacturer->id) && isset($manufacturer_image_url)}
        <div class="product-brand">
            <a href="{$product_brand_url}">
                <img class="img-responsive" src="{$manufacturer_image_url}" title="{$product_manufacturer->name}" alt="{$product_manufacturer->name}"  loading="lazy">
            </a>
        </div>
    {/if}
    {hook h="displayReviewsProduct"}
    {block name='product_prices'}
        {include file='catalog/_partials/product-prices.tpl'}
    {/block}

    <div class="product-information">
        {block name='product_description_short'}
        <div id="product-description-short-{$product.id}" class="product-description" itemprop="description">{$product.description_short nofilter}</div>
        {/block}

        {if $product.is_customizable && count($product.customizations.fields)}
        {block name='product_customization'}
        {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
        {/block}
        {/if}

        <div class="product-actions">
        {block name='product_buy'}
        <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
            <input type="hidden" name="token" value="{$static_token}">
            <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
            <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">
            {block name='product_variants'}
            {include file='catalog/_partials/product-variants.tpl'}
            {/block}
            {hook h='displaySizeChart'}	
            {block name='product_pack'}
            {if $packItems}
            <section class="product-pack">
            <p class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
            {foreach from=$packItems item="product_pack"}
                {block name='product_miniature'}
                {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack showPackProductsPrice=$product.show_price}
                {/block}
            {/foreach}
            </section>
            {/if}
            {/block}

            {block name='product_discounts'}
            {include file='catalog/_partials/product-discounts.tpl'}
            {/block}

            {block name='product_add_to_cart'}
            {include file='catalog/_partials/product-add-to-cart.tpl'}
            {/block}

            {block name='product_additional_info'}
            {include file='catalog/_partials/product-additional-info.tpl'}
            {/block}

            {* Input to refresh product HTML removed, block kept for compatibility with themes *}
            {block name='product_refresh'}{/block}
        </form>
        {/block}

        </div>

        {block name='hook_display_reassurance'}
        {hook h='displayReassurance'}
        {/block}

    </div>
</div>
