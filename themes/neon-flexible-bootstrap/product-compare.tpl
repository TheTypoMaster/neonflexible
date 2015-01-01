{if $comparator_max_item && !is_array($nowCategorySlide)}
	<form method="post" action="{$link->getPageLink('products-comparison')|escape:'html':'UTF-8'}" class="compare-form">
		<p class="cart_navigation clearfix">
			<button type="submit" class="button btn btn-default standard-checkout button-medium bt_compare bt_compare{if isset($paginationId)}_{$paginationId}{/if}" disabled="disabled">
				<span>{l s='Compare'} (<span class="total-compare-val">{count($compared_products)}</span>)</span>
			</button>
			<input type="hidden" name="compare_product_count" class="compare_product_count" value="{count($compared_products)}" />
			<input type="hidden" name="compare_product_list" class="compare_product_list" value="" />
		</p>
	</form>
	{if !isset($paginationId) || $paginationId == ''}
		{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
		{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
		{addJsDef comparator_max_item=$comparator_max_item}
		{addJsDef comparedProductsIds=$compared_products}
	{/if}
{/if}

