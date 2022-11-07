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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if ((($nbComments_extra == 0 && $too_early == false && ($logged || $allow_guests)) || ($nbComments_extra != 0)))}
<div id="product_comments_block_extra" class="no-print" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
	{if $nbComments_extra != 0}
		<div class="comments_note clearfix">
			<div class="star_content clearfix">
				<span class="rating_star" style="width: {$avg_percent}%;"></span>
				<meta itemprop="worstRating" content = "0" />
				<meta itemprop="ratingValue" content = "{if isset($ratings.avg)}{$ratings.avg|round:1|escape:'html':'UTF-8'}{else}{$averageTotal_extra|round:1|escape:'html':'UTF-8'}{/if}" />
				<meta itemprop="bestRating" content = "5" />
			</div>
		</div> <!-- .comments_note -->
	{/if}
	{if $nbComments_extra != 0}
		<div class="comments_advices">
			<a href="#product_comments_block_tab" class="reviews" >
				{$ratings.avg|round:2} | {$nbComments_extra} {l s='Reviews' mod='vecproductcomments'}
			</a>
		</div>
	{/if}
</div>
{/if}
<!--  /Module ProductComments -->
