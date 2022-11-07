{if $settings.skin == 'dropdown'}
<div class="currency-widget currency-dropdown vec-dropdown-wrapper js-dropdown">
	<div class="dropdown-toggle vec-dropdown-toggle" data-toggle="dropdown">
		{if $use_symbol}<span class="currency-symbol">{$current_currency.symbol}</span>{/if}
		{if $use_iso}<span class="currency-isocode">{$current_currency.iso_code}</span>{/if}
		{if $use_name}<span class="currency-name">{$current_currency.name}</span>{/if}
		<span class="icon-toggle vecicon-angle_down"></span>
	</div>
	<div class="dropdown-menu vec-dropdown-menu">
		{foreach from=$currencies item=currency}
			<a data-btn-currency="{$currency.id}" href="{$currency.url}" {if $currency.current} class="selected"{/if}>
				{if $use_symbol}<span class="currency-symbol">{$currency.symbol}</span>{/if}
				{if $use_iso}<span class="currency-isocode">{$currency.iso_code}</span>{/if}
				{if $use_name}<span class="currency-name">{$currency.name}</span>{/if}
			</a>
		{/foreach}
	</div>
</div>
{else}
<div class="currency-widget currency-classic">
	{foreach from=$currencies item=currency}
		<a data-btn-currency="{$currency.id}" href="{$currency.url}" {if $currency.current} class="selected"{/if}>
			{if $use_symbol}<span class="currency-symbol">{$currency.symbol}</span>{/if}
			{if $use_iso}<span class="currency-isocode">{$currency.iso_code}</span>{/if}
			{if $use_name}<span class="currency-name">{$currency.name}</span>{/if}
		</a>
	{/foreach}
</div>
{/if}