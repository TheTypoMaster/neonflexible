<div class="container hidden-xs hidden-sm hidden-md">

	<div id="accroche" itemscope itemtype="http://schema.org/ContactPoint">
		<span class="accroche-texte">{l s='Un stock et un SAV garantis 100% français pour plus de réactivité' mod='now_block_accroche_header'}</span>
		<span itemprop="contactType" class="hidden">Service client</span>
		<span itemprop="availableLanguage" class="hidden">FR, EN</span>
		<a href="tel:{Configuration::get('PS_SHOP_PHONE')|replace:' ':''}" class="accroche-telephone">
			<span class="telephone">{Configuration::get('PS_SHOP_PHONE')}</span> {l s='/ International' mod='now_block_accroche_header'} <span itemprop="telephone">{Configuration::get('NOW_PHONE_INTERNATIONAL')}</span>
		</a>
	</div>
	<div class="clearfix"></div>

</div>