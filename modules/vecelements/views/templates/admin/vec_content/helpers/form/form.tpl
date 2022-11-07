{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}

{extends file="helpers/form/form.tpl"}

{block name="input_row"}
	{$smarty.block.parent}
	{if 'hook' == $input.name}
		<script>
		var $hook = $('#hook').attr('type', 'hidden');
		var $select = $('<select name="hook">').html(
			'<option value="displayHome">displayHome</option>' +
			'<option value="displayTop">displayTop</option>' +
			'<option value="displayBanner">displayBanner</option>' +
			'<option value="displayNav1">displayNav1</option>' +
			'<option value="displayNav2">displayNav2</option>' +
			'<option value="displayNavFullWidth">displayNavFullWidth</option>' +
			'<option value="displayTopColumn">displayTopColumn</option>' +
			'<option value="displayLeftColumn">displayLeftColumn</option>' +
			'<option value="displayRightColumn">displayRightColumn</option>' +
			'<option value="displayFooterBefore">displayFooterBefore</option>' +
			'<option value="displayFooter">displayFooter</option>' +
			'<option value="displayFooterAfter">displayFooterAfter</option>' +
			'<option value="displayAfterBodyOpeningTag">displayAfterBodyOpeningTag</option>' +
			'<option value="displayShoppingCart">displayShoppingCart</option>' +
			'<option value="displayShoppingCartFooter">displayShoppingCartFooter</option>' +
			'<option value="displayFooterProduct">displayFooterProduct</option>' +
			'<option value="displayNotFound">displayNotFound</option>' +
			'<option value="display404PageBuilder">display404PageBuilder</option>'
		).insertAfter($hook);

		if (!$select.find('[value="'+$hook.val()+'"]').length) {
			$('<option>', {
				value: $hook.val(),
				html: $hook.val()
			}).appendTo($select);
		}

		$select.select2({
			tags: true,
			createTag: function(params) {
				return {
					id: params.term,
					text: params.term,
					newOption: true
				};
			},
			templateResult: function(data) {
				var $result = $('<span>').text(data.text);

				if (data.newOption) {
					$result.append(" <i>(custom)</i>");
				}
				return $result;
			}
		}).val($hook.val())
			.trigger('change.select2')
		;
		</script>
	{/if}
{/block}
