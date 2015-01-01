<div id="product-top-left">

	<div id="image-block">

		{if $have_image}
			<span id="view_full_size">
				<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html'}"{if $jqZoomEnabled && $have_image} class="jqzoom"{/if} title="{if !empty($cover.legend)}{$cover.legend|escape:'htmlall':'UTF-8'}{else}{$product->name|escape:'htmlall':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'htmlall':'UTF-8'}{else}{$product->name|escape:'htmlall':'UTF-8'}{/if}" id="bigpic" width="{$largeSize.width}" height="{$largeSize.height}" itemprop="image" />
			</span>
		{else}
			<span id="view_full_size">
				<img src="{$img_prod_dir}{$lang_iso}-default-large_default.jpg" id="bigpic" alt="" title="{$product->name|escape:'htmlall':'UTF-8'}" width="{$largeSize.width}" height="{$largeSize.height}" itemprop="image" />
			</span>
		{/if}

	</div>

</div>