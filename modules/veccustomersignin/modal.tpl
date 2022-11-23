<div class="modal vec-quicklogin-modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog animationShowPopup animated" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="login-title">
					{l s='Login to your account' mod='veccustomersignin'}
				</h3>
				<h3 class="reset-title" style="display: none;">
					{l s='Reset Password' mod='veccustomersignin'}
				</h3>
				<h3 class="wishlist-title" style="display: none;">
					{l s='You have to login to use Wishlist' mod='veccustomersignin'}
				</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i class="elicon-cross"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="vec-quicklogin-form">
					<!-- Login -->
					<div class="vec-login-form">
						<form class="login-form-content" action="#" method="post">
							<div class="vec-form-msg"></div>

							<div class="form-group">
								<label for="vec-pass-login">{l s='Email' mod='veccustomersignin'}</label>
								<input type="email" class="form-control vec-email-login" name="vec-email-login"
									required="" placeholder="{l s='Email Address' mod='veccustomersignin'}">
							</div>
							<div class="form-group">
								<label for="vec-pass-login">{l s='Password' mod='veccustomersignin'}</label>
								<input type="password" class="form-control vec-pass-login" name="vec-pass-login"
									required="" placeholder="{l s='Password' mod='veccustomersignin'}">
							</div>
							<div class="vec-forgot-password">
								<a role="button" href="#"
									class="vec-forgot-password">{l s='Forgot Password' mod='veccustomersignin'} ?</a>
							</div>
							<div class="form-group text-right">
								<button type="submit" class="vec-login-btn btn btn-primary">
									{l s='Login' mod='veccustomersignin'}
								</button>
							</div>
						</form>
						<div class="vec-register">
							<a role="button"
								href="{$urls.pages.authentication}?create_account=1">{l s='No account?' mod='veccustomersignin'}<span>{l s='Create one here' mod='veccustomersignin'}</span></a>
						</div>
					</div>
					<!-- Reset password -->
					<div class="vec-resetpass-form" style="display: none;">
						<form class="vcs-form-content vec-resetpass-form-content" action="#" method="post">
							<div class="vec-form-msg"></div>
							<p class="send-renew-password-link">
								{l s='Please enter the email address you used to register. You will receive a temporary link to reset your password.' d='Shop.Theme.Customeraccount'}
							</p>
							<div class="form-group">
								<label for="vec-pass-reset">{l s='Email' mod='veccustomersignin'}</label>
								<input type="email" class="form-control vec-email-reset" name="vec-email-reset"
									required="" placeholder="{l s='Email Address' mod='veccustomersignin'}">
							</div>
							<div class="form-group">
								<button type="submit" class="vec-reset-btn btn btn-primary">
									{l s='Reset Password' mod='veccustomersignin'}
								</button>
							</div>
							<button class="back-login-btn">{l s='Back to login' mod='veccustomersignin'}</button>
						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>

</div>