/*!
 * V-Elements - Live page builder
 * Copyright 2020-2022 themevec.com
 */

jQuery(function ($) {
	if ('#license' === location.hash) {
		history.pushState('', document.title, location.pathname + location.search);

		$('#modal_license').modal();
	}

	var $regenerate = $('#page-header-desc-configuration-regenerate-css'),
		$replace = $(document.replace_url);

	$regenerate
		.attr({
			title: '<p style="margin:0 -14px; width:190px;">' + $regenerate.attr('onclick').substr(2) + '</p>',
		})
		.tooltip({
			html: true,
			placement: 'bottom',
		})
		.on('click.ce', function onClickRegenerateCss() {
			if ($regenerate.find('.icon-spin').length) {
				return;
			}
			$regenerate.find('i').attr('class', 'process-icon-reload icon-rotate-right icon-spin');

			$.post(
				location.href,
				{
					ajax: true,
					action: 'regenerate_css',
				},
				function onSuccessRegenerateCss(resp) {
					$regenerate.find('i').attr('class', 'process-icon-ok');
				},
				'json'
			);
		})
		.removeAttr('onclick')
	;
    $replace.on('submit.ce', function onSubmitReplaceUrl(event) {
        event.preventDefault();

        if ($replace.find('.icon-spin').length) {
            return;
        }
        $replace.find('i').attr('class', 'icon-circle-o-notch icon-spin');

        $.post(
            location.href,
            $(this).serialize(),
            function onSuccessReplaceUrl(resp) {
            	if (resp.success) {
            		document.replace_url.reset();
            	}
                $replace.find('i').attr('class', 'icon-refresh');

                $replace.find('.alert').attr({
                    'class': 'alert alert-' + (resp.success ? 'success' : 'danger')
                }).html(resp.data);
            },
            'json'
        );
    });

	$('input[name=elementor_container_width]').attr({
		type: 'number',
		min: 300,
	});
	$('input[name=elementor_space_between_widgets]').attr({
		type: 'number',
		min: 0,
	});
	$('input[name=elementor_stretched_section_container]').attr({
		placeholder: 'body',
	});
	$('input[name=elementor_viewport_lg]').attr({
		type: 'number',
		min: 769,
		max: 1439,
	});
	$('input[name=elementor_viewport_md]').attr({
		type: 'number',
		min: 481,
		max: 1024,
	});
});
