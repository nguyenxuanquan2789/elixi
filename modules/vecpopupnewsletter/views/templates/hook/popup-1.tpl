<div id="vecpopupnewsletter" class="modal popup-type-1 popup-text-color-{$vecpopup.VEC_NEWSLETTER_TEXT_COLOR} fade"
	tabindex="-1" role="dialog">
	<div class="modal-dialog animationShowPopup animated" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
				<i class="vecicon-cross"></i>
			</button>
			<div class="wrapper-popup"
				style="{if $vecpopup.VEC_NEWSLETTER_BG == 1 && !empty($vecpopup.VEC_NEWSLETTER_BG_IMAGE)}background-image: url({$vecpopup.VEC_NEWSLETTER_BG_IMAGE});{/if}">
				{if $vecpopup.VEC_NEWSLETTER_FORM == 1 && Module::isEnabled('ps_emailsubscription')}
					<div class="popup-content">
						{if $vecpopup.VEC_NEWSLETTER_TITLE}
							<div class="popup_title">
								<i class="popup_icon vecicon-mail_close"></i>
								{$vecpopup.VEC_NEWSLETTER_TITLE|stripslashes nofilter}
							</div>
						{/if}
						{if $vecpopup.VEC_NEWSLETTER_TEXT}
							<div class="popup_text">
								{$vecpopup.VEC_NEWSLETTER_TEXT|stripslashes nofilter}
							</div>
						{/if}
						<form class="ajax-newsletter block_newsletter" action="{$link->getPageLink('index')|escape:'html'}"
							method="post">
							<input class="hidden" type="radio" name="action" value="0" checked>
							<div class="form-content">
								<input class="inputNew form-control" type="email" name="email"
									placeholder="{l s='Enter Email Address Here'  mod='vecpopupnewsletter'}" required />
								<button class="btn btn-primary" name="submitNewsletter" type="submit">
									{l s='SUBSCRIBE' mod='vecpopupnewsletter'}
								</button>
							</div>
							<div class="send-response"></div>
							{if isset($id_module)}
								{hook h='displayGDPRConsent' id_module=$id_module}
							{/if}
						</form>
						<div class="newsletter_block_popup-bottom custom-checkbox">
							<label>
								<input id="newsletter_popup_dont_show_again" type="checkbox">
								<span><i class="vecicon-check rtl-no-flip checkbox-checked"></i></span>
								<span>{l s='Don\'t show this popup again' mod='vecpopupnewsletter'}</span>
							</label>
						</div>
					</div>
				{/if}
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->