/*!
 * V-Elements - Live page builder
 * Copyright 2020-2022 themevec.com
 */

// fix for multiple select val(): when no options are selected, return [] instead of null
$.fn.val = (function(parent) {
	return function val(value) {
		return void 0 === value && this[0] && this[0].multiple && parent.call(this) === null ? [] : parent.apply(this, arguments);
	}
})($.fn.val);

// wrapfix jQuery plugin for product miniatures
$.fn.wrapfix = function() {
	return this.each(function(i, el) {
		if (!$(el).find('.elementor-product-miniature:first').length) {
			if (ce.wrapfix) {
				$(el).addClass(ce.wrapfix);
			} 
		}
	});
};

$(window).on('elementor:init', function onElementorInit() {
	elementor.channels.editor.on('section:activated', function onSectionActivated(sectionName, editor) {
		var editedElement = editor.getOption('editedElementView'),
			widgetType = editedElement.model.get('widgetType');

		if ('flip-box' === widgetType) {
			// init flip box back
			var isSideB = ['section_b', 'section_style_b', 'section_style_button'].indexOf(sectionName) > -1,
				$backLayer = editedElement.$el.find('.elementor-flip-box-back');

			editedElement.$el.toggleClass('elementor-flip-box--flipped', isSideB);

			if (isSideB) {
				$backLayer.css('transition', 'none');
			} else {
				setTimeout(function () {
					$backLayer.css('transition', '');
				}, 10);
			}
		} else if ('ajax-search' === widgetType) {
			// init search results
			editedElement.$el.find('.elementor-search__products').css({
				display: ['section_results_style', 'section_products_style'].indexOf(sectionName) < 0 ? 'none' : ''
			});
		}
	});
});

$(function() {elementor.on('preview:loaded', function() {
	// init widgets
	ceFrontend.hooks.addAction('frontend/element_ready/widget', function($widget, $) {
		// product miniature wrapfix
		if (0 === $widget.data('element_type').indexOf('product')) {
			$widget.removeClass('wrapfix').wrapfix();
		}

		// remote render fix
		if ($widget.find('.ce-remote-render').length) {
			elementor.helpers.getModelById('' + $widget.data('id')).renderRemoteServer();
		}
	});
	// Auto open Library for Theme Builder
	if (elementor.config.post_id.substr(-6, 2) == 17 && !elementor.config.data.length) {
		elementor.templates.startModal()
	}
})});

$(function onReady() {
	// init page custom CSS
	var addPageCustomCss = function() {
		var customCSS = elementor.settings.page.model.get('custom_css');

		if (customCSS) {
			customCSS = customCSS.replace(/selector/g, elementor.config.settings.page.cssWrapperSelector);

			elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
		}
	};
	elementor.on('preview:loaded', addPageCustomCss);
	elementor.settings.page.model.on('change', addPageCustomCss);

	// init element custom CSS
	elementor.hooks.addFilter('editor/style/styleText', function addCustomCss(css, view) {
		var model = view.getEditModel(),
			customCSS = model.get('settings').get('custom_css');

		if (customCSS) {
			css += customCSS.replace(/selector/g, '.elementor-element.elementor-element-' + view.model.id);
		}

		return css;
	});

	// init Products Cache
	elementor.productsCache = {};
	elementor.getProductName = function (id) {
		return this.productsCache[id] ? this.productsCache[id].name : '';
	};
	elementor.getProductImage = function (id) {
		return this.productsCache[id] ? this.productsCache[id].image : '';
	};

	// init File Manager
	elementor.fileManager = elementor.dialogsManager.createWidget('lightbox', {
		id: 'ce-file-manager-modal',
		closeButton: true,
		headerMessage: window.tinyMCE ? tinyMCE.i18n.translate('File manager') : 'File manager',

		onReady: function() {
			var $message = this.getElements('message').html(
				'<iframe id="ce-file-manager" width="100%" height="750"></iframe>'
			);
			this.iframe = $message.children().get(0);
			this.url = baseAdminDir + 'filemanager/dialog.php?type=1';

			this.open = function(fieldId) {
				this.fieldId = fieldId;

				if (this.iframe.contentWindow) {
					this.initFrame();
					this.getElements('widget').appendTo = function() {
						return this;
					};
					this.show();
				} else {
					$message.prepend(
						$('#tmpl-elementor-template-library-loading').html()
					);
					this.iframe.src = this.url + '&fldr=' + (localStorage.ceImgFldr || '');
					this.show(0);
				}
			};
			this.initFrame = function() {
				var $doc = $(this.iframe).contents();

				localStorage.ceImgFldr = $doc.find('#fldr_value').val();

				$doc.find('a.link').attr('data-field_id', this.fieldId);

				this.iframe.contentWindow.close_window = this.hide.bind(this);
			};
			this.iframe.onload = this.initFrame.bind(this);
		},

		onHide: function() {
			var $input = $('#' + this.fieldId);

			$input.val(
				$input.val().replace(location.origin, '')
			).trigger('input');
		},
	});

	// helper for get model by id
	elementor.helpers.getModelById = function(id, models) {
		models = models || elementor.elements.models;

		for (var i = models.length; i--;) {
			if (models[i].id === id) {
				return models[i];
			}
			if (models[i].attributes.elements.models.length) {
				var model = elementor.helpers.getModelById(id, models[i].attributes.elements.models);

				if (model) {
					return model;
				}
			}
		}
	};

	// fix: add home_url to relative image path
	elementor.imagesManager.getImageUrl = function(image) {
		var url = image.url;

		if (url && !/^(https?:)?\/\//i.test(url)) {
			url = elementor.config.home_url + url;
		}
		return url;
	};

	elementor.on('preview:loaded', function onPreviewLoaded() {
		// fix for View Page in force edit mode
		var href = elementor.$preview[0].contentWindow.location.href;

		if (~href.indexOf('&force=1&')) {
			elementor.config.post_permalink = href.replace(/&force=1&.*/, '');
		}

		// Add multistore warning
		elementor.$previewContents.find('#ce-warning-multistore').html(elementor.config.i18n.multistore);

		// scroll to content area
		var contentTop = elementor.$previewContents.find('#elementor .elementor-section-wrap').offset().top;
		if (contentTop > $(window).height() * 0.66) {
			elementor.$previewContents.find('html, body').animate({
				scrollTop: contentTop - 30
			}, 400);
		}

		// fix for multiple Global colors / fonts
		elementor.$previewContents.find('#elementor-global-css, link[href*="css/ce/global-"]').remove();

		// init Edit with CE buttons
		elementor.$previewContents.find('.ce-edit-btn').on('click.ce', function() {
			location.href = this.href;
		});

		// init Read More link
		elementor.$previewContents.find('.ce-read-more').on('click.ce', function() {
			window.open(this.href);
		});

		// fix for redirecting preview
		elementor.$previewContents.find('a[href]').on('click.ce', function(e) {
			e.preventDefault();
		});
	});
});

