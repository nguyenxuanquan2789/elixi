<div id="menu-icon"><i class="vecicon-bars"></i></div>
<div class="menu-mobile-content" id="mobile_menu_wrapper">
	{if $vmenu}
		<ul class="nav nav-mobile-menu" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#tab-mobile-megamenu" role="tab"
					aria-controls="mobile-megamenu" aria-selected="true">{l s='Menu' mod='vecelements'}</a>

			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tab-mobile-vegamenu" role="tab" aria-controls="mobile-vegamenu"
					aria-selected="true">{l s='Categories' mod='vecelements'}</a>
			</li>
		</ul>
	{/if}
	{if $vmenu}
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab-mobile-megamenu" role="tabpanel" aria-labelledby="megamenu-tab">
			{/if}
			<div id="_mobile_megamenu"></div>
			{if $vmenu}
			</div>
			<div class="tab-pane fade" id="tab-mobile-vegamenu" role="tabpanel" aria-labelledby="vegamenu-tab">
				<div id="_mobile_vegamenu"></div>
			</div>
		</div>
	{/if}
	<div class="menu-mobile-bottom">
		{hook h='displayMegamenuMobileBottom'}
		<div class="menu-close">
			{l s='close' mod='vecelements'}
		</div>
	</div>
</div>