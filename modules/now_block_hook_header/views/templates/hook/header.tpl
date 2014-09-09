<div class="container hidden-xs hidden-sm hidden-md">

	<div id="accroche">
		<span class="accroche-texte">{l s='Un stock et un SAV garantis 100% français pour plus de réactivité' mod='now_block_accroche_header'}</span>
		<a href="tel:{Configuration::get('PS_SHOP_PHONE')|replace:' ':''}" class="accroche-telephone">
			<span>{Configuration::get('PS_SHOP_PHONE')}</span> {l s='/ International %s' sprintf=Configuration::get('NOW_PHONE_INTERNATIONAL') mod='now_block_accroche_header'}
		</a>
	</div>
	<div class="clearfix"></div>

</div>