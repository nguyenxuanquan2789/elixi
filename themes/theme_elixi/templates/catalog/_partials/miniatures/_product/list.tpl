{block name='product_miniature_item'}
	<article class="thumbnail-container style_product_list product-miniature js-product-miniature item_in" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
		<div class="img-block">
		    {block name='product_thumbnail'}
				{if $product.cover}
				<a href="{$product.url}" class="thumbnail product-thumbnail rotator-animation-{$vectheme.rotator}">
				  <img class="first-image"
					src="{$product.cover.bySize.home_default.url}" width="{$product.cover.bySize.home_default.width}" height="{$product.cover.bySize.home_default.height}"
					alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
					data-full-size-image-url = "{$product.cover.large.url}" loading="lazy"
				  >
				   {if $vectheme.rotator}
						{foreach from=$product.images item=image}
							{if !$image.cover}
								<img
									src="{$image.bySize.home_default.url}"
									data-src="{$image.bySize.home_default.url}"
									width="{$image.bySize.home_default.width}"
									height="{$image.bySize.home_default.height}"
									alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if} 2"
									class="lazy-product-image product-thumbnail-rotator"  loading="lazy"
								>
								{break}
							{/if}
						{/foreach}
				   {/if}
				</a>
				{else}
				  <a href="{$product.url}" class="thumbnail product-thumbnail">
					<img src="{$urls.no_picture_image.bySize.home_default.url}" />
				  </a>
				{/if}
			{/block}
		</div>
		<div class="product-content">
			{if isset($product.id_manufacturer)}
				<div class="manufacturer"><a href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a></div>
			{/if}
			{block name='product_reviews'}
				<div class="hook-reviews">
				{hook h='displayProductListReviews' product=$product}
				</div>
			{/block}
			{block name='product_name'}
				<h3><a href="{$product.url}" class="product_name" title="{$product.name}">{$product.name|truncate:$vectheme.name_length:'...'}</a></h3> 
			{/block}
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
		</div>
	</article>
{/block}