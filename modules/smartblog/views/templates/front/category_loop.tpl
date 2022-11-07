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
{if $columns == '1'}
    {assign var="class" value="col-xs-12"}
{else if $columns == '2'}
    {assign var="class" value="col-lg-6 col-md-6 col-sm-12 col-xs-12"}
{else if $columns == '3'}
    {assign var="class" value="col-lg-4 col-md-6 col-sm-12 col-xs-12"}
{else}
    {assign var="class" value="col-lg-3 col-md-6 col-sm-12 col-xs-12"}
{/if}
{if $post_style == '1'}
    {include file="module:smartblog/views/templates/front/post/style1.tpl" postcategory=$postcategory class=$class}
{else if $post_style == '2'}
    {include file="module:smartblog/views/templates/front/post/style2.tpl" postcategory=$postcategory class=$class}
{else if $post_style == '3'}
    {include file="module:smartblog/views/templates/front/post/style3.tpl" postcategory=$postcategory class=$class}
{else}
    {include file="module:smartblog/views/templates/front/post/style4.tpl" postcategory=$postcategory class=$class}
{/if}