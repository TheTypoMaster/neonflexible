{include file="$tpl_dir./breadcrumb.tpl"}

{if isset($category)}
	{if $category->id AND $category->active}

		{* Bandeau catégorie *}
		<div id="bandeau-category">

			<div class="container">

				<div class="left">

					<p class="titre-vert">{l s='Products'}</p>

					<h1>
						{$category->name|escape:'htmlall':'UTF-8'}
						{if isset($categoryNameComplement)}
							{$categoryNameComplement|escape:'htmlall':'UTF-8'}
						{/if}
					</h1>

					<div class="desc">{$category->description|truncate:550:'...'}</div>

				</div>

				{if $category->id_image}
					<div class="right">
						<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'bandeau_category')|escape:'html'}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" />
					</div>
				{/if}

			</div>

		</div>

		{* Contenu des produits de cette catégorie *}
		<div class="container">

			{include file="$tpl_dir./errors.tpl"}


			{if $products}
				{* Filtres haut *}
				<div class="filter-top">

					{include file="./product-sort.tpl"}
					{include file="./nbr-product-page.tpl"}

					<div id="displayed-list">
						<p>{l s='Affichage :'}</p>
						<ul>
							<li>
								<img src="{$img_dir}theme/mode-liste.png" alt="" data-type-affichage="list" class="jqAffichage" />
							</li>
							<li>
								<img src="{$img_dir}theme/mode-block.png" alt="" data-type-affichage="block" class="jqAffichage" />
							</li>
						</ul>
						<span class="clearBoth"></span>
					</div>
					<span class="clearBoth"></span>

				</div>

				{* colonne de gauche avec les filtres à facette *}
				<div id="left-column" class="left">
					{hook h="displayLeftColumn"}
					{$HOOK_LEFT_COLUMN}
				</div>

				<div class="left">

					<div class="filter">
						{include file="$tpl_dir./category-count.tpl"}
						{include file="$tpl_dir./pagination.tpl"}
						{include file="./product-compare.tpl"}
					</div>

					{* Liste des produits *}
					{include file="./product-list.tpl" products=$products}

				</div>
			{/if}

		</div>
	{elseif $category->id}
		<div class="container">
			<p class="warning">{l s='This category is currently unavailable.'}</p>
		</div>
	{/if}
{/if}
