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
<div class="language-selector dropdown js-dropdown">
	<button data-toggle="dropdown" class="btn-unstyle" aria-haspopup="true" aria-expanded="false"
		aria-label="{l s='Language dropdown' d='Shop.Theme.Global'}">
		<img src="{$urls.img_lang_url}{$current_language.id_lang}.jpg" alt="" width="16" height="11" />
		<span class="expand-more">{$current_language.name_simple}</span>
		<i class="elicon-arrow_more"></i>
	</button>
	<ul class="dropdown-menu" aria-labelledby="language-selector-label">
		{foreach from=$languages item=language}
			<li {if $language.id_lang == $current_language.id_lang} class="current" {/if}>
				<a href="{url entity='language' id=$language.id_lang}" class="dropdown-item"
					data-iso-code="{$language.iso_code}"><img src="{$urls.img_lang_url}{$language.id_lang}.jpg" alt=""
						width="16" height="11" />{$language.name_simple}</a>
			</li>
		{/foreach}
	</ul>
</div>