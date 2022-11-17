{*
 * Classic theme doesn't use this subtemplate, feel free to do whatever you need here.
 * This template is generated at each ajax calls.
 * See ProductListingFrontController::getAjaxProductSearchVariables()
 *}
<div id="js-product-list-bottom">
    {if $listing.products|count}
        {if isset($vectheme.category_pagination) && $vectheme.category_pagination != 'default'}
            {foreach from=$listing.pagination.pages key=page_key item=sort_order}
                {if $sort_order.current}
                    {if isset($listing.pagination.pages[$page_key+1]) && $listing.pagination.pages[$page_key+1].type != 'next'}
                        {assign var="infiniteUrl" value=$listing.pagination.pages[$page_key+1].url}
                    {/if}
                    {break}
                {/if}
            {/foreach}
            {if isset($infiniteUrl)}
                <div class="productlist-bottom">
                    <div class="productlist-load-wrapper">
                        <div class="productlist-load-button">
                            <a class="btn widget-productlist-trigger {$vectheme.category_pagination} {['js-search-link' => true]|classnames} {if isset($vectheme.category_pagination) && $vectheme.category_pagination == 'infinite'} trigger_infinite{/if}" href="{$infiniteUrl}" rel="nofollow">
                                {l s='More Products' d='Shop.Theme.Vec'}					
                            </a>
                            <div class="btn widget-productlist-loader" style="display:none;">
                                {l s='Loading...' d='Shop.Theme.Vec'}						
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
        {else}
            {block name='pagination'}
                {include file='_partials/pagination.tpl' pagination=$listing.pagination}
            {/block}
        {/if}
    {/if}
</div>
