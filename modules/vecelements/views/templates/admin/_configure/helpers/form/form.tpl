{**
 * AxonCreator - Website Builder
 *
 * NOTICE OF LICENSE
 *
 * @author    axonvip.com <support@axonvip.com>
 * @copyright 2021 axonvip.com
 * @license   You can not resell or redistribute this software.
 *
 * https://www.gnu.org/licenses/gpl-3.0.html
 **}

{extends file="helpers/form/form.tpl"}

{block name="input_row"}
    {if $input.type == 'page_trigger'}
        <div class="form-group">
            <label class="control-label col-lg-3"></label>
            <div class="col-lg-9">
                {if $input.url}
                    <a href="{$input.url|escape:'html':'UTF-8'}" class="btn btn-info axps-btn-edit"><i class="icon-external-link"></i> {l s='Edit with - AxonCreator' mod='axoncreator'}</a>
                {else}
                    <div class="alert alert-info">&nbsp;{l s='Save page first to enable AxonCreator' mod='axoncreator'}</div>
                {/if}
            </div>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
