{if isset($product) && $product->description}
	<div class="product-tab">
		{$product->description|replace:'class="jqExempleUtilisation"':'id="exemple" class="no-border"'|replace:'class="jqDescriptif"':'id="description"'}
	</div>

	<script type="text/javascript">
		if ($('#exemple').length == 0) {
			$('#description').addClass('no-border')
		}
	</script>
{/if}