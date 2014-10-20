<div id="bandeau-product">
	<div class="container">

		<div class="left">

			<p class="titre-vert">{$nowProductType->name}</p>

			<h1>
				{$product->name|escape:'htmlall':'UTF-8'}
			</h1>

			<div class="desc"><p>{$product->description_short|truncate:550:'...'}</p></div>

			{*<a href="#"><span class="fleche-tuto-gauche"></span></a>*}
			<a href="#"><span class="fleche-tuto-droite"></span></a>

		</div>

		{if $have_image}
			<div class="right">
				<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'product-type-tuto')|escape:'html'}" alt="{$product->name|escape:'htmlall':'UTF-8'}" title="{$product->name|escape:'htmlall':'UTF-8'}" />
			</div>
		{else}
			<div class="right">
				<img src="{$img_prod_dir}{$lang_iso}-default-product-type-tuto.jpg" alt="{$product->name|escape:'htmlall':'UTF-8'}" title="{$product->name|escape:'htmlall':'UTF-8'}" />
			</div>
		{/if}

	</div>
</div>

<div class="container">
	<div class="rte">
		{$product->description}
	</div>
</div>