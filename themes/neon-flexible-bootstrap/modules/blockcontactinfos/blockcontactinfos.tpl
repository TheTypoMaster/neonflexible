<span class="barre hidden-xs hidden-sm hidden-md"></span>

<div id="contact">

	<div itemscope itemtype="http://schema.org/LocalBusiness">
		<span itemprop="name" class="hidden">{$shop_name|escape:'htmlall':'UTF-8'}</span>
		<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="url">
			<img class="logo" src="{$img_dir}theme/logo-footer.png" alt="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="image" />
		</a>

		<div class="adresse"itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<span itemprop="streetAddress">95 avenue Denis Papin</span><br>
			<span itemprop="postalCode">45800</span> -
			<span itemprop="addressLocality">Saint-Jean-de-Braye</span>,
			<span itemprop="addressRegion" class="hidden">Centre</span>
			<span itemprop="addressCountry">France</span>
		</div>

		<a href="tel:{Configuration::get('PS_SHOP_PHONE')|replace:' ':''}" class="telephone">
			<span class="tel-fr">{Configuration::get('PS_SHOP_PHONE')}</span>
			{l s='/ Int.' mod='blockcontactinfos'} <span itemprop="telephone">{Configuration::get('NOW_PHONE_INTERNATIONAL')}</span>
		</a>
	</div>
</div>
