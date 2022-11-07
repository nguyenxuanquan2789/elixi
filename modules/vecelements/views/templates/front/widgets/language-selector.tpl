{if $settings.skin == 'dropdown'}
<div class="language-widget language-dropdown vec-dropdown-wrapper js-dropdown">
	<div class="dropdown-toggle vec-dropdown-toggle" data-toggle="dropdown">
		{if $use_flag}<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$current_language.id_lang}.jpg" alt="{$current_language.name_simple}" width="16" height="11"/>{/if}
		{if $use_code}<span class="language-isocode">{$current_language.iso_code}</span>{/if}
		{if $use_name}<span class="language-name">{$current_language.name_simple}</span>{/if}
		<span class="icon-toggle vecicon-angle_down"></span>
	</div>
	<div class="dropdown-menu vec-dropdown-menu">
		{foreach from=$languages item=language}
			<a data-btn-language="{$language.id_lang}" href="{$language.url}" {if $language.current} class="selected"{/if}>
				{if $use_flag}<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11"/>{/if}
				{if $use_code}<span class="language-isocode">{$language.iso_code}</span>{/if}
				{if $use_name}<span class="language-name">{$language.name_simple}</span>{/if}
			</a>
		{/foreach}
	</div>
</div>
{else}
<div class="language-widget language-classic">
	{foreach from=$languages item=language}
		<a data-btn-language="{$language.id_lang}" href="{$language.url}" {if $language.current} class="selected"{/if}>
			{if $use_flag}<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11"/>{/if}
			{if $use_code}<span class="language-isocode">{$language.iso_code}</span>{/if}
			{if $use_name}<span class="language-name">{$language.name_simple}</span>{/if}
		</a>
	{/foreach}
</div>
{/if}