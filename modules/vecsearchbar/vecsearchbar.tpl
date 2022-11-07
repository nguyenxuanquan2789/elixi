{**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="vec-search-widget">
	<div class="search-container {if $search_type == 'dropdown' || $search_type == 'topbar'}js-dropdown{/if} search-{$search_type}" role="search">
		{if $search_type == 'dropdown' || $search_type == 'topbar'}
            <div class="search-toggle" data-toggle="dropdown">
                <i class="{$icon}" aria-hidden="true"></i>
            </div>
            <div class="dropdown-menu">
        {/if}
				<div class="search-content">
					<form class="search-form {if $show_categories && $search_type != 'minimal'}has-categories{/if}" action="{$search_controller_url}" data-search-controller-url="{$search_controller_url}" method="get">
						<div class="search-input-container">
							{if $search_type == 'minimal'}<i class="icon-minimal {$icon}" aria-hidden="true"></i>{/if}
							<input type="hidden" name="order" value="product.position.desc">
							<input class="search-input" type="search" name="s" autocomplete="off" placeholder="{$placeholder}" />
							{if $show_categories && $search_type != 'minimal'}
								<input type="hidden" name="cat" value="" id="search-cat">
								<div class="search-category-items">             	
									<a href="#" class="search-cat-value" data-id="0">{l s='All categories' mod='vecsearchbar'}<i class="vecicon-angle_down"></i></a>
									<ul class="dropdown-search">
										<li><a href="#" class="search-cat-value" data-id="0">{l s='All categories' mod='vecsearchbar'}</a></li>
										{$cateOptions nofilter}
									</ul>
								</div>
							{/if}
							<span class="search-clear unvisible"></span> 
						</div>
						{if $search_type == 'classic' || $search_type == 'topbar'}
						<button class="search-submit" type="submit">
							{if $button_type == 'icon'}
								<i class="{$icon}" aria-hidden="true"></i>
							{else}
								{$button_text}
							{/if}
						</button>
						{/if}
						{if $search_type == 'dropdown'}
						<button class="search-submit" type="submit">
							<i class="{$icon}" aria-hidden="true"></i>
						</button>
						{/if}
						{if $search_type == 'topbar'}
							<div class="dialog-lightbox-close-button dialog-close-button">
								<i class="vecicon-cross" aria-hidden="true"></i> 
							</div>
						{/if}
					</form>
					<div class="search-suggest {if $search_type == 'classic' || $search_type == 'minimal'} unvisible{/if}">
						<div class="search-suggest-container">
							<div class="suggest-ajax-results"></div>
							<div class="suggest-content">
								{if $keywords}
								<h5 class="search-dropdown-title search-keywords-title">{$keywords_title}</h5>
								<div class="search-keywords">
									{foreach from=$keywords item="keyword"}
										<a href="{$search_controller_url}?controller=search&s={$keyword}"><span>{$keyword}</span></a>
									{/foreach}
								</div>
								{/if}
								{if $suggest_status && $suggest_ids}
								<h5 class="search-suggest-title">{$suggest_title}</h5>
								<div class="search-suggest-products" data-id_products="{$suggest_ids}"></div>
								{/if}
							</div>
						</div>
					</div>
				</div>
        {if $search_type == 'dropdown' || $search_type == 'topbar'}
        	</div>
        {/if}
	</div>
</div>