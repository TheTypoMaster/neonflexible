{if $comparator_max_item}
{if !isset($paginationId) || $paginationId == ''}
<script type="text/javascript">
// <![CDATA[
	var min_item = '{l s='Please select at least one product' js=1}';
	var max_item = "{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}";
//]]>
</script>
{/if}
	<form method="post" action="{$link->getPageLink('products-comparison')|escape:'html'}" onsubmit="true" class="product-compare">
		<p>
			<input type="submit" id="bt_compare{if isset($paginationId)}_{$paginationId}{/if}" class="button bt_compare" value="{l s='Compare'}" />
			<input type="hidden" name="compare_product_list" class="compare_product_list" value="" />
			&nbsp;(<span>0</span>)
		</p>
	</form>
{/if}

