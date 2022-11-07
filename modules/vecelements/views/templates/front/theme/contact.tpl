{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}
{if isset($CE_PAGE_CONTACT)}
	{$ce_layout=$layout}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/contact.tpl")}
	{$ce_layout="{$smarty.const._PS_THEME_DIR_}templates/contact.tpl"}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{$ce_layout='parent:contact.tpl'}
{/if}

{extends $ce_layout}

{if isset($CE_PAGE_CONTACT)}
	{block name='content'}
	<section id="content">{$CE_PAGE_CONTACT|cefilter}</section>
	{/block}
{/if}