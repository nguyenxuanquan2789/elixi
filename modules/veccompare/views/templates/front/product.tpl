<article class="veccompare-product ">
	<div class="js-product-miniature">
		<div class="img-block">
			{block name='product_thumbnail'}
			<a href="{$product.url}" class="thumbnail product-thumbnail">
			  <img class="first-image"
				src = "{$product.cover.bySize.home_default.url}"
				alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
				data-full-size-image-url = "{$product.cover.large.url}"
			  >
			   {hook h="rotatorImg" product=$product}	
			</a>
		  {/block}
		</div>
		<div class="product-content">
			{block name='product_reviews'}
				<div class="hook-reviews">
				{hook h='displayProductListReviews' product=$product}
				</div>
			{/block} 
			{if isset($product.id_manufacturer)}
				<div class="manufacturer"><a href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a></div>
			{/if}
			<a href="{$product.url}" class="product_name">{$product.name|truncate:50:'...'}</a>
			{block name='product_price_and_shipping'}
			  {if $product.show_price}
				<div class="product-price-and-shipping">
				  {if $product.has_discount}
					{hook h='displayProductPriceBlock' product=$product type="old_price"}
					<span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
				  {/if}

				  {hook h='displayProductPriceBlock' product=$product type="before_price"}

				  <span class="price {if $product.has_discount}price-sale{/if}" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
					{capture name='custom_price'}{hook h='displayProductPriceBlock' product=$product type='custom_price' hook_origin='products_list'}{/capture}
					{if '' !== $smarty.capture.custom_price}
					  {$smarty.capture.custom_price nofilter}
					{else}
					  {$product.price}
					{/if}
				  </span>
					
				  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

				  {hook h='displayProductPriceBlock' product=$product type='weight'}
				  {if $product.has_discount}
					{if $product.discount_type === 'percentage'}
					  <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
					{elseif $product.discount_type === 'amount'}
					  <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
					{/if}
				  {/if}
				</div>
			  {/if}
			{/block}
			<div class="cart">
				{include file='catalog/_partials/miniatures/customize/button-cart.tpl' product=$product}
			</div>	
		</div>			
	</div>
	<a href="#" class="js-compare-remove compare-remove" data-id-product="{$product.id_product|intval}">{l s='Remove' mod='veccompare'}</a>
</article>