{**
* 2007-2015 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel" id="vecmegamenu-list">
	<h3><i class="icon-list-ul"></i> {l s='Menu List' mod='vecmegamenu'}
	<span class="panel-heading-action">
		
	</span>
	</h3>
	<div id="menuContent">
		<div id="menus">
			{foreach from=$info_menus item=info_menu}
				<div id="menu_{$info_menu.id_vecmegamenu_item|intval}" class="panel">
					<div class="row">
						<div class="col-md-1"><i class="icon icon-arrows" style="font-size:16px; line-height:32px;"></i></div>
						<div class="col-md-2">
							<h4 class="pull-left">#{$info_menu.id_vecmegamenu_item|escape:'html':'UTF-8'}</h4>
						</div>
						<div class="col-md-2">
							<h4 class="pull-left">{$info_menu.title|escape:'html':'UTF-8'}</h4>
						</div>
						<div class="col-md-7">	
							<div class="btn-group-action pull-right">
								{if $info_menu.submenu_type != 2}
									<a class="btn btn-default"
										href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=vecmegamenu&id_vecmegamenu_item={$info_menu.id_vecmegamenu_item|intval}&buildMenu=1">
										<i class="icon-server"></i>
										{l s='Build SubMenu' mod='vecmegamenu'}
									</a>
								{/if}
								<a class="btn btn-default"
									href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=vecmegamenu&id_vecmegamenu_item={$info_menu.id_vecmegamenu_item|intval}&editMenu=1">
									<i class="icon-pencil"></i>
									{l s='Edit' mod='vecmegamenu'}
								</a>
								<a class="btn btn-default"
									href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=vecmegamenu&delete_id_menu={$info_menu.id_vecmegamenu_item|intval}">
									<i class="icon-trash-o"></i>
									{l s='Delete' mod='vecmegamenu'}
								</a>
								{$info_menu.status|escape:'quotes':'UTF-8'}
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
		<a id="desc-product-new" class="" href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=vecmegamenu&addMenu=1">
			<i class="icon-plus"></i><span title="" class="label-tooltip" data-html="true">{l s='Add new item' mod='vecmegamenu'}</span>
		</a>
	</div>	
	<script type="text/javascript">
		$(function() {
			var $myMenus = $("#menus");
			$myMenus.sortable({
				opacity: 0.6,
				cursor: "move",
				start: function(){
					$(this).css('background','#f1f1f1');
				},
				stop: function(){
					$(this).css('background','#ffffff');
				},
				update: function() {
					var order = $(this).sortable("serialize") + "&action=updateMenusPosition";
					$.post("{$url_base|escape:'html':'UTF-8'}modules/vecmegamenu/ajax_vecmegamenu.php?secure_key={$secure_key|escape:'html':'UTF-8'}", order);
					}
				});
			$myMenus.hover(function() {
				$(this).css("cursor","move");
				},
				function() {
				$(this).css("cursor","auto");
			});
		});
	</script>
</div>