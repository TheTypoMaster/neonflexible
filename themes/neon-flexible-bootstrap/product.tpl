{if $errors|@count == 0}

	{include file="$tpl_dir./product-script.tpl"}

		{if NowProductTypeProduct::isProductTyped($product->id) && !NowProductTypeProduct::isProductTyped($product->id, NowProductType::TYPE_SUR_COMMANDE)}

			{include file="$tpl_dir./breadcrumb.tpl" noBorder=true}

			<div id="product-type-tuto">
				{include file="$tpl_dir./product-type-tuto.tpl"}
			</div>
		{else}

			{include file="$tpl_dir./breadcrumb.tpl"}

			<div class="container">

				{if isset($confirmation) && $confirmation}
					<p class="confirmation">
						{$confirmation}
					</p>
				{/if}

				{include file="$tpl_dir./errors.tpl"}

				<div id="product-left">
					{include file="$tpl_dir./product-top-left.tpl"}
					{include file="$tpl_dir./product-top-center.tpl"}

					{if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}

					<span class="clearBoth"></span>

					{include file="$tpl_dir./product-tab.tpl"}
					{include file="$tpl_dir./product-tab-description.tpl"}
					{include file="$tpl_dir./product-tab-caracteristiques.tpl"}
					{include file="$tpl_dir./product-tab-attachments.tpl"}
				</div>

				<div id="product-right">
					{include file="$tpl_dir./product-top-right.tpl"}
				</div>

				<span class="clearBoth"></span>

				{include file="$tpl_dir./product-tab-accessoires.tpl"}
				{if isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT}{$HOOK_PRODUCT_TAB_CONTENT}{/if}

			</div>
		{/if}
{/if}