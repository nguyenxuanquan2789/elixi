{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{function name="blockCategTree" nodes=[] depth=0}
	{strip}
	{if $nodes|count}
		<ul>
		{foreach from=$nodes item=node}
			{if $node.name != ''}
				<li data-depth="{$depth}">
				{if $depth===0}
				<a href="{$node.link}" title="{$node.name}">{$node.name}</a>
					{if $node.children}
					
					
					<div class="category-sub-menu" id="exBlogCollapsingNavbar{$node.id}">
						{blockCategTree nodes=$node.children depth=$depth+1}
					</div>
					{/if}
				{else}
					<a class="category-sub-link" href="{$node.link}" title="{$node.name}">{$node.name}</a>
					{if $node.children}
					<div class="category-sub-menu" id="exBlogCollapsingNavbar{$node.id}">
						{blockCategTree nodes=$node.children depth=$depth+1}
					</div>
					{/if}
				{/if}
				</li>
			{/if}
		{/foreach}
		</ul>
	{/if}
	{/strip}
{/function}

{if $blockCategTree.children}
	<div class="block blog-categories smart-block">
		<h4 class="smart_blog_sidebar_title"><span>{l s='Blog Category' mod='smartblogcategories'}</span></h4> 
		<div class="block_content">{blockCategTree nodes=$blockCategTree.children}</div> 
	</div>
{/if}
