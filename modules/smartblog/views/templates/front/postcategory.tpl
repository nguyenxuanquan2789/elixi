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
  <div class=" page-title-wrapper {if $vectheme.ptitle_size == 'small'}p_tilte_small{elseif $vectheme.ptitle_size == 'big'}p_tilte_big{/if}" {if $cat_image && $cat_image != "no"}style="background: url({$cat_image}) no-repeat;"{/if}>
      <div class="container">
        {block name='page_title'}
        <header class="page-header">
          <h1>
			{if $categoryinfo}
              {$categoryinfo[0].name}
            {else}
              {$blog_title} 
            {/if}
		  </h1> 
        </header>
        {/block}
        {block name='breadcrumb'}
          {if isset($breadcrumb)}
            <nav class="breadcrumb smart-blog-breadcrumb">
              <ol>
                  <li>
                    <a href="{$breadcrumb.links[0].url}">
                      <span itemprop="name">{$breadcrumb.links[0].title}</span>
                    </a>
                  </li>
                  <li>
                    <a href="{smartblog::GetSmartBlogLink('module-smartblog-list')}">
                    <span itemprop="name">{l s='All Post'  d='Modules.Smartblog.Postcategory'}</span>
                    </a>
                  </li>
                  {if $title_category != ''}
                  {assign var="link_category" value=null}
                  {$link_category.id_category = $id_category}
                  {$link_category.slug = $cat_link_rewrite}
                  <li>
                    <a href="{smartblog::GetSmartBlogLink('module-smartblog-category',$link_category)}">
                    <span itemprop="name">{$title_category}</span>
                    </a>
                  </li>
                {/if}
              </ol>
            </nav>
          {/if}
        {/block}
      </div>
  </div>
{/block}

{block name='page_content'}
    {capture name=path}
      <a href="{smartblog::GetSmartBlogLink('module-smartblog-list')|escape:'htmlall':'UTF-8'}">{l s='All Blog News'  d='Modules.Smartblog.Postcategory'}</a>
      {if $title_category != ''}<span class="navigation-pipe"></span>{$title_category|escape:'htmlall':'UTF-8'}{/if}
    {/capture}
    {if $postcategory == ''}
        {if $title_category != ''}
              <div class="alert alert-danger"><p>There is 1 error</p><ol><li>{l s='No Post in Category'  d='Modules.Smartblog.Postcategory'}</li></ol></div>
        {else}
          <div class="alert alert-danger"><p>There is 1 error</p><ol><li>{l s='No Post in Blog'  d='Modules.Smartblog.Postcategory'}</li></ol></div>
        {/if}
    {else}
      <div id="smartblogcat" class="block">
			<div class="row row-sdsarticleCat">
            {foreach from=$postcategory item=post}
                {include file="module:smartblog/views/templates/front/category_loop.tpl" postcategory=$postcategory}
            {/foreach}
			</div>
        </div>
        {if !empty($pagenums)}
          <div class="row bottom-pagination-content smart-blog-bottom-pagination">
            <div class="post-page col-md-12">
              <div id="pagination_bottom"> 
                <ul class="pagination">
                  {for $k=0 to $pagenums} 
                    {if ($k+1) == $c}
                      <li><span class="page-link page-active"><span>{$k+1|escape:'htmlall':'UTF-8'}</span></span></li>
                    {else}
                      {if $title_category != ''}
                        <li><a class="page-link" href="{$smartbloglink->getSmartBlogCategoryPagination($id_category,$cat_link_rewrite,$k+1)|escape:'htmlall':'UTF-8'}"><span>{$k+1|escape:'htmlall':'UTF-8'}</span></a></li> 
                      {else}
                          <li><a class="page-link" href="{$smartbloglink->getSmartBlogListPagination($k+1)|escape:'htmlall':'UTF-8'}"><span>{$k+1|escape:'htmlall':'UTF-8'}</span></a></li>
                      {/if}
                    {/if}
                  {/for}
                </ul>
              </div>
            </div>
          </div>
        {/if}
      {/if}
      {if isset($smartcustomcss)}
        <style>
          {$smartcustomcss|escape:'htmlall':'UTF-8'}
        </style>
      {/if}
{/block}