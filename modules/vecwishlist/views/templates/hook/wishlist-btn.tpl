{if isset($id_product)}
<a class="btn-action btn-wishlist js-wishlist" data-id-product="{$id_product|intval}" data-id-product-attribute="{$id_product_attribute|intval}" title="{l s='Add to Wishlist' mod='vecwishlist'}">
<i class="vecicon-heart1"></i>{l s='Add to Wishlist' mod='vecwishlist'}
</a>
{/if}