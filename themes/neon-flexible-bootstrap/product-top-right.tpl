
{if !$priceDisplay || $priceDisplay == 2}
	{assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, $priceDisplayPrecision)}
	{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
{elseif $priceDisplay == 1}
	{assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, $priceDisplayPrecision)}
	{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
{/if}

<div id="product-top-right">

	<!-- add to cart form-->
	<form id="buy_block"{if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0} class="hidden"{/if} action="{$link->getPageLink('cart')|escape:'html':'UTF-8'}" method="post">
		<!-- hidden datas -->
		<p class="hidden">
			<input type="hidden" name="token" value="{$static_token}" />
			<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
			<input type="hidden" name="add" value="1" />
			<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
		</p>

		<div class="content_prices">
			<!-- prices -->
			{if $product->show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}

				{if $product->online_only}
					<p class="online_only">{l s='Online only'}</p>
				{/if}

				<div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<p class="our_price_display">
						{if $priceDisplay >= 0 && $priceDisplay <= 2}
							<span id="our_price_display" itemprop="price">{convertPrice price=$productPrice}</span>
						<!--{if $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) OR !isset($display_tax_label))}
								{if $priceDisplay == 1}
									{l s='tax excl.'}
								{else}
									{l s='tax incl.'}
								{/if}
							{/if}-->
						{/if}
					</p>

					{if $product->on_sale}
						<img src="{$img_dir}onsale_{$lang_iso}.gif" alt="{l s='On sale'}" class="on_sale_img"/>
						<span class="on_sale">{l s='On sale!'}</span>
					{elseif $product->specificPrice AND $product->specificPrice.reduction AND $productPriceWithoutReduction > $productPrice}
						<span class="discount">{l s='Reduced price!'}</span>
					{/if}
					{if $priceDisplay == 2}
						<br />
						<span id="pretaxe_price"><span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span>&nbsp;{l s='tax excl.'}</span>
					{/if}
				</div>
				<p id="reduction_percent" {if !$product->specificPrice OR $product->specificPrice.reduction_type != 'percentage'} style="display:none;"{/if}>
					<span id="reduction_percent_display">
						{if $product->specificPrice AND $product->specificPrice.reduction_type == 'percentage'}
							-{$product->specificPrice.reduction*100}%
						{/if}
					</span>
				</p>
				<p id="reduction_amount" {if !$product->specificPrice OR $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|intval == 0} style="display:none"{/if}>
					<span id="reduction_amount_display">
					{if $product->specificPrice AND $product->specificPrice.reduction_type == 'amount' AND $product->specificPrice.reduction|intval != 0}
						-{convertPrice price=$productPriceWithoutReduction-$productPrice|floatval}
					{/if}
					</span>
				</p>
				<p id="old_price"{if !$product->specificPrice || !$product->specificPrice.reduction} class="hidden"{/if}>
					{if $priceDisplay >= 0 && $priceDisplay <= 2}
						<span id="old_price_display">{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction}{/if}</span>
					<!--{if $tax_enabled && $display_tax_label == 1}
							{if $priceDisplay == 1}
								{l s='tax excl.'}
							{else}
								{l s='tax incl.'}
							{/if}
						{/if}-->
					{/if}
				</p>
				{if $packItems|@count && $productPrice < $product->getNoPackPrice()}
					<p class="pack_price">
						{l s='Instead of'}
						<span style="text-decoration: line-through;">
							{convertPrice price=$product->getNoPackPrice()}
						</span>
					</p>
					<br class="clear" />
				{/if}
				{if $product->ecotax != 0}
					<p class="price-ecotax">
						{l s='Include'}
						<span id="ecotax_price_display">
							{if $priceDisplay == 2}
								{$ecotax_tax_exc|convertAndFormatPrice}
							{else}
								{$ecotax_tax_inc|convertAndFormatPrice}
							{/if}
						</span> {l s='For green tax'}
						{if $product->specificPrice AND $product->specificPrice.reduction}
							<br />{l s='(not impacted by the discount)'}
						{/if}
					</p>
				{/if}
				{if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
					{math equation="pprice / punit_price" pprice=$productPrice  punit_price=$product->unit_price_ratio assign=unit_price}
					<p class="unit-price">
						<span id="unit_price_display">
							{convertPrice price=$unit_price}
						</span> {l s='per'} {$product->unity|escape:'htmlall':'UTF-8'}
					</p>
				{/if}
				{*close if for show price*}
			{/if}
			{if isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS}{$HOOK_PRODUCT_ACTIONS}{/if}

			<div class="clear"></div>
		</div>

		{if NowProductTypeProduct::isProductTyped($product->id, NowProductType::TYPE_SUR_COMMANDE)}
			<div class="sur-commande">
				<p>{l s='Pour une commande ou une information sur ce produit contactez-nous par téléphone au'} <strong>{Configuration::get('PS_SHOP_PHONE')}</strong> {l s='ou par mail en cliquant sur le bouton ci-dessous.'}</p>
				<a href="{Context::getContext()->link->getPageLink('contact')}" class="button-rose-contact-us"><span>@</span>{l s='Contactez-nous'}</a>
			</div>
		{else}
			<div class="product-quantity">

				<!-- quantity wanted -->
				{if !$PS_CATALOG_MODE}
					<p id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
						<label>{l s='Quantity:'}</label><a href="#" data-field-qty="qty" class="btn btn-default button-minus product_quantity_down">
							<span><i class="icon-minus"></i></span>
						</a>
						<input type="text" name="qty" id="quantity_wanted" class="text btn btn-default button-minus " value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" />
						<a href="#" data-field-qty="qty" class="btn btn-default button-plus product_quantity_up ">
							<span><i class="icon-plus"></i></span>
						</a>
						<span class="clearfix"></span>
					</p>
				{/if}

				<!-- minimal quantity wanted -->
				<p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 OR !$product->available_for_order OR $PS_CATALOG_MODE} style="display: none;"{/if}>
					{l s='This product is not sold individually. You must select at least'} <b id="minimal_quantity_label">{$product->minimal_quantity}</b> {l s='quantity for this product.'}
				</p>
				{if $product->minimal_quantity > 1}
					<script type="text/javascript">
						checkMinimalQuantity();
					</script>
				{/if}
			</div>

			{* Hook permettant d'afficher les délais de livraison des transporteurs *}
			{hook h='displayCarrierDeliveryTimeList'}

			<div id="add_to_cart" {if (!$allow_oosp && $product->quantity <= 0) OR !$product->available_for_order OR (isset($restricted_country_mode) AND $restricted_country_mode) OR $PS_CATALOG_MODE}style="display:none"{/if} class="buttons_bottom_block">
				<p class="button-add-to-cart gradient">
					<span></span>
					<input type="submit" name="Submit" value="{l s='Add to cart'}" class="button-none" />
				</p>
			</div>

			{if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if}
		{/if}
	</form>

</div>