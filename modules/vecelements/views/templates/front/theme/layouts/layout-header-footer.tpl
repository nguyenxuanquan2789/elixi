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
	{block name='hook_after_body_opening_tag'}
		{hook h='displayAfterBodyOpeningTag'}
	{/block}
	<main>
		{block name='product_activation'}
			{include file='catalog/_partials/product-activation.tpl'}
		{/block}
		<header id="header">
			{block name='header'}
				{include file='_partials/header.tpl'}
			{/block}
		</header>
		{block name='notifications'}
			{include file='_partials/notifications.tpl'}
		{/block}
		{block name="content"}{/block}
		<footer id="footer">
			{block name="footer"}
				{include file="_partials/footer.tpl"}
			{/block}
		</footer>
	</main>
	{block name='javascript_bottom'}
		{include file="_partials/javascript.tpl" javascript=$javascript.bottom}
	{/block}
	{block name='hook_before_body_closing_tag'}
		{hook h='displayBeforeBodyClosingTag'}
	{/block}
</body>
</html>