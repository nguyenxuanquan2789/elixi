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
 {extends file=$layout}
 {block name='page_title'}
 {if $page.page_name == 'category'}
  {if $category.name}
   {$category.name}
 {/if}
 {/if}
 {/block}

{block name='head_microdata_special'}
  {include file='_partials/microdata/product-list-jsonld.tpl' listing=$listing}
{/block}

{if isset($vectheme.category_layout) && $vectheme.category_layout}
  {if $vectheme.category_layout == '1'}
    {include file="catalog/listing/_layout/left_column.tpl"} 
  {else if $vectheme.category_layout == '2'}
    {include file="catalog/listing/_layout/full_width.tpl"} 
  {else}
	  {include file="catalog/listing/_layout/right_column.tpl"} 
  {/if}
{/if}
