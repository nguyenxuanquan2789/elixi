{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">
   var vecproductcomments_controller_url = '{$vecproductcomments_controller_url}';
   var secure_key = '{$secure_key}';
   var vecproductcomments_url_rewrite = '{$vecproductcomments_url_rewriting_activated}';
   var productcomment_added = '{l s='Your comment has been added!' mod='vecproductcomments' js=1}';
   var productcomment_added_moderation = '{l s='Your comment has been submitted and will be available once approved by a moderator.' mod='vecproductcomments' js=1}';
   var productcomment_title = '{l s='New comment' mod='vecproductcomments' js=1}';
   var productcomment_ok = '{l s='OK' mod='vecproductcomments' js=1}';
   var moderation_active = {$moderation_active};
</script>
<div id="product_comments_block_tab" class="tab-pane fade in"> 
  {if $comments}
  <h2 class="reviews-title">{l s='Customer Reviews' mod='vecproductcomments'}</h2>
  <div class="reviews-header flex-layout space-between center-vertical">
  	<div class="rating_aggregate">
		<div class="star_content clearfix">
			<span class="rating_star" style="width: {$avg_percent}%;"></span> 
		</div>
		{if $nbComments == 1}
			<span class="nb-comments">{l s='Based on %s review' sprintf=[$nbComments] mod='vecproductcomments'}</span>
		{else}
			<span class="nb-comments">{l s='Based on %s reviews' sprintf=[$nbComments] mod='vecproductcomments'}</span>
		{/if}
	</div>
	{if (!$too_early AND ($logged OR $allow_guests))}
		<a id="new_comment_tab_btn" class="open-comment-form btn btn-secondary" >{l s='Write your review' mod='vecproductcomments'} !</a>
	{/if}
  </div>
  <div class="reviews-content">
	  {foreach from=$comments item=comment}
	  {if $comment.content}
	  <div class="comment clearfix">
		 <div class="comment_author">
			
			<div class="star_content clearfix">
			   {section name="i" start=0 loop=5 step=1}
			   {if $comment.grade le $smarty.section.i.index}
			   <div class="star"></div>
			   {else}
			   <div class="star star_on"></div>
			   {/if}
			   {/section}
			</div>
			<div class="comment_author_infos">
			   <span class="author-reviews">{$comment.customer_name|escape:'html':'UTF-8'}</span>
			   <span>{l s='on' mod='vecproductcomments'}&nbsp</span>
			   <span>{$comment.date_add|date_format}</span>
			</div>
		 </div>
		 <div class="comment_details">
			<h4 class="title_block">{$comment.title}</h4>
			<p>{$comment.content|escape:'html':'UTF-8'|nl2br}</p>
		 </div>
	  </div>
	  {/if}
	  {/foreach}
	</div>
  {else}
	  {if (!$too_early AND ($logged OR $allow_guests))}
	  <p class="align_center">
		 <a id="new_comment_tab_btn" class="btn btn-secondary">{l s='Be the first to write your review' mod='vecproductcomments'} !</a>
	  </p>
	  {else}
	  <p class="align_center">{l s='No customer reviews for the moment.' mod='vecproductcomments'}</p>
	  {/if}
  {/if}	
</div>


<!-- Trigger the modal with a button -->
<!-- Modal -->
<div class="modal" id="vec-product-comment-modal" role="dialog">
  <div class="modal-dialog animationShowPopup animated">
     <!-- Modal content-->
     <div class="modal-content">
        <div class="modal-body">
            <div id="new_comment_form">
              <form id="id_new_comment_form" action="#">
					<h2>{l s='Write your review' mod='vecproductcomments'}</h2>
					<div class="content_comment_form">
						{if isset($product) && $product}
						 <div class="product clearfix">
							<img src="{$productcomment_cover_image}" alt="{$product->name|escape:html:'UTF-8'}" />
							<div class="product_desc">
							   <p class="product_name">{$product->name}</p>
							</div>
						 </div>
						 {/if}
						 <div class="new_comment_form_content">
							<div id="new_comment_form_error" class="error" style="display:none;padding:15px 25px">
							   <ul></ul>
							</div>
							{if $criterions|@count > 0}
							<ul id="criterions_list">
							   {foreach from=$criterions item='criterion'}
							   <li>
								  <label>{$criterion.name|escape:'html':'UTF-8'}</label>
								  <div class="star_content">
									 <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="1" />
									 <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="2" />
									 <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="3" />
									 <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="4" />
									 <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="5" checked="checked" />
								  </div>
								  <div class="clearfix"></div>
							   </li>
							   {/foreach}
							</ul>
							{/if}
							<label for="comment_title">{l s='Title for your review' mod='vecproductcomments'}<sup class="required">*</sup></label>
							<input id="comment_title" name="title" class="form-control" type="text" value=""/>
							{if $allow_guests == true && !$logged}
							<label>{l s='Your name' mod='vecproductcomments'}<sup class="required">*</sup></label>
							<input id="commentCustomerName" class="form-control" name="customer_name" type="text" value=""/>
							{/if}
	
							<label for="content">{l s='Your review' mod='vecproductcomments'}<sup class="required">*</sup></label>
							<textarea class="form-control" name="content"></textarea> 
							<div id="new_comment_form_footer">
							   <input id="id_product_comment_send" class="form-control" name="id_product" type="hidden" value='{$id_product_comment_form}' />
							   <p class=" required" style="margin-bottom:10px;"><sup>*</sup> {l s='Required fields' mod='vecproductcomments'}</p>
								<p class="button_comment">							
									<button type="button" class="closefb btn btn-secondary" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">{l s='Cancel' mod='vecproductcomments'}</span>
									</button>
									<button id="submitNewMessage" class ="btn btn-secondary" name="submitMessage" type="submit">{l s='Send' mod='vecproductcomments'}</button>
								</p>
							   <div class="clearfix"></div>
							</div>
						 </div>
					</div>					
              </form>
              <!-- /end new_comment_form_content -->
            </div>
            <div id="result_comment" style="display: none; text-align: center;">
            	<h4>{l s='Thank you for the reviews ! Your comment is submitted' mod='vecproductcomments'}</h4>
            </div>
        </div>
     </div>
  </div>
</div>

