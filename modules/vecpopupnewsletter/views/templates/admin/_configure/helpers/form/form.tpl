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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}

{block name="script"}


$(document).ready(function() {

		 $('.iframe-upload').fancybox({	
			'width'		: 900,
			'height'	: 600,
			'type'		: 'iframe',
      		'autoScale' : false,
      		'autoDimensions': false,
      		 'fitToView' : false,
  			 'autoSize' : false,
  			 onUpdate : function(){ $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			 	 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));},
  			 afterShow: function(){
			 	 $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			 	 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
			}
  		  });
});

{/block}
{block name="input"}
	{if $input.type == 'background_image'}
                <p> <input id="{$input.name}" type="text" name="{$input.name}" value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"> </p>
                <a href="filemanager/dialog.php?type=1&field_id={$input.name}" class="btn btn-default iframe-upload"  data-input-name="{$input.name}" type="button">{l s='Background image selector' mod='vecpopupnewsletter'} <i class="icon-angle-right"></i></a>
	{elseif $input.type == 'image-select'}
		<div class="image-select {if isset($input.direction)} image-select-{$input.direction}{/if}">

			{foreach $input.options.query AS $option }
				<input id="{$input.name|escape:'html':'utf-8'}-{$option.id_option}" type="radio"
						name="{$input.name|escape:'html':'utf-8'}"
						value="{$option.id_option}" {if $fields_value[$input.name] == ''}{if $option@index eq 0} checked{/if}{/if} {if $option.id_option == $fields_value[$input.name]}checked{/if} />
				<div class="image-option">
					
					<label class="image-option-label"
							for="{$input.name|escape:'html':'utf-8'}-{$option.id_option}"></label>
					<img src="{$base_url}modules/vecpopupnewsletter/img/{$option.img}" alt="{$option.name}"
							class="img-responsive"/>
					<span class="image-option-title">{$option.name}</span>
				</div>
			{/foreach}
		</div>
	{else}
		{$smarty.block.parent}
    {/if}
{/block}



