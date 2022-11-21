/*!
 * V-Elements - Live page builder
 * Copyright 2020-2022 themevec.com
 */

window.vecAdmin && document.addEventListener('DOMContentLoaded', function() {
	if (vecAdmin.ready) return;
	else vecAdmin.ready = true;

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
	vecAdmin.onClickImport = function() {
		$import.hasClass('visible')
			? $import.removeClass('visible').slideUp(300)
			: $import.addClass('visible').slideDown(300)
		;
	};
	vecAdmin.onClickBtnBack = function(e) {
		vecAdmin.checkChanges = true;
	};
	vecAdmin.onClickBtnWrapper = function(e) {
		this.children[0].click();
	};
	vecAdmin.onClickBtnEdit = function(e) {
		e.stopPropagation();
		vecAdmin.checkChanges = true;

		if (vecAdmin.i18n.error) {
			vecAdmin.checkChanges = e.preventDefault();
			return alert(vecAdmin.i18n.error);
		}
		if ('0' === vecAdmin.uid[0]) {
			if (document.body.classList.contains('adminmaintenance')) {
				return this.href += '&action=addMaintenance';
			}
			vecAdmin.checkChanges = e.preventDefault();
			return alert(vecAdmin.i18n.save);
		}
	};

	// Button templates
	var tmplBtnBack = $('#tmpl-btn-back-to-ps').html(),
		tmplBtnEdit = $('#tmpl-btn-edit-with-ce').html();

	if (vecAdmin.footerProduct) {
		var $tf = $('<div class="translationsFields tab-content">').wrap('<div class="translations tabbable">');
		$tf.parent()
			.insertAfter('#related-product')
			.before('<h2 class="ce-product-hook">displayFooterProduct</h2>')
		;

		$('textarea[id*=description_short_]').each(function(i, el) {
			var idLang = el.id.split('_').pop(),
				lang = el.parentNode.className.match(/translation-label-(\w+)/),
				$btn = $(tmplBtnEdit).click(vecAdmin.onClickBtnEdit);

			if ('0' === vecAdmin.footerProduct[0]) {
				$btn[0].href += '&action=addFooterProduct&uid=' + (1*vecAdmin.uid + 100*idLang);
			} else {
				$btn[0].href += '&uid=' + (1*vecAdmin.footerProduct + 100*idLang) + '&footerProduct=' + vecAdmin.uid.slice(0, -6);
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

	vecAdmin.getContents = function(selector) {
		return $(selector).each(function(i, el) {
			var idLang = parseInt(el[el.id ? 'id' : 'name'].split('_').pop()) || 0,
				$btn = $(tmplBtnEdit).insertBefore(el).click(vecAdmin.onClickBtnEdit);

			$btn[0].href += '&uid=' + (1*vecAdmin.uid + 100*idLang);

			if (~vecAdmin.hideEditor.indexOf(idLang)) {
				$(tmplBtnBack).insertBefore($btn).click(vecAdmin.onClickBtnBack)[0].href += '&uid=' + (1*vecAdmin.uid + 100*idLang);
				$btn.wrap('<div class="wrapper-edit-with-ve">').parent().click(vecAdmin.onClickBtnWrapper);
				$(el).hide().next('.maxLength').hide();
			} else {
				$btn.after('<br>');
			}
		});
	};

	vecAdmin.$contents = vecAdmin.getContents([
		'body:not(.adminproducts) textarea[name^=content_]:not([name*=short])',
		'body:not(.adminproducts) textarea[name*="[content]"]',
		'body:not(.adminpsblogblogs, .admincmscontent) textarea[name^=description_]:not([name*=short])',
		'textarea[name*="[description]"]',
		'textarea[name^=post_content_]',
		'textarea[name=content]',
		'.adminmaintenance textarea'
	].join());

	// Insert edit button to Maintenance on PS 1.6
	if (!vecAdmin.$contents.length && document.body.classList.contains('adminmaintenance')) {
		var $btn = $(tmplBtnEdit)
			.css('marginTop', 25)
			.insertAfter('input[name=PS_MAINTENANCE_IP]')
			.click(vecAdmin.onClickBtnEdit);

		$btn[0].href += '&uid=' + (1*vecAdmin.uid + 100*default_language);
	}

	vecAdmin.form = vecAdmin.$contents[0] && vecAdmin.$contents[0].form;
	vecAdmin.formChanged = false;

	$(function() {
		// run after jQuery's document ready
		$(vecAdmin.form).one('change', ':input', function() {
			vecAdmin.formChanged = true;
		});
	});
	$(window).on('beforeunload', function() {
		if (vecAdmin.checkChanges && vecAdmin.formChanged) {
			delete vecAdmin.checkChanges;
			return "Changes you made may not be saved!";
		}
	});
});
