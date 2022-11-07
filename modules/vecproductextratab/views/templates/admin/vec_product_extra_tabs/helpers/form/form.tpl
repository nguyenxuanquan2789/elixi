{extends file="helpers/form/form.tpl"}
{block name="label"}
    {if $input.type == 'vecpetab_content_type'}
        <input type="hidden" value="{$input.specific_prd_values}" name="specific_product" id="specific_product_id_text">  
    {elseif $input.type == 'ajaxproducts'}  
        {assign var=accessories value=$input.saved}
        <label class="control-label col-lg-3 " for="product_page"> {$input.label} </label>
        <div class="col-lg-5">
            <input type="hidden" name="inputAccessories" id="inputAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.id_product}-{/foreach}{/if}" />
            <input type="hidden" name="nameAccessories" id="nameAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.name|escape:'html':'UTF-8'}¤{/foreach}{/if}" />
            <div id="ajax_choose_product">
                <div class="input-group">
                    <input type="text" id="product_autocomplete_input" name="product_autocomplete_input"/>
                    <span class="input-group-addon"><i class="icon-search"></i></span>
                </div>
            </div>
            <div id="divAccessories">
                {if !empty($accessories)}
                {foreach from=$accessories item=accessory} 
                    <div class="form-control-static">
                            <button type="button" class="btn btn-default delAccessory" name="{$accessory.id_product}">
                                <i class="icon-remove text-danger"></i>
                            </button>
                        {$accessory.name|escape:'html':'UTF-8'}{if isset($accessory.reference)}&nbsp;{l s='(ref: %s)' sprintf=$accessory.reference}{/if}
                    </div>
                {/foreach}
                {/if}
            </div>
        </div>
    {elseif $input.type == 'ajaxproductcats'}  
        {assign var=accessories value=$input.saved}
        <label class="control-label col-lg-3 " for="product_page"> {$input.label} </label>
        <div class="col-lg-5">
            <input type="hidden" name="inputCatAccessories" id="inputCatAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.id_category}-{/foreach}{/if}" />
            <input type="hidden" name="nameCatAccessories" id="nameCatAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.name|escape:'html':'UTF-8'}¤{/foreach}{/if}" />
            <div id="ajax_choose_cat">
                <div class="input-group">
                    <input type="text" id="cat_autocomplete_input" name="cat_autocomplete_input"/>
                    <span class="input-group-addon"><i class="icon-search"></i></span>
                </div>
            </div>
            <div id="divCatAccessories">
                {if !empty($accessories)}
                {foreach from=$accessories item=accessory} 
                    <div class="form-control-static">
                            <button type="button" class="btn btn-default delCatAccessory" name="{$accessory.id_category}">
                                <i class="icon-remove text-danger"></i>
                            </button>
                        {$accessory.name|escape:'html':'UTF-8'}{if isset($accessory.reference)}&nbsp;{l s='(ref: %s)' sprintf=$accessory.reference}{/if}
                    </div>
                {/foreach}
                {/if}
            </div>
        </div>
    {elseif $input.type == 'ajaxproductmanus'}  
        {assign var=accessories value=$input.saved}
        <label class="control-label col-lg-3 " for="product_page"> {$input.label} </label>
        <div class="col-lg-5">
            <input type="hidden" name="inputManuAccessories" id="inputManuAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.id_manufacturer}-{/foreach}{/if}" />
            <input type="hidden" name="nameManuAccessories" id="nameManuAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.name|escape:'html':'UTF-8'}¤{/foreach}{/if}" />
            <div id="ajax_choose_manu">
                <div class="input-group">
                    <input type="text" id="manu_autocomplete_input" name="manu_autocomplete_input"/>
                    <span class="input-group-addon"><i class="icon-search"></i></span>
                </div>
            </div>
            <div id="divManuAccessories">
                {if !empty($accessories)}
                {foreach from=$accessories item=accessory} 
                    <div class="form-control-static">
                            <button type="button" class="btn btn-default delManuAccessory" name="{$accessory.id_manufacturer}">
                                <i class="icon-remove text-danger"></i>
                            </button>
                        {$accessory.name|escape:'html':'UTF-8'}{if isset($accessory.reference)}&nbsp;{l s='(ref: %s)' sprintf=$accessory.reference}{/if}
                    </div>
                {/foreach}
                {/if}
            </div>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
    <style>
        #vecproductextratabs_form .control-label.col-lg-4{
            width: 25%;
        }
    </style>
{/block}

