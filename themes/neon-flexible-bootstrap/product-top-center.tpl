<div id="product-top-center">

	{* Titre *}
	<h1 itemprop="name">{$product->name|escape:'htmlall':'UTF-8'}</h1>

	{* Description *}
	{if $product->description_short OR $packItems|@count > 0}
		<div id="short_description_block">

			{if $product->description_short}
				<div id="short_description_content" class="rte align_justify" itemprop="description">
					{$product->description_short}
				</div>
			{/if}

			{if $packItems|@count > 0}
				<div class="short_description_pack">
					<h3>{l s='Pack content'}</h3>
					{foreach from=$packItems item=packItem}
						<div class="pack_content">
							{$packItem.pack_quantity} x <a href="{$link->getProductLink($packItem.id_product, $packItem.link_rewrite, $packItem.category)|escape:'html'}">{$packItem.name|escape:'htmlall':'UTF-8'}</a>
							<p>{$packItem.description_short}</p>
						</div>
					{/foreach}
				</div>
			{/if}

		</div>
	{/if}

	{* Hooks *}
	{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
	{if isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS}{$HOOK_PRODUCT_ACTIONS}{/if}

	{* Description *}
	<div class="reference-stock">

		{* Référence du produit *}
		<p id="product_reference" {if isset($groups) OR !$product->reference}style="display: none;"{/if}>
			<label>{l s='Ref :'} </label>
			<span class="editable" itemprop="sku">{$product->reference|escape:'htmlall':'UTF-8'}</span>
		</p>

		{* Quantité en stock *}
		{if ($display_qties == 1 && !$PS_CATALOG_MODE && $product->available_for_order)}
			{if $product->quantity <= 0}
				<p id="pQuantityAvailable" class="noAvailable">
					<span id="quantityAvailableTxt">{l s='No available'}</span></p>
			{else}
				<p id="pQuantityAvailable">
					<span {if $product->quantity > 1} style="display: none;"{/if} id="quantityAvailableTxt">{l s='Item in stock'} <span id="quantityAvailable">({$product->quantity|intval})</span></span>
					<span {if $product->quantity == 1} style="display: none;"{/if} id="quantityAvailableTxtMultiple">{l s='Items in stock'} <span id="quantityAvailable">({$product->quantity|intval})</span></span>
				</p>
			{/if}
		{/if}

	</div>

	{* Listes des images *}
	{if isset($images) && count($images) > 0}

		<div id="views_block" class="clearfix {if isset($images) && count($images) < 2}hidden{/if}">

			{if isset($images) && count($images) > 3}
				{*<span class="view_scroll_spacer">*}
					<a id="view_scroll_left" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">
						{l s='Previous'}
					</a>
				{*</span>*}
			{/if}

			<div id="thumbs_list" data-nb-image="3">

				<ul id="thumbs_list_frame">

					{if isset($images)}
						{foreach from=$images item=image name=thumbnails}
							{assign var=imageIds value="`$product->id`-`$image.id_image`"}
							{if !empty($image.legend)}
								{assign var=imageTitlte value=$image.legend|escape:'htmlall':'UTF-8'}
							{else}
								{assign var=imageTitlte value=$product->name|escape:'htmlall':'UTF-8'}
							{/if}

							<li id="thumbnail_{$image.id_image}">
								<a
										{if $jqZoomEnabled && $have_image && !$content_only}
											href="javascript:void(0);"
											rel="{literal}{{/literal}gallery: 'gal1', smallimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'large_default')|escape:'html':'UTF-8'}',largeimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}'{literal}}{/literal}"
										{else}
											href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}"
											data-fancybox-group="other-views"
											class="fancybox{if $image.id_image == $cover.id_image} shown{/if}"
										{/if}
										title="{$imageTitle}">
									<img class="img-responsive" id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'cart_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" height="{$mediumSize.height}" width="{$mediumSize.width}" itemprop="image" />
								</a>
							</li>

						{/foreach}
					{/if}

				</ul>

			</div>

			{if isset($images) && count($images) > 3}
				<a id="view_scroll_right" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">
					{l s='Next'}
				</a>
			{/if}

		</div>

	{/if}

</div>