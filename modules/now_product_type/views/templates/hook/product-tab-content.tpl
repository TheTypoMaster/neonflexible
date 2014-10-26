
{if isset($aNowIdeasOrTips) && count($aNowIdeasOrTips) > 0 && $aNowIdeasOrTips}
	<div class="product-tab">
		<h2 id="ideasOrTips">{l s='Idées & conseils'}</h2>
		<ul class="product-accessories">

			{foreach from=$aNowIdeasOrTips item=aNowIdeaOrTip name=nowIdeasOrTips_list key=k}
				{if $k < 3 && ($aNowIdeaOrTip.allow_oosp || $aNowIdeaOrTip.quantity_all_versions > 0 || $aNowIdeaOrTip.quantity > 0) AND $aNowIdeaOrTip.available_for_order AND !isset($restricted_country_mode)}
					{assign var='aNowIdeaOrTipLink' value=$link->getProductLink($aNowIdeaOrTip.id_product, $aNowIdeaOrTip.link_rewrite, $aNowIdeaOrTip.category)}
					<li class="ajax_block_product{if $smarty.foreach.nowIdeasOrTips_list.first} first_item{elseif $smarty.foreach.nowIdeasOrTips_list.last} last_item{else} item{/if} product_accessories_description">

						{* Image *}
						<div class="image-left">

							<img src="{$link->getImageLink($aNowIdeaOrTip.link_rewrite, $aNowIdeaOrTip.id_image, 'accessories')|escape:'html'}" alt="{$aNowIdeaOrTip.legend|escape:'htmlall':'UTF-8'}" />

						</div>

						<div class="content-right">

							{* Nom du produit *}
							<p class="product-name">
								{$aNowIdeaOrTip.name|truncate:35:'...':true|escape:'htmlall':'UTF-8'}
							</p>

							{* Description *}
							<div class="block_description">
								{$aNowIdeaOrTip.description_short|strip_tags|truncate:100:'...'}
							</div>

							{* Bouton ajouter au panier *}
							{if !$PS_CATALOG_MODE && ($aNowIdeaOrTip.allow_oosp || $aNowIdeaOrTip.quantity > 0)}
								<a class="button-grey-and-green" href="{$aNowIdeaOrTipLink|escape:'htmlall':'UTF-8'}" title="{l s='En savoir plus'}">
									{l s='En savoir plus'}
								</a>
							{/if}

						</div>

						<span class="clearBoth"></span>

						<a class="link" href="{$aNowIdeaOrTipLink|escape:'htmlall':'UTF-8'}" title="{$aNowIdeaOrTip.name|escape:'htmlall':'UTF-8'}">{$aNowIdeaOrTip.name|escape:'htmlall':'UTF-8'}</a>

					</li>
				{/if}
			{/foreach}
		</ul>

		<span class="clearBoth"></span>

	</div>
{/if}