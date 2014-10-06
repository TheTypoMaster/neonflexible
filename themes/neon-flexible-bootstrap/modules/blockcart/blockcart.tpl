<div class="container">

	<div class="header_right">

		{if in_array(Context::getContext()->controller->php_self, array("authentication", "order"))}
			<div class="shop-return">
				<a href="{Context::getContext()->link->getPageLink('index')}">{l s='Retour à la boutique' mod='blockcart'}</a>
			</div>
		{else}

			<div class="espace_pro">
				<a href="{Context::getContext()->link->getPageLink('index')}">{l s='Espace Pro' mod='blockcart'}</a>
			</div>

			{if count($languages) > 1}

				<div class="languages">
					{foreach $languages as $language}
					{if $language.iso_code == $lang_iso}
					<img src="{$img_dir}/theme/lang/{$language.iso_code}-on.png" alt="{$language.name}" />
					{else}
					{assign var=indice_lang value=$language.id_lang}
					{if isset($lang_rewrite_urls.$indice_lang)}
					<a href="{$lang_rewrite_urls.$indice_lang|escape:htmlall}" title="{$language.name}">
						{else}
						<a href="{$link->getLanguageLink($language.id_lang)|escape:htmlall}" title="{$language.name}">
							{/if}
							<img src="{$img_dir}/theme/lang/{$language.iso_code}.png" alt="{$language.name}" />
						</a>
						{/if}
						{/foreach}
				</div>

			{/if}

			<div class="my_account">
				<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='My account' mod='blockcart'}">
					{l s='My account' mod='blockcart'}
				</a>
			</div>

			<div id="shopping_cart">
				<a href="{$link->getPageLink($order_process, true)|escape:'html':'UTF-8'}" title="{l s='View my shopping cart' mod='blockcart'}" rel="nofollow">
					{l s='Cart:' mod='blockcart'}
					<span class="ajax_cart_quantity{if $cart_qties == 0} unvisible{/if}">{$cart_qties}</span>
					<span class="ajax_cart_product_txt{if $cart_qties != 1} unvisible{/if}">{l s='product' mod='blockcart'}</span>
					<span class="ajax_cart_product_txt_s{if $cart_qties < 2} unvisible{/if}">{l s='products' mod='blockcart'}</span>
					<span class="ajax_cart_total{if $cart_qties == 0} unvisible{/if}">
						{if $cart_qties > 0}
							{if $priceDisplay == 1}
								{assign var='blockcart_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}
								{convertPrice price=$cart->getOrderTotal(false, $blockcart_cart_flag)}
							{else}
								{assign var='blockcart_cart_flag' value='Cart::BOTH_WITHOUT_SHIPPING'|constant}
								{convertPrice price=$cart->getOrderTotal(true, $blockcart_cart_flag)}
							{/if}
						{/if}
					</span>
					<span class="ajax_cart_no_product{if $cart_qties > 0} unvisible{/if}">{l s='(empty)' mod='blockcart'}</span>
					{if $ajax_allowed && isset($blockcart_top) && !$blockcart_top}
						<span class="block_cart_expand{if !isset($colapseExpandStatus) || (isset($colapseExpandStatus) && $colapseExpandStatus eq 'expanded')} unvisible{/if}">&nbsp;</span>
						<span class="block_cart_collapse{if isset($colapseExpandStatus) && $colapseExpandStatus eq 'collapsed'} unvisible{/if}">&nbsp;</span>
					{/if}
					<b class="caret"></b>
				</a>
			</div>
		{/if}

	</div>
</div>