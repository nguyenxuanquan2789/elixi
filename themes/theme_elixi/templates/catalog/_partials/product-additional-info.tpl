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

<div class="product-additional-info js-product-additional-info">
  {block name='product_reference'}
    {if isset($product.reference_to_display) && $product.reference_to_display neq ''}
      <div class="product-reference">
        <label class="label">{l s='Reference' d='Shop.Theme.Catalog'} </label>: 
        <span>{$product.reference_to_display}</span>
      </div>
    {/if}
  {/block}
  {if isset($vectheme) && $vectheme.main_layout == '2'}
    {if isset($product_manufacturer->id) && isset($manufacturer_image_url)}
      <div class="product-brand">
        <label class="label">{l s='Brand' d='Shop.Theme.Catalog'} </label>: 
          <a href="{$product_brand_url}">
          {$product_manufacturer->name}
          </a>
      </div>
    {/if}
  {/if}
  {hook h='displayProductAdditionalInfo' product=$product}
</div>
