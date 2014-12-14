<ul id="more_info_tabs" >

	{assign var=ok value=false}

	{if $product->description && preg_match('#jqExempleUtilisation#', $product->description)}
		<li class="{if !$ok}active{/if}"><span><a href="#exemple">{l s='Exemple d\'utilisation'}</a></span></li>
		{assign var=ok value=true}
	{/if}

	{if $product->description && preg_match('#jqDescriptif#', $product->description)}
		<li class="{if !$ok}active{/if}"><span><a href="#description">{l s='Description'}</a></span></li>
		{assign var=ok value=true}
	{/if}

	{if $features}
		<li class="{if !$ok}active{/if}"><span><a href="#features">{l s='Caractéristiques'}</a></span></li>
		{assign var=ok value=true}
	{/if}

	{if $attachments}
		<li class="{if !$ok}active{/if}"><span><a href="#download">{l s='Download'}</a></span></li>
		{assign var=ok value=true}
	{/if}

	{if isset($accessories) AND $accessories}
		<li class="{if !$ok}active{/if}"><span><a href="#accessories">{l s='Produits complémentaires'}</a></span></li>
		{assign var=ok value=true}
	{/if}

	{if isset($product) && $product->customizable}
		<li class="{if !$ok}active{/if}"><span><a href="#custom">{l s='Product customization'}</a></span></li>
		{assign var=ok value=true}
	{/if}

	{$HOOK_PRODUCT_TAB}

</ul>
<span class="clearBoth"></span>