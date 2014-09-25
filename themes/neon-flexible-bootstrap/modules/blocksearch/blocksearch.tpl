
<!-- Block search module -->
<div id="search_block_left" class="block exclusive">
	<h4 class="title_block">{l s='Search' mod='blocksearch'}</h4>
	<form method="get" action="{$link->getPageLink('search', true)|escape:'html'}" id="searchbox">
		<p class="block_content">
			<label for="search_query_block">{l s='Search products:' mod='blocksearch'}</label>
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query" type="text" id="search_query_block" name="search_query" value="{$search_query|escape:'html':'UTF-8'|stripslashes}" />
			<input type="submit" id="search_button" class="button_mini" value="{l s='Go' mod='blocksearch'}" />
		</p>
	</form>
</div>
{include file="$self/blocksearch-instantsearch.tpl"}
<!-- /Block search module -->
