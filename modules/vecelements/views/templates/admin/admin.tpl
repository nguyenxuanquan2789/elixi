{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}

{capture ce_warning_multistore}<span id="ce-warning-multistore"></span>{/capture}

{capture ce_alert}<div class="alert alert-%s">%s</div>{/capture}

{capture ce_undefined_position}
	{ce__('Undefined Position!')}
{/capture}

{capture ce_action_link}<a href="%s" target="%s"><i class="icon-%s"></i> %s</a>{/capture}

{capture ce_modal_replace_url}
	<form name="replace_url" class="form-horizontal">
		<div class="modal-body">
			<input type="hidden" name="ajax" value="1">
			<input type="hidden" name="action" value="replace_url">
			<div class="alert alert-warning">%s</div>
			<div class="form-group">
				<div class="col-sm-6">
					<input type="url" placeholder="%s" class="form-control" name="from" required>
				</div>
				<div class="col-sm-6">
					<input type="url" placeholder="%s" class="form-control" name="to" required>
				</div>
			</div>
			<div class="help-block">%s</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary"><i class="icon-refresh"></i> %s</button>
		</div>
	</form>
{/capture}

{function ce_preview_breadcrumb links=[]}
	{$last = array_pop($links)}
	{foreach $links as $link}
		<a>{$link['title']|cleanHtml}</a><span class="navigation-pipe">&gt;</span>
	{/foreach}
	{$last['title']|cleanHtml}
{/function}

{capture ce_inline_script}
	<script data-cfasync="false">
	%s
	</script>
{/capture}

{function ce_add_custom_font}
	<div class="elementor-metabox-content">
		<div class="elementor-field font_face elementor-field-repeater">
			<script type="text/template" id="tmpl-elementor-add-font">
				<div class="repeater-block block-visible">
					<span class="repeater-title ce-hidden" data-default="{ce__('Settings')}" data-selector=".font_weight">{ce__('Settings')}</span>
					<span class="elementor-repeater-tool-btn close-repeater-row" title="{ce__('Close')}">
						<i aria-hidden="true" class="icon-times"></i> {ce__('Close')}
					</span>
					<span class="elementor-repeater-tool-btn toggle-repeater-row" title="{ce__('Edit')}">
						<i aria-hidden="true" class="icon-edit"></i> {ce__('Edit')}
					</span>
					<span class="elementor-repeater-tool-btn remove-repeater-row" data-confirm="{ce__('Are you sure?')}" title="{ce__('Delete')}">
						<i aria-hidden="true" class="icon-trash"></i> {ce__('Delete')}
					</span>
					<div class="repeater-content form-table">
						<div class="repeater-content-top">
							<div class="elementor-field font_face elementor-field-select">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][font_weight]">{ce__('Weight')}:</label>
								</p>
								<select class="font_weight" id="font_face[__counter__][font_weight]" name="font_face[__counter__][font_weight]">
									<option value="normal">{ce__('Normal')}</option>
									<option value="bold">{ce__('Bold')}</option>
									<option value="100">100</option>
									<option value="200">200</option>
									<option value="300">300</option>
									<option value="400">400</option>
									<option value="500">500</option>
									<option value="600">600</option>
									<option value="700">700</option>
									<option value="800">800</option>
									<option value="900">900</option>
								</select>
							</div>
							<div class="elementor-field font_face elementor-field-select">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][font_style]">{ce__('Style')}:</label>
								</p>
								<select class="font_style" id="font_face[__counter__][font_style]" name="font_face[__counter__][font_style]">
									<option value="normal">{ce__('Normal')}</option>
									<option value="italic">{ce__('Italic')}</option>
									<option value="oblique">{ce__('Oblique')}</option>
								</select>
							</div>
							<div class="inline-preview">V-Elements PageBuilder Is Making The Web Beautiful!</div>
							<div class="elementor-field font_face elementor-field-toolbar">
								<span class="elementor-repeater-tool-btn close-repeater-row" title="{ce__('Close')}">
									<i aria-hidden="true" class="icon-times"></i> {ce__('Close')}
								</span>
								<span class="elementor-repeater-tool-btn toggle-repeater-row" title="{ce__('Edit')}">
									<i aria-hidden="true" class="icon-edit"></i> {ce__('Edit')}
								</span>
								<span class="elementor-repeater-tool-btn remove-repeater-row" data-confirm="Are you sure?" title="{ce__('Delete')}">
									<i aria-hidden="true" class="icon-trash"></i> {ce__('Delete')}
								</span>
							</div>
						</div>
						<div class="repeater-content-bottom">
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][woff]file">{sprintf(ce__('%s File'), 'WOFF')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][woff][file]" type="file" accept=".woff,font/woff">
								<input class="elementor-field-input" name="font_face[__counter__][woff][url]" placeholder="{ce__('The Web Open Font Format, Used by Modern Browsers')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="woff" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][woff]" name="font_face[__counter__][woff]" type="button">
							</div>
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][woff2]file">{sprintf(ce__('%s File'), 'WOFF2')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][woff2][file]" type="file" accept=".woff2,font/woff2">
								<input class="elementor-field-input" name="font_face[__counter__][woff2][url]" placeholder="{ce__('The Web Open Font Format 2, Used by Super Modern Browsers')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="woff2" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][woff2]" name="font_face[__counter__][woff2]" type="button">
							</div>
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][ttf]file">{sprintf(ce__('%s File'), 'TTF')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][ttf][file]" type="file" accept=".ttf,font/ttf">
								<input class="elementor-field-input" name="font_face[__counter__][ttf][url]" placeholder="{ce__('TrueType Fonts, Used for better supporting Safari, Android, iOS')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="ttf" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][ttf]" name="font_face[__counter__][ttf]" type="button">
							</div>
							<div class="elementor-field font_face elementor-field-file">
								<p class="elementor-field-label">
									<label for="font_face[__counter__][otf]file">{sprintf(ce__('%s File'), 'OTF')}</label>
								</p>
								<input class="hidden" name="font_face[__counter__][otf][file]" type="file" accept=".otf,font/otf">
								<input class="elementor-field-input" name="font_face[__counter__][otf][url]" placeholder="{ce__('OpenType Fonts, Used for better supporting Safari, Android, iOS')}" type="text">
								<input class="elementor-button elementor-upload-btn" data-ext="otf" data-preview_anchor="none" data-remove_text="{ce__('Delete')}" data-upload_text="{ce__('Upload')}" id="font_face[__counter__][otf]" name="font_face[__counter__][otf]" type="button">
							</div>
						</div>
					</div>
				</div>
			</script>
			<input type="button" class="elementor-button add-repeater-row" value="{ce__('Add Font Variation')}" data-template-id="tmpl-elementor-add-font">
		</div>
	</div>
{/function}
