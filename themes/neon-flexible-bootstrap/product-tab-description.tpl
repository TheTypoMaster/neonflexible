{if isset($product) && $product->description}
	<div class="product-tab">
		{$product->description|replace:'class="jqExempleUtilisation"':'id="exemple" class="no-border"'|replace:'class="jqDescriptif"':'id="description"'}
	</div>
{/if}