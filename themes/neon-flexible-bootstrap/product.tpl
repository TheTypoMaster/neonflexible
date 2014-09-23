{if $errors|@count == 0}

	{include file="$tpl_dir./product-script.tpl"}

	{include file="$tpl_dir./breadcrumb.tpl"}

	<div class="container">

		{if isset($confirmation) && $confirmation}
			<p class="confirmation">
				{$confirmation}
			</p>
		{/if}

		{include file="$tpl_dir./errors.tpl"}

		<div id="pb-right-column">
			<div id="image-block">
				{if $have_image}
					<span id="view_full_size">
					<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html'}"{if $jqZoomEnabled && $have_image} class="jqzoom"{/if} title="{if !empty($cover.legend)}{$cover.legend|escape:'htmlall':'UTF-8'}{else}{$product->name|escape:'htmlall':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'htmlall':'UTF-8'}{else}{$product->name|escape:'htmlall':'UTF-8'}{/if}" id="bigpic" width="{$largeSize.width}" height="{$largeSize.height}"/>
				</span>
				{else}
					<span id="view_full_size">
					<img src="{$img_prod_dir}{$lang_iso}-default-large_default.jpg" id="bigpic" alt="" title="{$product->name|escape:'htmlall':'UTF-8'}" width="{$largeSize.width}" height="{$largeSize.height}" />
				</span>
				{/if}
			</div>
			{if isset($images) && count($images) > 0}
				<!-- thumbnails -->
				<div id="views_block" class="clearfix {if isset($images) && count($images) < 2}hidden{/if}">
					{if isset($images) && count($images) > 3}<span class="view_scroll_spacer"><a id="view_scroll_left" class="hidden" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Previous'}</a></span>{/if}
					<div id="thumbs_list">
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
										<a href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html'}" rel="other-views" class="thickbox{if $smarty.foreach.thumbnails.first} shown{/if}" title="{$imageTitlte}">
											<img id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium_default')|escape:'html'}" alt="{$imageTitlte}" title="{$imageTitlte}" height="{$mediumSize.height}" width="{$mediumSize.width}" />
										</a>
									</li>
								{/foreach}
							{/if}
						</ul>
					</div>
					{if isset($images) && count($images) > 3}<a id="view_scroll_right" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Next'}</a>{/if}
				</div>
			{/if}
		</div>

	</div>
{/if}