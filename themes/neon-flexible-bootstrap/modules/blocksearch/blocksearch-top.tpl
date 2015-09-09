<div class="col-sm-6 col-md-5 col-md-push-1 col-lg-3 col-lg-push-0 hidden-xs clearfix">
	<div id="search_block_top">
		<form method="get" action="{$link->getPageLink('search')|escape:'html'}" id="searchbox">

			<label for="search_query_top"><!-- image on background --></label>
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query" type="text" id="search_query_top" name="search_query" value="{$search_query|escape:'html':'UTF-8'|stripslashes}"  placeholder="{l s='Rechercher' mod='blocksearch'}" />
			<input type="submit" name="submit_search" id="search_query_submit" value="{l s='OK' mod='blocksearch'}" class="button" />

		</form>
		<div class="clearfix"></div>
	</div>
	{include file="$self/blocksearch-instantsearch.tpl"}
</div>