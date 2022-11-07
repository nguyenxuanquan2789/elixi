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

<form id="module_form" class="defaultForm form-horizontal themevec-module" action="index.php?controller=AdminModules&amp;configure=vecvegamenu&amp;token={Tools::getAdminTokenLite('AdminModules')|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data" novalidate="">
<div class="panel"><h3><i class="icon-list-ul"></i> {l s='Menu Item' mod='vecvegamenu'}</h3>
	
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Active' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input type="radio" name="active" id="active_on" value="1" {if (isset($menu->active) &&  $menu->active != 0) || !$menu->active}checked="checked"{/if}>
				<label for="active_on">Yes</label>
				<input type="radio" name="active" id="active_off" value="0" {if isset($menu->active) && $menu->active == 0}checked="checked"{/if}>
				<label for="active_off">No</label>
				<a class="slide-button btn"></a>
			</span>	
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Title' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			{foreach from=$languages item=language}
				{if $languages|count > 1}
					<div class="row translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_language}style="display:none"{/if}>
				{/if}
				<div class="col-lg-6">
				<input type="text" class="title" id="title_{$language.id_lang|intval}" name="title_{$language.id_lang|intval}" value="{if isset($menu->title[$language.id_lang|intval])}{$menu->title[$language.id_lang|intval]}{else}menu title{/if}"/>
				</div>
				{if $languages|count > 1}
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$language.iso_code|escape:'html':'UTF-8'}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});javascript:changeLangInfor({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if $languages|count > 1}
					</div>
				{/if}
			{/foreach}
		</div>
	</div>
	<div class="form-group lab-type-link">
		<label class="control-label col-lg-3">{l s='Type Link' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			<select id="type_link" name="type_link" class="form-control fixed-width-xl" data-default="0">
                <option value="0" {if $menu->type_link == 0}selected="selected" {/if}>{l s='PrestaShop Link' mod='vecvegamenu'}</option>
                <option value="1" {if $menu->type_link == 1}selected="selected" {/if}>{l s='Custom Link' mod='vecvegamenu'}</option>
                <option value="2" {if $menu->type_link == 2}selected="selected" {/if}>{l s='None' mod='vecvegamenu'}</option>
            </select>
		</div>
	</div>
	
	<div class="form-group custom_link">
		<label class="control-label col-lg-3">{l s='Custom link' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			{foreach from=$languages item=language}
				{if $languages|count > 1}
					<div class="row translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_language}style="display:none"{/if}>
				{/if}
				<div class="col-lg-6">
				<input type="text" id="custom_link_{$language.id_lang|intval}" name="custom_link_{$language.id_lang|intval}" value="{if isset($menu->custom_link[$language.id_lang|intval])}{$menu->custom_link[$language.id_lang|intval]}{else}#{/if}"/>
				</div>
				{if $languages|count > 1}
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$language.iso_code|escape:'html':'UTF-8'}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});javascript:changeLangInfor({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if $languages|count > 1}
					</div>
				{/if}
			{/foreach}
		</div>
	</div>
	<div class="form-group ps_link">
		<label class="control-label col-lg-3">{l s='PrestaShop Link' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			<select class="form-control fixed-width-xl" name="link" id="link">
				{$all_options|escape:'quotes':'UTF-8'}
			</select>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#link").val('{$menu->link}');
				});
			</script>
		</div>
	</div>
	<div class="form-group lab-type-icon">
		<label class="control-label col-lg-3">{l s='Use icon' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			<select id="type_icon" name="type_icon" class="form-control fixed-width-xl" data-default="0">
                <option value="0" {if $menu->type_icon == 0}selected="selected" {/if}>{l s='No' mod='vecvegamenu'}</option>
                <option value="1" {if $menu->type_icon == 1}selected="selected" {/if}>{l s='Theme & Awesome icons' mod='vecvegamenu'}</option>
                <option value="2" {if $menu->type_icon == 2}selected="selected" {/if}>{l s='Image icon' mod='vecvegamenu'}</option>
            </select>
		</div>
	</div>
	
	<div class="form-group lab-fw-icon">
		<label class="control-label col-lg-3">{l s='Theme & Awesome Icon' mod='vecvegamenu'}</label>
		<div class="col-lg-3">
			<select class="icon_class" id="icon_class" name="icon_class">
				<optgroup label="Theme icons">
				{foreach from=$vecicons key=key item=value}
				<option value="{$key}" {if $menu->icon_class == $key}selected{/if}>{$value}</option>
				{/foreach}
				</optgroup>
				<optgroup label="Awesome icons">
				{foreach from=$awesomeicons key=key item=value}
				<option value="{$key}" {if $menu->icon_class == $key}selected{/if}>{$value}</option>
				{/foreach}
				</optgroup>
			</select>
		</div>
		<p class="help-block col-lg-9 col-lg-offset-3">{l s='Add link to page view icons' mod='vecmegamenu'}</p>
	</div>
	<div class="form-group lab-img-icon">
		<label class="control-label col-lg-3">{l s='Image Icon' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			<div class="row">
				<div class="col-lg-6">
					<input type="text" id="icon_img" name="icon_img" value="{if $menu->icon}{$menu->icon}{/if}"/>
					<a href="filemanager/dialog.php?type=1&field_id=icon_img" class="btn btn-default iframe-column-upload"  data-input-name="icon_img" type="button">{l s='Select image' mod='vecvegamenu'} <i class="icon-angle-right"></i></a>
					{if $menu->icon}<img src="{$menu->icon}" style="display: block; max-width: 200px;"/>{/if}
				</div>
			</div>
        </div>
	</div>
	
	<div style="clear:both;"></div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Item label' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			{foreach from=$languages item=language}
				{if $languages|count > 1}
					<div class="row translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $id_language}style="display:none"{/if}>
				{/if}
				<div class="col-lg-6">
				<input type="text" class="subtitle" id="subtitle_{$language.id_lang|intval}" name="subtitle_{$language.id_lang|intval}" value="{if isset($menu->subtitle[$language.id_lang]) && $menu->subtitle[$language.id_lang]}{$menu->subtitle[$language.id_lang|intval]}{/if}"/>
				</div>
				{if $languages|count > 1}
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$language.iso_code|escape:'html':'UTF-8'}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});javascript:changeLangInfor({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if $languages|count > 1}
					</div>
				{/if}
			{/foreach}
		</div>
		<p class="help-block col-lg-9 col-lg-offset-3">{l s='Add highlight to menu item.' mod='vecvegamenu'}</p>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3" for="">{l s='Item label style' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			<div class="row">
				
				<div class="form-group col-lg-3" style="clear: both;">
	                <label class="control-label col-lg-6" for="subtitle_color">{l s='Color' mod='vecvegamenu'}</label>
	                <div class="col-lg-6">
	                    <div class="input-group">
	                        <input data-hex="true" class="color mColorPickerInput mColorPicker" data-default="" data-serializable="true" name="subtitle_color" value="{if $menu->subtitle_color}{$menu->subtitle_color}{/if}" id="subtitle_color" type="color">
	                    </div>
	                </div>
	            </div>
	            <div class="form-group col-lg-3" style="clear: both;">
	                <label class="control-label col-lg-6" for="subtitle_bgcolor">{l s='Background color' mod='vecvegamenu'}</label>
	                <div class="col-lg-6">
	                    <div class="input-group">
	                        <input data-hex="true" class="color mColorPickerInput mColorPicker" data-default="" data-serializable="true" name="subtitle_bgcolor" value="{if $menu->subtitle_bg_color}{$menu->subtitle_bg_color}{/if}" id="subtitle_bgcolor" type="color">
	                    </div>
	                </div>
	            </div>
		    </div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Add specific class' mod='vecvegamenu'}</label>
		<div class="col-lg-5">
			<input type="text" class="item_class" id="item_class" name="item_class" value="{if $menu->item_class}{$menu->item_class|escape:'html':'UTF-8'}{/if}"/>
		</div>
		<p class="help-block col-lg-9 col-lg-offset-3">{l s='Add a specific class to make different style for this item.' mod='vecmegamenu'}</p>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='New window' mod='vecvegamenu'}</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input type="radio" name="new_window" id="new_window_on" value="1" {if (isset($menu->new_window) &&  $menu->new_window != 0) }checked="checked"{/if}>
				<label for="new_window_on">Yes</label>
				<input type="radio" name="new_window" id="new_window_off" value="0" {if (isset($menu->new_window) && $menu->new_window == 0)|| !$menu->new_window}checked="checked"{/if}>
				<label for="new_window_off">No</label>
				<a class="slide-button btn"></a>
			</span>	
		</div>
	</div>
	<hr></hr>
	<h4 class="col-lg-offset-3">Submenu</h4>
	<div class="form-group" id="item_type_form">
        <label class="control-label col-lg-3" for="submenu_type">{l s='Submenu type' mod='vecvegamenu'}</label>
        <div class="col-lg-9">
			<span class="vec-switch vec-switch-3 col-lg-9">
				<input type="radio" name="submenu_type" id="submenu_type_0" value="0" {if !isset($menu->submenu_type ) || $menu->submenu_type == 0} checked="checked"{/if}/>
				<label for="submenu_type_0">
					{l s='Mega' mod='vecvegamenu'}
				</label>
				<input type="radio" name="submenu_type" id="submenu_type_1" value="1" {if $menu->submenu_type == 1} checked="checked"{/if}/>
				<label for="submenu_type_1">
					{l s='Flyout' mod='vecvegamenu'}
				</label>
				<input type="radio" name="submenu_type" id="submenu_type_2" value="2" {if $menu->submenu_type == 2} checked="checked"{/if}/>
				<label for="submenu_type_2">
					{l s='None' mod='vecvegamenu'}
				</label>	
				<a class="slide-button btn"></a>
			</span>
        </div>
    </div>
	
	<div class="panel-footer">
		<input type="hidden" name="id_vecvegamenu_item" id="id_vecvegamenu_item" value="{if isset($menu->id)}{$menu->id|intval}{/if}"/>
		<button type="submit" value="1" id="module_form_submit_btn" name="submitMenuItem" class="btn btn-default pull-right">
			<i class="process-icon-save"></i> Save
		</button>
		<a href="index.php?controller=AdminModules&amp;configure=vecvegamenu&amp;token={$token|escape:'html':'UTF-8'}" class="btn btn-default">
		<i class="process-icon-back"></i> Back to list</a>
	</div>
	
