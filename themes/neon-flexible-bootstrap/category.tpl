{include file="$tpl_dir./breadcrumb.tpl"}

{if isset($category)}
	{if $category->id AND $category->active}

		{* Bandeau catégorie *}
		<div id="bandeau-category">

			<div class="container">

				{if $category->id_image}
					<div class="right">
						<img class="img-responsive" src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'bandeau_category')|escape:'html'}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" />
					</div>
				{/if}

				<div class="left">

					{assign var=nowCategorySlide value=NowCategorySlide::getCategorySlideByIdCategory($category->id)}
					{if is_array($nowCategorySlide)}
						<p class="titre-vert">{l s='Job'}</p>
					{else}
						<p class="titre-vert">{l s='Products'}</p>
					{/if}
					<h1 itemprop="name">
						{$category->name|escape:'htmlall':'UTF-8'}
						{if isset($categoryNameComplement)}
							{$categoryNameComplement|escape:'htmlall':'UTF-8'}
						{/if}
					</h1>

					<div class="desc" itemprop="description">{$category->description|truncate:550:'...'}</div>

				</div>

			</div>

		</div>

		{if $nb_products > 0}
			{* Contenu des produits de cette catégorie *}
			<div class="container">

				{include file="$tpl_dir./errors.tpl"}


				{if $products}
					{* Filtres haut *}
					{include file="$tpl_dir./filter-top.tpl"}

					{* colonne de gauche avec les filtres à facette *}
					<div id="left-column" class="left">
						{hook h="displayLeftColumn"}
					</div>

					<div id="center-column" class="left">

						{include file="$tpl_dir./filter.tpl" class="top" paginationId=false}

						{* Liste des produits *}
						{include file="./product-list.tpl" products=$products}

						{include file="$tpl_dir./filter.tpl" class="bottom" paginationId="bottom"}

					</div>
				{/if}

			</div>
		{elseif $category->id == 1 OR $nb_products == 0}
			<div class="container">
				<p class="warning">{l s='There are no products in this category'}</p>
			</div>
		{/if}
	{elseif $category->id}
		<div class="container">
			<p class="warning">{l s='This category is currently unavailable.'}</p>
		</div>
	{/if}
{/if}
