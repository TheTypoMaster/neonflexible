{if isset($no_follow) AND $no_follow}
	{assign var='no_follow_text' value='rel="nofollow"'}
{else}
	{assign var='no_follow_text' value=''}
{/if}

{if isset($p) AND $p}
	{if isset($smarty.get.id_category) && $smarty.get.id_category && isset($category)}
		{if !isset($current_url)}
		{assign var='requestPage' value=$link->getPaginationLink('category', $category, false, false, true, false)}
		{else}
			{assign var='requestPage' value=$current_url}
		{/if}
		{assign var='requestNb' value=$link->getPaginationLink('category', $category, true, false, false, true)}
	{elseif isset($smarty.get.id_manufacturer) && $smarty.get.id_manufacturer && isset($manufacturer)}
		{assign var='requestPage' value=$link->getPaginationLink('manufacturer', $manufacturer, false, false, true, false)}
		{assign var='requestNb' value=$link->getPaginationLink('manufacturer', $manufacturer, true, false, false, true)}
	{elseif isset($smarty.get.id_supplier) && $smarty.get.id_supplier && isset($supplier)}
		{assign var='requestPage' value=$link->getPaginationLink('supplier', $supplier, false, false, true, false)}
		{assign var='requestNb' value=$link->getPaginationLink('supplier', $supplier, true, false, false, true)}
	{else}
		{assign var='requestPage' value=$link->getPaginationLink(false, false, false, false, true, false)}
		{assign var='requestNb' value=$link->getPaginationLink(false, false, true, false, false, true)}
	{/if}
	<!-- Pagination -->
	<div id="pagination{if isset($paginationId)}_{$paginationId}{/if}" class="pagination">
	{if $start!=$stop}
		<ul>
		{if $p != 1}
			{assign var='p_previous' value=$p-1}
			<li id="pagination_previous{if isset($paginationId)}_{$paginationId}{/if}" class="pagination_previous"><a {$no_follow_text} href="{$link->goPage($requestPage, $p_previous)}">&laquo;&nbsp;{l s='Previous'}</a></li>
		{else}
			<li id="pagination_previous{if isset($paginationId)}_{$paginationId}{/if}" class="disabled pagination_previous"><span>&laquo;&nbsp;{l s='Previous'}</span></li>
		{/if}
		{if $start==3}
			<li><a {$no_follow_text}  href="{$link->goPage($requestPage, 1)}">1</a></li>
			<li><a {$no_follow_text}  href="{$link->goPage($requestPage, 2)}">2</a></li>
		{/if}
		{if $start==2}
			<li><a {$no_follow_text}  href="{$link->goPage($requestPage, 1)}">1</a></li>
		{/if}
		{if $start>3}
			<li><a {$no_follow_text}  href="{$link->goPage($requestPage, 1)}">1</a></li>
			<li class="truncate">...</li>
		{/if}
		{section name=pagination start=$start loop=$stop+1 step=1}
			{if $p == $smarty.section.pagination.index}
				<li class="current"><span>{$p|escape:'htmlall':'UTF-8'}</span></li>
			{else}
				<li><a {$no_follow_text} href="{$link->goPage($requestPage, $smarty.section.pagination.index)}">{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}</a></li>
			{/if}
		{/section}
		{if $pages_nb>$stop+2}
			<li class="truncate">...</li>
			<li><a href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
		{/if}
		{if $pages_nb==$stop+1}
			<li><a href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
		{/if}
		{if $pages_nb==$stop+2}
			<li><a href="{$link->goPage($requestPage, $pages_nb-1)}">{$pages_nb-1|intval}</a></li>
			<li><a href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
		{/if}
		{if $pages_nb > 1 AND $p != $pages_nb}
			{assign var='p_next' value=$p+1}
			<li id="pagination_next{if isset($paginationId)}_{$paginationId}{/if}" class="pagination_next"><a {$no_follow_text} href="{$link->goPage($requestPage, $p_next)}">{l s='Next'}&nbsp;&raquo;</a></li>
		{else}
			<li id="pagination_next{if isset($paginationId)}_{$paginationId}{/if}" class="disabled pagination_next"><span>{l s='Next'}&nbsp;&raquo;</span></li>
		{/if}
		</ul>
	{/if}
	</div>
	<!-- /Pagination -->

	<a href="javascript:void(0);" class="jqDisplayedAllProductsButton">{l s='Display all'}</a>
{/if}