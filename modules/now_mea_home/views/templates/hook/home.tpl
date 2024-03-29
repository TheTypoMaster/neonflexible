{if Configuration::get('NOW_MEA_HOME_ENABLE') && count($aProducts) > 0}
	<div class="container">

		<div id="mea-home">
			<h2 class="titre-size-1">{l s='Les produits Néon Flexible à la UNE' mod='now_mea_home'}</h2>

			<ul>
				{foreach from=$aProducts item=oProduct name=mea}

					<!--{if $smarty.foreach.mea.first}
						{assign var=imageType value="home_medium"}
					{else}
						{assign var=imageType value="home_small"}
					{/if}-->
					{assign var=imageType value="home_medium"}
					<li {if $smarty.foreach.mea.first}class="first clearfix col-xs-12 col-md-4"{else}class="clearfix col-sm-6 col-md-4"{/if}>

						{* Images *}
						<span {if $smarty.foreach.mea.first}class="product_image col-sm-6 col-md-12"{else}class="product_image col-sm-6"{/if}>
							{assign var=image value=Product::getCover($oProduct->id)}
							{if isset($image.id_image)}
								{assign var=imageIds value=$oProduct->id|cat:'-'|cat:$image.id_image}
								<img class="img-responsive" src="{Context::getContext()->link->getImageLink($oProduct->link_rewrite, $imageIds, $imageType)|escape:'html':'UTF-8'}" alt="{$oProduct->name|escape:'html':'UTF-8'}" />
							{else}
								<img  class="img-responsive" src="{$img_prod_dir}{$lang_iso}-default-{$imageType}.jpg" alt="{$oProduct->name|escape:'html':'UTF-8'}" />
							{/if}
						</span>

						<div {if $smarty.foreach.mea.first}class="col-sm-6 col-md-12"{else}class="col-sm-6"{/if}>

							{* Nom du produit *}
							<h3 class="titre">{$oProduct->name|escape:'html':'UTF-8'}</h3>

							{* Courte description *}
							<p class="desc">{$oProduct->description_short|strip_tags:false|truncate:110:'...'}</p>

							{* Prix *}
							{if isset($oProduct->show_price) && $oProduct->show_price && !isset($restricted_country_mode)}
								<p class="price">						
									{if !$priceDisplay}
										{convertPrice price=$oProduct->price*1.2}
									{else}
										{convertPrice price=$oProduct->price}
									{/if}
								</p>
							{/if}

							{* Note *}

							{* Bouton ajouter au panier *}
							{if (isset($add_prod_display) && ($add_prod_display == 1)) && $oProduct->available_for_order && !isset($restricted_country_mode) && $oProduct->minimal_quantity <= 1 && $oProduct->customizable != 2 && (!($PS_CATALOG_MODE))}
								{if (Product::isAvailableWhenOutOfStock($oProduct->out_of_stock) || $oProduct->quantity > 0)}
									{if isset($static_token)}
										<a class="button-add-to-cart gradient ajax_add_to_cart_button" data-id-product="{$oProduct->id|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$oProduct->id|intval}&amp;token={$static_token}", false)|escape:'html'}" title="{l s='Add to cart' mod='now_mea_home'}"><span></span>{l s='Add to cart' mod='now_mea_home'}</a>
									{else}
										<a class="button-add-to-cart gradient ajax_add_to_cart_button" data-id-product="{$oProduct->id|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$oProduct->id|intval}", false)|escape:'html'}" title="{l s='Add to cart' mod='now_mea_home'}"><span></span>{l s='Add to cart' mod='now_mea_home'}</a>
									{/if}
								{else}
									<span class="button-add-to-cart gradient disabled"><span></span>{l s='Add to cart' mod='now_mea_home'}</span><br />
								{/if}
							{/if}
						</div>

						{* lien *}
						<a href="{Context::getContext()->link->getProductLink($oProduct)|escape:'html':'UTF-8'}" title="{$oProduct->name|escape:'html':'UTF-8'}" class="link"></a>

					</li>
				{/foreach}
			</ul>

		</div>

		<div class="clearfix"></div>

	</div>
{/if}