</div>
</form>
<script type="text/javascript">
	$(document).ready(function(){
        $('#type_icon').change(function(){
            ItemChangeTypeIcon();
        });
        $('#type_link').change(function(){
            ItemChangeTypeLink();
        });
        ItemChangeTypeIcon();
        ItemChangeTypeLink();
        $('.iframe-column-upload').fancybox({  
            'width'     : 900,
            'height'    : 600,
            'type'      : 'iframe',
            'autoScale' : false,
            'autoDimensions': false,
             'fitToView' : false,
             'autoSize' : false,
             onUpdate : function(){ 
                 $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
                 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
                },
             afterShow: function(){
                 $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
                 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
            }
          });
    });

    function ItemChangeTypeIcon(){
        var val = $('#type_icon').val();
        switch(val){
            case "0": // none
                $('.lab-fw-icon,.lab-img-icon').addClass('hidden');
                break;

            case "1": // integration
                $('.lab-img-icon').addClass('hidden');
                $('.lab-fw-icon').removeClass('hidden');
                break;

            case "2": // js
                $('.lab-fw-icon').addClass('hidden');
                $('.lab-img-icon').removeClass('hidden');
                break;
        }
    }
    function ItemChangeTypeLink(){
        var val = $('#type_link').val();
        switch(val){
            case "0": // none
                $('.custom_link').addClass('hidden');
                $('.ps_link').removeClass('hidden');
                break;

            case "1": // integration
                $('.ps_link').addClass('hidden');
                $('.custom_link').removeClass('hidden');
                break;

            case "2": // js
                $('.ps_link, .custom_link').addClass('hidden');
                break;
        }
    }
</script>
<style>
	#menuContent > div.form-group {
		margin-bottom: 20px;
	} 
</style>