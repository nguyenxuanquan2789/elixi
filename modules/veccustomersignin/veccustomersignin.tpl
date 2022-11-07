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
<div class="vec-customersignin">
  {if $logged}
  	<div class="dropdown js-dropdown">
		<button data-target="#" data-toggle="dropdown" class="btn-unstyle my-account">
			{if $icon}<i class="{$icon}"></i>{/if}
		  	<span class="text-account">{l s='My account' mod='veccustomersignin'}</span>
		</button>
		<ul class="dropdown-menu">
			<li class="welcome-user">
				<a href="{$urls.pages.my_account}" rel="nofollow" class="dropdown-item">{l s='Welcome' mod='veccustomersignin'} , {$customerName}</a>
			</li>
			<li>
				<i class="vecicon-person3"></i><a href="{$urls.pages.my_account}" rel="nofollow" class="dropdown-item">{l s='My account' mod='veccustomersignin'}</a>
			</li>
			<li>
				<i class="vecicon-search4"></i><a href="{$urls.pages.history}" rel="nofollow" class="dropdown-item">{l s='Order history' mod='veccustomersignin'}</a>
			</li>
			<li>
				<i class="vecicon-gift2"></i><a href="{$urls.pages.discount}" rel="nofollow" class="dropdown-item">{l s='My voucher' mod='veccustomersignin'}</a>
			</li>
			<li class="logout">
				<a href="{$urls.actions.logout}" rel="nofollow">
					<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><path d="M0,0h24v24H0V0z" fill="none"/></g><g><path d="M17,8l-1.41,1.41L17.17,11H9v2h8.17l-1.58,1.58L17,16l4-4L17,8z M5,5h7V3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h7v-2H5V5z"/></g></svg>
					<span>{l s='Sign out' mod='veccustomersignin'}</span>
				</a>
			</li>
		</ul>
	</div>
  {else}
    <a class="login my-account" href="{$urls.pages.my_account}" rel="nofollow" title="{l s='Log in to your customer account' mod='veccustomersignin'}">{if $icon}<i class="{$icon}"></i>{/if}<span class="text-account">{l s='Sign in' mod='veccustomersignin'}</span></a>
  {/if}
</div>
