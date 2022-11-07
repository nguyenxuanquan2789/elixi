{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}

{extends file='page.tpl'}

{block name='page_content_container'}
	{if isset($vec_content)}
		{$vec_content.content|cefilter}
	{else}
		{$vec_template.content|cefilter}
	{/if}
{/block}
