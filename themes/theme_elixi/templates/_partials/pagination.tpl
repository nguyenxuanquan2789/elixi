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
<nav class="pagination">
  {block name='pagination_page_list'}
    {if $pagination.should_be_displayed}
      <ul class="page-list clearfix text-sm-center">
        {foreach from=$pagination.pages item="page"}
          <li {if $page.current} class="current" {/if}>
            {if $page.type === 'spacer'}
              <span class="spacer">&hellip;</span>
            {else}
              <a
                rel="{if $page.type === 'previous'}prev{elseif $page.type === 'next'}next{else}nofollow{/if}"
                href="{$page.url}"
                class="{if $page.type === 'previous'}previous {elseif $page.type === 'next'}next {/if}{['disabled' => !$page.clickable, 'js-search-link' => true]|classnames}"
              >
                {if $page.type === 'previous'}
                  <i class="vecicon-arrow_left"></i>
                {elseif $page.type === 'next'} 
                 <i class="vecicon-arrow_right"></i>
                {else}
                  {$page.page}
                {/if}
              </a>
            {/if}
          </li>
        {/foreach}
      </ul>
    {/if}
  {/block}
</nav>
