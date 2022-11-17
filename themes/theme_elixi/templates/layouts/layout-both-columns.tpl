{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

{include file='_partials/helpers.tpl'}

<!doctype html>
<html lang="{$language.locale}">

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

      <header id="header" class="{if $vectheme.header_sticky == '1'}use-sticky"{/if}"> 
        {block name='header'}
          {include file='_partials/header.tpl'}
        {/block}
      </header>

      <section id="wrapper">
        {if $page.page_name != 'index' && $page.page_name != 'module-vecelements-preview'}
        {block name='page_header_container'}
        <div class=" page-title-wrapper {if $vectheme.ptitle_size == 'small'}v_tilte_small{elseif $vectheme.ptitle_size == 'big'}v_tilte_big{/if}">
          <div class="container">
            {block name='page_title'}
            <header class="page-header">
              <h1>{$smarty.block.child}</h1> 
            </header>
            {/block}
          {block name='breadcrumb'}
            {include file='_partials/breadcrumb.tpl'}
          {/block}
          </div>
        </div>
        {/block}
        {/if}
        {block name='notifications'}
          {include file='_partials/notifications.tpl'}
        {/block}

        {hook h="displayWrapperTop"}
        <div class="{if $page.page_name != 'index' && $page.page_name != 'module-vecelements-preview'} container {/if}"> 
          <div class="row row-wrapper">
            {block name="left_column"}
              <div id="left-column" class="col-xs-12 col-xl-3">
                {if $page.page_name == 'product'}
                  {hook h='displayLeftColumnProduct'}
                {else}
                  {hook h="displayLeftColumn"}
                {/if}
              </div>
            {/block}

            {block name="content_wrapper"}
              <div id="content-wrapper" class="js-content-wrapper left-column right-column col-xs-12 col-xl-6">
                {hook h="displayContentWrapperTop"}
                {block name="content"}
                  <p>Hello world! This is HTML5 Boilerplate.</p>
                {/block}
                {hook h="displayContentWrapperBottom"}
              </div>
            {/block}

            {block name="right_column"}
              <div id="right-column" class="col-xs-12 col-xl-3">
                {if $page.page_name == 'product'}
                  {hook h='displayRightColumnProduct'}
                {else}
                  {hook h="displayRightColumn"}
                {/if}
              </div>
            {/block}
          </div>
		  {if $page.page_name == 'product'}
				{block name='product_accessories'}
					{if $accessories}
					<section class="products-accessories clearfix">
						<div class="product-title">
							<h2>{l s='You might also like' d='Shop.Theme.Catalog'}</h2>
						</div>
						<div class="elementor-slick-slider">
							<div class="product-accessoriesslide slick-arrows-inside slick-block items-desktop-5 items-tablet-3 items-mobile-2">
							{foreach from=$accessories item="product_accessory"}
							{block name='product_miniature'}
							<div>
							{include file='catalog/_partials/miniatures/product.tpl' product=$product_accessory}
							</div>
							{/block}
							{/foreach}
							</div>
						</div>
					</section>
					{/if}
				{/block}
				{block name='product_footer'}
				  {hook h='displayFooterProduct' product=$product category=$category}
				{/block}
			{/if}
        </div>
        {hook h="displayWrapperBottom"}
      </section>

      <footer id="footer" class="js-footer">
        {block name="footer"}
          {include file="_partials/footer.tpl"}
        {/block}
      </footer>

    </main>
    <div class="back-top"><a href= "#"><i class="vecicon-arrow_upward"></i></a></div>   
    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}
    {block name='hook_filter_canvas'}{/block}
    {block name='hook_before_body_closing_tag'}
      {hook h='displayBeforeBodyClosingTag'}
    {/block}
    <div class="vec-overlay"></div>
  </body>

</html>
