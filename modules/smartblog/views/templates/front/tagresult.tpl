{*
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


{extends file='page.tpl'}
{block name='page_header_container'}
  <div class=" page-title-wrapper {if $vectheme.ptitle_size == 'small'}p_tilte_small{elseif $vectheme.ptitle_size == 'big'}p_tilte_big{/if}">
      <div class="container">
        {block name='page_title'}
        <header class="page-header">
          <h1>
			{l s='Tag' mod='mymodule'}
		  </h1> 
        </header>
        {/block}
		<div class="breadcrumb smart-blog-breadcrumb">
		<span class="smart_blog-cat-text">{l s='Tag' mod='mymodule'}: </span>
		{* {$category.meta_title|nl2br nofilter} *}
		<span class="smart_blog-cat-text">{$keyword|nl2br nofilter}</span>
	    </div>
      </div>
  </div>
{/block}

{block name='page_content'}
    {capture name=path}<a href="{smartblog::GetSmartBlogLink('module-smartblog-list')|escape:'htmlall':'UTF-8'}">{l s='All Blog News'  d='Modules.Smartblog.Tagresult'}</a>
         {if $title_category != ''}
        <span class="navigation-pipe"></span>{$keyword|escape:'htmlall':'UTF-8'}{/if}{/capture}
     
        {if $postcategory == ''}
                 <p class="error">{l s='No Post in This Tag'  d='Modules.Smartblog.Tagresult'}</p>
        {else}
   
        <div id="smartblogcat" class="block row">
		{foreach from=$postcategory item=post}
			{include file="module:smartblog/views/templates/front/category_loop.tpl" post=$post smartbloglink = $smartbloglink}
		{/foreach}
        </div>
    {/if}
    {if isset($smartcustomcss)}
        <style>
            {$smartcustomcss|escape:'htmlall':'UTF-8'}
        </style>
    {/if}
{/block}

