{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}
{if isset($CE_FOOTER)}
	{$CE_FOOTER|cefilter}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/checkout/_partials/footer.tpl")}
	{include '[1]checkout/_partials/footer.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{include 'parent:checkout/_partials/footer.tpl'}
{/if}