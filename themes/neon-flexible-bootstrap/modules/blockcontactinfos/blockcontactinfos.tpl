<span class="barre hidden-xs hidden-sm hidden-md"></span>

<div id="contact">

	<div itemscope itemtype="http://schema.org/LocalBusiness">
		<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="url">
			<img class="logo" src="{$img_dir}theme/logo-footer.png" alt="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="photo" />
		</a>

		<div class="adresse" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
			<span itemprop="street-address">95 avenue Denis Papin</span><br>
			<span itemprop="postal-code">45800</span> -
			<span itemprop="locality">Saint-Jean-de-Braye</span>,
			<span itemprop="country-name">France</span>
		</div>

		<a href="tel:{Configuration::get('PS_SHOP_PHONE')|replace:' ':''}" class="telephone" itemprop="telephone"><span>{Configuration::get('PS_SHOP_PHONE')}</span>  {l s='/ Int. %s' sprintf=Configuration::get('NOW_PHONE_INTERNATIONAL') mod='blockcontactinfos'}</a>
	</div>
</div>
