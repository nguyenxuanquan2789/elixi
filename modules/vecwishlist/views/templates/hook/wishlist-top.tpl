<a class="btn-wishlist-top btn-header" href="{url entity='module' name='vecwishlist' controller='view'}" title="{l s='My Wishlist' mod='vecwishlist'}">
    {if isset($icon) && $icon}
        <i class="wishlist-top-icon {$icon}"></i>  
    {/if}
    <span class="btn-wishlist-text">{l s='My Wishlist' mod='vecwishlist'}</span>
    <span class="js-wishlist-count wishlist-count">0</span>
</a>