<ul id="more_info_tabs" >

	{if $product->description && preg_match('#jqExempleUtilisation#', $product->description)}
		<li class="active"><span><a href="#exemple">{l s='Exemple d\'utilisation'}</a></span></li>
	{/if}

	{if $product->description && preg_match('#jqDescriptif#', $product->description)}
		<li><span><a href="#descriptif">{l s='Descriptif'}</a></span></li>
	{/if}

	{if $features}
		<li><span><a href="#features">{l s='Caractéristiques'}</a></span></li>
	{/if}

	{if $attachments}
		<li><span><a href="#download">{l s='Download'}</a></span></li>
	{/if}

	{if isset($accessories) AND $accessories}
		<li><span><a href="#accessories">{l s='Produits complémentaires'}</a></span></li>
	{/if}

	{if isset($product) && $product->customizable}
		<li><span><a href="#custom">{l s='Product customization'}</a></span></li>
	{/if}

	{$HOOK_PRODUCT_TAB}

</ul>
<span class="clearBoth"></span>