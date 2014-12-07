{if isset($products)}
	<ul id="product_list" class="clear mode-{if isset($smarty.cookies['category-mode'])}{$smarty.cookies['category-mode']}{else}list{/if} {if isset($class) && $class} {$class}{/if}{if isset($active) && $active == 1} active{/if}">
	{foreach from=$products item=product name=products}
		<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if ($smarty.foreach.products.index %4) == 3}alternate_item{else}item{/if} clearfix {if isset($product.product_type) && $product.product_type.type == NowProductType::TYPE_CONTENT}is_content_type{/if}">

			{if isset($product.product_type) && $product.product_type.type == NowProductType::TYPE_CONTENT}

				<div class="left_block_product_type">
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'product_type_category_block')|escape:'html'}" alt="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" data-mode-affichage="block" />
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_medium')|escape:'html'}" alt="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" data-mode-affichage="list" />
				</div>

				<div class="center_block_product_type">

					<div class="left_block_product_type2">
						<p class="product_type_name">{$product.product_type.name|escape:'htmlall':'UTF-8'}</p>
						<h2 class="product_type_product">{$product.name|escape:'htmlall':'UTF-8'}</h2>
						<p class="product_desc">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</p>
					</div>

					<div class="right_block_product_type">
						<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}" class="button-rose-and-grey">
							{$product.product_type.button_name}
						</a>
					</div>
				</div>


			{else}
				<div class="left_block product_image">
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'category_mode_block')|escape:'html'}" alt="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}" class="image"/>
				</div>

				<div class="center_block">
					<h2 class="product_title">{$product.name|truncate:55:'...':true|escape:'htmlall':'UTF-8'}</h2>
					<p class="product_desc">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</p>
				</div>

				<div class="right_block">
					{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
						<span class="on_sale">{l s='On sale!'}</span>
					{elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
						<span class="discount">{l s='Reduced price!'}</span>
					{/if}

					{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
						<div class="content_price">
							{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
								<span class="price" style="display: inline;">
								{if !$priceDisplay}
									{convertPrice price=$product.price}
								{else}
									{convertPrice price=$product.price_tax_exc}
								{/if}
							</span><br />
							{/if}
							{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
								<span class="availability">
								{if ($product.allow_oosp || $product.quantity > 0)}
									{l s='Available'}
								{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
									{l s='Product available with different options'}
								{else}
									<span class="warning_inline">{l s='Out of stock'}</span>
								{/if}
							</span>
							{/if}
						</div>
						{if isset($product.online_only) && $product.online_only}
							<span class="online_only">{l s='Online only'}</span>
						{/if}
					{/if}

					<img src="{$img_dir}theme/etoiles.png" alt="" class="note" />

					{if isset($product.product_type) && $product.product_type.type == NowProductType::TYPE_BUTTON}
						<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">
							<span class="button-add-to-cart gradient on-order"><span></span>{$product.product_type.button_name}</span>
						</a>
					{elseif ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
						{if ($product.allow_oosp || $product.quantity > 0)}
							{if isset($static_token)}
								<a class="button ajax_add_to_cart_button gradient button-add-to-cart" data-id-product="{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html'}" title="{l s='Add to cart'}"><span></span>{l s='Add to cart'}</a>
							{else}
								<a class="button ajax_add_to_cart_button gradient button-add-to-cart" data-id-product="{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}", false)|escape:'html'}" title="{l s='Add to cart'}"><span></span>{l s='Add to cart'}</a>
							{/if}
						{else}
							<span class="button-add-to-cart gradient"><span></span>{l s='Add to cart'}</span><br />
						{/if}
					{/if}
					{if $page_name != 'index'}
						<div class="functional-buttons clearfix">
							{hook h='displayProductListFunctionalButtons' product=$product}
							{if isset($comparator_max_item) && $comparator_max_item}
								<div class="compare">
									<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}">{l s='Add to Compare'}</a>
								</div>
							{/if}
						</div>
					{/if}
				</div>
			{/if}
			<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}" class="link"></a>
		</li>
	{/foreach}
	</ul>
	<span class="clearBoth"></span>
	{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
	{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
	{addJsDef comparator_max_item=$comparator_max_item}
	{addJsDef comparedProductsIds=$compared_products}
{/if}