$(window).on('load.ce', function onLoadWindow() {
	// init language switcher
	var $context = $('#ce-context'),
		$langs = $('#ce-langs'),
		$languages = $langs.children().remove(),
		built = $langs.data('built'),
		lang = $langs.data('lang');

	elementor.shopContext = $context.length
		? $context.val()
		: 's-' + parseInt(elementor.config.post_id.slice(-2))
	;
	elementor.helpers.filterLangs = function() {
		var ctx = $context.length ? $context.val() : elementor.shopContext,
			id_group = 'g' === ctx[0] ? parseInt(ctx.substr(2)) : 0,
			id_shop = 's' === ctx[0] ? parseInt(ctx.substr(2)) : 0,
			dirty = elementor.shopContext != ctx;

		$langs.empty();

		var id_shops = id_group ? $context.find(':selected').nextUntil('[value^=g]').map(function() {
			return parseInt(this.value.substr(2));
		}).get() : [id_shop];

		$languages.each(function() {
			if (!ctx || $(this).data('shops').filter(function(id) { return ~id_shops.indexOf(id) }).length) {
				var $lang = $(this).clone().appendTo($langs),
					id_lang = $lang.data('lang'),
					active = !dirty && lang == id_lang;

				var uid = elementor.config.post_id.replace(/\d\d(\d\d)$/, function(m, shop) {
					return ('0' + id_lang).slice(-2) + ('0' + id_shop).slice(-2);
				});
				$lang.attr('data-uid', uid).data('uid', uid);

				active && $lang.addClass('active');

				if (active || !id_shop || !built[id_shop] || !built[id_shop][id_lang]) {
					$lang.find('.elementor-button').remove();
				}
			}
		});
	};
	elementor.helpers.filterLangs();
	$context.on('click.ce-ctx', function onClickContext(e) {
		// prevent closing languages
		e.stopPropagation();
	}).on('change.ce-ctx', elementor.helpers.filterLangs);

	$langs.on('click.ce-lang', '.ce-lang', function onChangeLanguage() {
		var uid = $(this).data('uid'),
			href = location.href.replace(/uid=\d+/, 'uid=' + uid);

		if ($context.length && $context.val() != elementor.shopContext) {
			document.context.action = href;
			document.context.submit();
		} else if (uid != elementor.config.post_id) {
			location = href;
		}
	}).on('click.ce-lang-get', '.elementor-button', function onGetLanguageContent(e) {
		e.stopImmediatePropagation();
		var $icon = $('i', this);

		if ($icon.hasClass('fa-spin')) {
			return;
		}
		$icon.attr('class', 'fa fa-spin fa-circle-o-notch');

		elementorCommon.ajax.addRequest('get_language_content', {
			data: {
				uid: $(this).closest('[data-uid]').data('uid')
			},
			success: function(data) {
				$icon.attr('class', 'eicon-file-download');

				elementor.getRegion('sections').currentView.addChildModel(data);
			},
			error: function(data) {
				elementor.templates.showErrorDialog(data);
			}
		});
	});

	// handle permission errors for AJAX requests
	$(document).ajaxSuccess(function onAjaxSuccess(e, xhr, conf, res) {
		if (false === res.success && res.data && res.data.permission) {
			NProgress.done();
			$('.elementor-button-state').removeClass('elementor-button-state');

			try {
				elementor.templates.showTemplates();
			} catch (ex) {}

			elementor.templates.getErrorDialog()
				.setMessage('<center>' + res.data.permission + '</center>')
				.show()
			;
		}
	});
});
