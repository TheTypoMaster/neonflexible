{if isset($accessories) AND $accessories}
	<div class="product-tab">
		<h2 id="accessories">{l s='Produits complémentaires'}</h2>
		<ul class="product-accessories">

			{foreach from=$accessories item=accessory name=accessories_list key=k}
				{if $k < 3 && ($accessory.allow_oosp || $accessory.quantity_all_versions > 0 || $accessory.quantity > 0) AND $accessory.available_for_order AND !isset($restricted_country_mode)}
					{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
					<li class="ajax_block_product{if $smarty.foreach.accessories_list.first} first_item{elseif $smarty.foreach.accessories_list.last} last_item{else} item{/if} product_accessories_description">

						{* Image *}
						<div class="image-left">

							<img src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'accessories')|escape:'html'}" alt="{$accessory.legend|escape:'htmlall':'UTF-8'}" />

						</div>

						<div class="content-right">

							{* Nom du produit *}
							<p class="product-name">
								{$accessory.name|escape:'htmlall':'UTF-8'}
							</p>

							{* Description *}
							<div class="block_description">
								{$accessory.description_short|strip_tags|truncate:100:'...'}
							</div>

							{* Prix *}
							<p class="price">
								{if $accessory.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
									{if $priceDisplay != 1}
										{displayWtPrice p=$accessory.price}
									{else}
										{displayWtPrice p=$accessory.price_tax_exc}
									{/if}
								{/if}
							</p>

							{* Bouton ajouter au panier *}
							{if !$PS_CATALOG_MODE && ($accessory.allow_oosp || $accessory.quantity > 0)}
								<a class="button-add-to-cart ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html'}" rel="ajax_id_product_{$accessory.id_product|intval}" title="{l s='Add to cart'}">
									<span></span>
									{l s='Add to cart'}
								</a>
							{/if}

						</div>

						<span class="clearBoth"></span>

						<a class="link" href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View'}</a>

					</li>
				{/if}
			{/foreach}
		</ul>

		<span class="clearBoth"></span>

	</div>
{/if}