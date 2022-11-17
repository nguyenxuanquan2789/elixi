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
{extends file='page.tpl'}

{block name="breadcrumb"}{/block}

{block name='page_header_container'}{/block}

{if $vectheme.404_content == 'default'}
  {capture assign="errorContent"}
    <div class="image-404">
    {if $vectheme.404_image}
      <img src="{$vectheme.404_image}" alt="404" />
    {else}
      404
    {/if}
    </div>
    <h4>
      {if $vectheme.404_text1}
        {$vectheme.404_text1}
      {else}
        {l s='Oops! That page can not be found.' d='Shop.Theme.Catalog'}
      {/if}
    </h4>
    <p>
      {if $vectheme.404_text2}
        {$vectheme.404_text2}
      {else}
        {l s='Stay tuned! More products will be shown here as they are added.' d='Shop.Theme.Catalog'}
      {/if}
    </p>
    <a href="{$urls.pages.index}" class="btn btn-primary">{l s='Back to homepage' d='Shop.Theme.Catalog'}</a>
    <a href="{$urls.pages.contact}" class="btn btn-primary">{l s='Contact us' d='Shop.Theme.Catalog'}</a>
  {/capture}

  {block name='page_content_container'}
    {include file='errors/not-found.tpl' errorContent=$errorContent}
  {/block}
{else}
  {block name='page_content_container'}
    {hook h="display404PageBuilder"}
  {/block}
{/if}
