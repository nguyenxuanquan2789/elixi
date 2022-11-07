{extends file='page.tpl'}
{block name='page_title'}
<header class="page-header">
  <h1>{l s='Products compare' mod='veccompare'}</h1>
</header>
{/block}
{block name='page_content'}
    {if isset($compareProducts) && $compareProducts}
        <div id="veccompare-table">
            <div class="veccompare-table-container">
                <div class="table table-hover">
  
                    <div class="veccompare-product-tr">
						<div class="veccompare-table-actions feature-name veccompare-product-td">
							<a href="#" class="js-compare-remove-all compare-remove-all"
							   data-url="{url entity='module' name='veccompare' controller='actions'}">
								{l s='Remove all products' mod='veccompare'}
							</a>
						</div>
  
                        {foreach from=$compareProducts item="compareProduct"}
                            <div class="veccompare-product-td js-veccompare-product-{$compareProduct.id_product}">
                                {include 'module:veccompare/views/templates/front/product.tpl' product=$compareProduct}
                            </div>
                        {/foreach}
                    </div>
                    {if $orderedFeatures}
                        {foreach from=$orderedFeatures item=feature}
                            <div class="veccompare-product-tr veccompare-product-row">
                                {cycle values='comparison_feature_odd,comparison_feature_even' assign='classname'}
                                <div class="{$classname} feature-name veccompare-product-td">
                                    {$feature.name}
                                </div>
                                {foreach from=$compareProducts item="product"}

                                    {assign var='product_id' value=$product.id_product}
                                    {assign var='feature_id' value=$feature.id_feature}

                                    {if isset($listFeatures[$product_id])}
                                        {assign var='tab' value=$listFeatures[$product_id]}
                                        <div class="{$classname} veccompare-feature-td veccompare-product-td js-veccompare-product-{$product.id_product}">
                                            {if (isset($tab[$feature_id]))}
                                                {foreach from=$tab[$feature_id] item=tabfeature}
                                                    {$tabfeature|escape:'htmlall'|nl2br nofilter}
                                                {/foreach}
                                                {/if}
                                        </div>
                                    {else}
                                        <div class="{$classname} veccompare-feature-td js-veccompare-product-{$product.id_product} veccompare-product-td">
                                            ---
                                        </div>
                                    {/if}

                                {/foreach}
                            </div>
                        {/foreach}
                    {else}
                        <div>
                            <div colspan="{$compareProducts|@count}">{l s='No features to compare.' mod='veccompare'}</div>
                        </div>
                    {/if}

                </div>
            </div>
        </div>
        <p class="alert alert-warning hidden-xs-up"
           id="veccompare-warning">{l s='There is no products to compare.' mod='veccompare'}</p>
    {else}
        <p class="alert alert-warning">{l s='There is no products to compare.' mod='veccompare'}</p>
    {/if}
{/block}


