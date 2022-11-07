{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}
<style>
i.mi-ve {
	font-size: 14px !important;
}
i.icon-AdminParentVECContent, i.mi-ve {
	position: relative;
	height: 1em;
	width: 1.2857em;
}
/* i.icon-AdminParentVECContent:before, i.mi-ce:before,
i.icon-AdminParentVECContent:after, i.mi-ce:after {
	content: '';
	position: absolute;
	margin: 0;
	left: .2143em;
	top: 0;
	width: .9286em;
	height: .6428em;
	border-width: .2143em 0;
	border-style: solid;
	border-color: inherit;
	box-sizing: content-box;
}
i.icon-AdminParentVECContent:after, i.mi-ce:after {
	top: .4286em;
	width: .6428em;
	height: 0;
	border-width: .2143em 0 0;
} */
#maintab-AdminParentVecElements, #subtab-AdminParentVecElements {
	display: none;
}
</style>
{if !empty($edit_width_ce)}
<script type="text/html" id="tmpl-btn-back-to-ps">
    <a href="{$edit_width_ce|escape:'html':'UTF-8'}&amp;action=backToPsEditor" class="btn btn-default btn-back-to-ps"><i class="material-icons">navigate_before</i> {l s='Back to PrestaShop Editor' mod='vecelements'}</a>
</script>
<script type="text/html" id="tmpl-btn-edit-with-ce">
    <a href="{$edit_width_ce|escape:'html':'UTF-8'}" class="btn pointer btn-edit-with-ce"><i class="material-icons mi-ve"></i> {l s='Edit with Vec Elements' mod='vecelements'}</a>
</script>
{/if}