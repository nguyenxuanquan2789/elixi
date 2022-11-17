{block name='product_miniature_item'}
	<article class="thumbnail-container product-miniature-grid4 product-miniature js-product-miniature item_in"
		data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
		<div class="img-block">
			{block name='product_thumbnail'}
				{if $product.cover}
					<a href="{$product.url}" class="thumbnail product-thumbnail rotator-animation-{$vectheme.rotator}">
						<img class="first-image" src="{$product.cover.bySize.home_default.url}"
							width="{$product.cover.bySize.home_default.width}" height="{$product.cover.bySize.home_default.height}"
							alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
							data-full-size-image-url="{$product.cover.large.url}">
						{if $vectheme.rotator}
							{foreach from=$product.images item=image}
								{if !$image.cover}
									<img src="{$image.bySize.home_default.url}" data-src="{$image.bySize.home_default.url}"
										width="{$image.bySize.home_default.width}" height="{$image.bySize.home_default.height}"
										alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if} 2"
										class="lazy-product-image product-thumbnail-rotator" loading="lazy">
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
			{block name='quick_view'}
				<a class="quick-view js-quick-view" href="#" data-link-action="quickview"
					title="{l s='Quick view' d='Shop.Theme.Actions'}">
					<span>{l s='Quick view' d='Shop.Theme.Actions'}</span>
				</a>
			{/block}
			<div class="add-links">
				{hook h='displayProductListFunctionalButtons' product=$product}
				{hook h='displayWishlistButton' product=$product}
			</div>
			{block name='product_flags'}
				<ul class="product-flag">
					{foreach from=$product.flags item=flag}
						<li class="{$flag.type}"><span>{$flag.label}</span></li>
					{/foreach}
				</ul>
			{/block}
			{if isset($product.specific_prices.to) && $product.specific_prices.to|strtotime > $smarty.now && $product.specific_prices.from|strtotime < $smarty.now}
				<div class="countdown-timer">
					<span class="specific-prices-timer" data-date-y='{$product.specific_prices.to|date_format:"%Y"}'
						data-date-m='{$product.specific_prices.to|date_format:"%m"}'
						data-date-d='{$product.specific_prices.to|date_format:"%d"}'
						data-date-h='{$product.specific_prices.to|date_format:"%H"}'
						data-date-mi='{$product.specific_prices.to|date_format:"%M"}'
						data-date-s='{$product.specific_prices.to|date_format:"%S"}'>
					</span>

				</div>
			{/if}
		</div>
		<div class="product-content">
			{if isset($product.id_manufacturer)}
				<div class="manufacturer"><a
						href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a>
				</div>
			{/if}
			{block name='product_reviews'}
				<div class="hook-reviews">
					{hook h='displayProductListReviews' product=$product}
				</div>
			{/block}
			{block name='product_name'}
				<h3><a href="{$product.url}" class="product_name"
						title="{$product.name}">{$product.name|truncate:$vectheme.name_length:'...'}</a></h3>
			{/block}
			<div class="inner-content">
				<div class="left-content">
					{block name='product_price_and_shipping'}
						{if $product.show_price}
							<div class="product-price-and-shipping">
								{if $product.has_discount}
									{hook h='displayProductPriceBlock' product=$product type="old_price"}
									<span class="regular-price"
										aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
								{/if}

								{hook h='displayProductPriceBlock' product=$product type="before_price"}

								<span class="price {if $product.has_discount}price-sale{/if}"
									aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
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
				<div class="right-content">
					{include file='catalog/_partials/miniatures/customize/button-cart.tpl' product=$product}
				</div>
			</div>
			{block name='product_variants'}
				{if $product.main_variants}
					{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
				{/if}
			{/block}
		</div>
	</article>
{/block}