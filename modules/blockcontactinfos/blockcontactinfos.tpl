

<span class="barre hidden-xs hidden-sm hidden-md"></span>

<div id="contact">

	<div itemscope itemtype="http://schema.org/LocalBusiness">
		<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="url">
			<img class="logo" src="{$logo_url}" alt="{$shop_name|escape:'htmlall':'UTF-8'}" {if $logo_image_width}width="{$logo_image_width}"{/if} {if $logo_image_height}height="{$logo_image_height}" {/if}  itemprop="photo" />
		</a>

		<div class="adresse" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
			<span itemprop="street-address">95 avenue Denis Papin</span><br>
			<span itemprop="postal-code">45800</span> -
			<span itemprop="locality">Saint-Jean-de-Braye</span>,
			<span itemprop="country-name">France</span>
		</div>

		<a href="tel:{Configuration::get('PS_SHOP_PHONE')}" class="telephone" itemprop="telephone"><span>{Configuration::get('PS_SHOP_PHONE')}</span> {l s='/ Int. (+33) 234 321 179'}</a>
	</div>

</div>


{*
<!-- MODULE Block contact infos -->
<div id="block_contact_infos">
	<h4 class="title_block">{l s='Contact us' mod='blockcontactinfos'}</h4>
	<ul>
		{if $blockcontactinfos_company != ''}<li><strong>{$blockcontactinfos_company|escape:'html':'UTF-8'}</strong></li>{/if}
		{if $blockcontactinfos_address != ''}<li><pre>{$blockcontactinfos_address|escape:'html':'UTF-8'|nl2br}</pre></li>{/if}
		{if $blockcontactinfos_phone != ''}<li>{l s='Tel' mod='blockcontactinfos'} {$blockcontactinfos_phone|escape:'html':'UTF-8'}</li>{/if}
		{if $blockcontactinfos_email != ''}<li>{l s='Email:' mod='blockcontactinfos'} {mailto address=$blockcontactinfos_email|escape:'html':'UTF-8' encode="hex"}</li>{/if}
	</ul>
</div>
<!-- /MODULE Block contact infos -->
*}
