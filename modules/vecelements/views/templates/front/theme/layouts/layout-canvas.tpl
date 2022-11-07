{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}
<!doctype html>
<html lang="{$language.iso_code}">
<head>
	{block name='head'}
		{include file='_partials/head.tpl'}
	{/block}
</head>
<body id="{$page.page_name}" class="{$page.body_classes|classnames}">
	<main>
		{block name='notifications'}
			{include file='_partials/notifications.tpl'}
		{/block}
		{$ce_desc['description']|cefilter}
	</main>
	{block name='javascript_bottom'}
		{include file="_partials/javascript.tpl" javascript=$javascript.bottom}
	{/block}
</body>
</html>