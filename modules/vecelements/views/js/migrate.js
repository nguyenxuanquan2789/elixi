/*!
 * V-Elements - Live page builder
 * Copyright 2020-2022 themevec.com
 */

ceMigrate.init = function() {
	this.moveCount = 6;
	this.ajaxDelay = 333;

	$('<link>', {
		href: this.baseDir + 'modules/vecelements/views/css/migrate.css',
		rel: 'stylesheet'
	}).appendTo('head');

	$('<link>', {
		href: this.baseDir + 'js/jquery/plugins/fancybox/jquery.fancybox.css',
		rel: 'stylesheet'
	}).one('load', function() {
		$.ajax({
			url: ceMigrate.baseDir + 'js/jquery/plugins/fancybox/jquery.fancybox.js',
			cache: true,
			dataType: 'script',
			success: $.proxy(ceMigrate, 'open')
		});
	}).appendTo('head');
};

ceMigrate.open = function() {
	$.fancybox({
		padding: 5,
		minWidth: 500,
		maxWidth: 500,
		minHeight: 16,
		maxHeight: 16,
		closeBtn: false,
		helpers: {
			overlay: {closeClick: false},
			title: true
		},
		title: 'V-Elements - Upgrade',
		content: '\
			<div id="ce-progress" class="progress">\
				<div class="progress-bar progress-bar-striped progress-bar-animated"></div>\
			</div>\
		',
		afterShow: function() {
			ceMigrate.updateProgress();
			ceMigrate.move();
		}
	});
};

ceMigrate.updateProgress = function() {
	var p = 100 - (this.ids.content.length + this.ids.template.length) / this.count * 100;

	$('#ce-progress .progress-bar').css('width', p.toFixed(2) + '%');
};

ceMigrate.move = function() {
	var type = this.ids.content.length ? 'content' : 'template';

	$.ajax(this.ajaxUrl[type], {
		type: 'post',
		dataType: 'json',
		data: {
			action: 'migrate',
			ids: this.ids[type].slice(0, this.moveCount),
		},
		success: $.proxy(this, 'onSuccess'),
		error: $.proxy(this, 'onError')
	});
};

ceMigrate.onSuccess = function(data, status, xhr) {
	if (data && data.done && data.done.length) {
		var diff = [];

		this.ids[data.type].forEach(function(id) {
			data.done.indexOf(id) < 0 && diff.push(id);
		});
		this.ids[data.type] = diff;
		this.updateProgress();

		if (this.ids.content.length + this.ids.template.length) {
			setTimeout($.proxy(this, 'move'), this.ajaxDelay);
		} else {
			setTimeout(function onFinish() {
				location.href = ceMigrate.ajaxUrl.content.replace('&ajax=1', '');
			}, 600);
		}
	} else {
		this.onError(xhr, 'unknown error');
	}
};

ceMigrate.onError = function(xhr, status) {
	alert(status + '\nPlease contact us on product support.');
	$.fancybox.close();
	$('<div>' + xhr.responseText + '</div>').prependTo('#content');
};

window.$
	? $(document).ready($.proxy(ceMigrate, 'init'))
	: document.addEventListener('DOMContentLoaded', ceMigrate.init.bind(ceMigrate))
;
