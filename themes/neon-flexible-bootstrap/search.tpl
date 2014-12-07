{capture name=path}{l s='Search'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<div class="container">
	<h1 class="titre-size-1" {if isset($instantSearch) && $instantSearch}id="instant_search_results"{/if}>
		{l s='Search'}&nbsp;{if $nbProducts > 0}"{if isset($search_query) && $search_query}{$search_query|escape:'htmlall':'UTF-8'}{elseif $search_tag}{$search_tag|escape:'htmlall':'UTF-8'}{elseif $ref}{$ref|escape:'htmlall':'UTF-8'}{/if}"{/if}
		{if isset($instantSearch) && $instantSearch}<a href="#" class="close">{l s='Return to the previous page'}</a>{/if}
	</h1>

	{include file="$tpl_dir./errors.tpl"}
	{if !$nbProducts}
		<p class="warning">
			{if isset($search_query) && $search_query}
				{l s='No results were found for your search'}&nbsp;"{if isset($search_query)}{$search_query|escape:'htmlall':'UTF-8'}{/if}"
			{elseif isset($search_tag) && $search_tag}
				{l s='No results were found for your search'}&nbsp;"{$search_tag|escape:'htmlall':'UTF-8'}"
			{else}
				{l s='Please enter a search keyword'}
			{/if}
		</p>
	{else}


		<h3 class="nbresult">
			<span class="big">
				{if $nbProducts == 1}
					{l s='%d result has been found.' sprintf=$nbProducts|intval}
				{else}
					{l s='%d results have been found.' sprintf=$nbProducts|intval}
				{/if}
			</span>
		</h3>

		{* Filtres haut *}
		{include file="$tpl_dir./filter-top.tpl"}

		<div id="center-column" class="left">

			{include file="$tpl_dir./filter.tpl" class="top"}

			{* Liste des produits *}
			{include file="./product-list.tpl" products=$products}

			{include file="$tpl_dir./filter.tpl" class="bottom"}

		</div>
	{/if}
</div>
