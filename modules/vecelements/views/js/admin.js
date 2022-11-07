/*!
 * V-Elements - Live page builder
 * Copyright 2020-2022 themevec.com
 */

window.ceAdmin && document.addEventListener('DOMContentLoaded', function() {
	if (ceAdmin.ready) return;
	else ceAdmin.ready = true;

	// Cancel button fix
	$('.btn[id$=_form_cancel_btn]')
		.removeAttr('onclick')
		.attr('href', location.href.replace(/&id\w*=\d+|&(add|update)\w+(=[^&]*)?/g, ''))
	;

	// Fix for shortcode
	$('.ce-shortcode input').on('click.ce', function(e) {
		this.select();
	}).parent()
		.removeAttr('onclick')
		.removeClass('pointer')
	;

	// Minor CSS fix
	$('.btn-group-action a.product-edit.tooltip-link').addClass('dropdown-item');

	// Import Template
	var $import = $('.ce-import-panel').removeClass('hide')
		.parent().slideUp(0).insertBefore('#form-vec_template')
	;
	$('.ce-import-panel #file').attr({
		accept: '.json,.zip',
		required: true,
	});

	// Handler functions
	ceAdmin.onClickImport = function() {
		$import.hasClass('visible')
			? $import.removeClass('visible').slideUp(300)
			: $import.addClass('visible').slideDown(300)
		;
	};
	ceAdmin.onClickBtnBack = function(e) {
		ceAdmin.checkChanges = true;
	};
	ceAdmin.onClickBtnWrapper = function(e) {
		this.children[0].click();
	};
	ceAdmin.onClickBtnEdit = function(e) {
		e.stopPropagation();
		ceAdmin.checkChanges = true;

		if (ceAdmin.i18n.error) {
			ceAdmin.checkChanges = e.preventDefault();
			return alert(ceAdmin.i18n.error);
		}
		if ('0' === ceAdmin.uid[0]) {
			if (document.body.classList.contains('adminmaintenance')) {
				return this.href += '&action=addMaintenance';
			}
			ceAdmin.checkChanges = e.preventDefault();
			return alert(ceAdmin.i18n.save);
		}
	};

	// Button templates
	var tmplBtnBack = $('#tmpl-btn-back-to-ps').html(),
		tmplBtnEdit = $('#tmpl-btn-edit-with-ce').html();

	if (ceAdmin.footerProduct) {
		var $tf = $('<div class="translationsFields tab-content">').wrap('<div class="translations tabbable">');
		$tf.parent()
			.insertAfter('#related-product')
			.before('<h2 class="ce-product-hook">displayFooterProduct</h2>')
		;

		$('textarea[id*=description_short_]').each(function(i, el) {
			var idLang = el.id.split('_').pop(),
				lang = el.parentNode.className.match(/translation-label-(\w+)/),
				$btn = $(tmplBtnEdit).click(ceAdmin.onClickBtnEdit);

			if ('0' === ceAdmin.footerProduct[0]) {
				$btn[0].href += '&action=addFooterProduct&uid=' + (1*ceAdmin.uid + 100*idLang);
			} else {
				$btn[0].href += '&uid=' + (1*ceAdmin.footerProduct + 100*idLang) + '&footerProduct=' + ceAdmin.uid.slice(0, -6);
			}
			$('<div class="translation-field tab-pane">')
				.addClass(lang ? 'translation-label-'+lang[1] : '')
				.addClass(el.parentNode.classList.contains('active') ? 'active' : '')
				.addClass(el.parentNode.classList.contains('visible') ? 'visible' : '')
				.append($btn)
				.appendTo($tf)
			;
		});
	}

	ceAdmin.getContents = function(selector) {
		return $(selector).each(function(i, el) {
			var idLang = parseInt(el[el.id ? 'id' : 'name'].split('_').pop()) || 0,
				$btn = $(tmplBtnEdit).insertBefore(el).click(ceAdmin.onClickBtnEdit);

			$btn[0].href += '&uid=' + (1*ceAdmin.uid + 100*idLang);

			if (~ceAdmin.hideEditor.indexOf(idLang)) {
				$(tmplBtnBack).insertBefore($btn).click(ceAdmin.onClickBtnBack)[0].href += '&uid=' + (1*ceAdmin.uid + 100*idLang);
				$btn.wrap('<div class="wrapper-edit-with-ve">').parent().click(ceAdmin.onClickBtnWrapper);
				$(el).hide().next('.maxLength').hide();
			} else {
				$btn.after('<br>');
			}
		});
	};

	ceAdmin.$contents = ceAdmin.getContents([
		'body:not(.adminproducts) textarea[name^=content_]:not([name*=short])',
		'body:not(.adminproducts) textarea[name*="[content]"]',
		'body:not(.adminpsblogblogs) textarea[name^=description_]:not([name*=short])',
		'textarea[name*="[description]"]',
		'textarea[name^=post_content_]',
		'textarea[name=content]',
		'.adminmaintenance textarea'
	].join());

	// Insert edit button to Maintenance on PS 1.6
	if (!ceAdmin.$contents.length && document.body.classList.contains('adminmaintenance')) {
		var $btn = $(tmplBtnEdit)
			.css('marginTop', 25)
			.insertAfter('input[name=PS_MAINTENANCE_IP]')
			.click(ceAdmin.onClickBtnEdit);

		$btn[0].href += '&uid=' + (1*ceAdmin.uid + 100*default_language);
	}

	ceAdmin.form = ceAdmin.$contents[0] && ceAdmin.$contents[0].form;
	ceAdmin.formChanged = false;

	$(function() {
		// run after jQuery's document ready
		$(ceAdmin.form).one('change', ':input', function() {
			ceAdmin.formChanged = true;
		});
	});
	$(window).on('beforeunload', function() {
		if (ceAdmin.checkChanges && ceAdmin.formChanged) {
			delete ceAdmin.checkChanges;
			return "Changes you made may not be saved!";
		}
	});
});
