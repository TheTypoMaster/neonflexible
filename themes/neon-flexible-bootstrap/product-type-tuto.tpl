<div id="bandeau-product">
	<div class="container">

		<div class="left">

			<p class="titre-vert">{$nowProductType->name}</p>

			<h1>
				{$product->name|escape:'htmlall':'UTF-8'}
			</h1>

			<div class="desc"><p>{$product->description_short|truncate:550:'...'}</p></div>

			{*<span class="fleche-tuto-gauche"></span>*}
			{*<span class="fleche-tuto-droite"></span>*}



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

					<div id="thumbs_list" data-nb-image="5">

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


		<div id="image-block">
			{if $have_image}
				<div class="right">
					<span id="view_full_size">
						<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'product-type-tuto')|escape:'html'}" class="img-responsive{if $jqZoomEnabled && $have_image} jqzoom{/if}" title="{if !empty($cover.legend)}{$cover.legend|escape:'htmlall':'UTF-8'}{else}{$product->name|escape:'htmlall':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'htmlall':'UTF-8'}{else}{$product->name|escape:'htmlall':'UTF-8'}{/if}" id="bigpic" width="{$largeSize.width}" height="{$largeSize.height}" itemprop="image" />
					</span>
				</div>
			{else}
				<div class="right">
					<span id="view_full_size">
						<img src="{$img_prod_dir}{$lang_iso}-default-product-type-tuto.jpg" alt="{$product->name|escape:'htmlall':'UTF-8'}" title="{$product->name|escape:'htmlall':'UTF-8'}" id="bigpic" class="img-responsive" itemprop="image" />
					</span>
				</div>
			{/if}
		</div>

	</div>
</div>

<div class="container">
	<div class="rte">
		{$product->description}
	</div>

	{include file="$tpl_dir./product-tab-accessoires.tpl"}

</div>