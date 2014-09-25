{if isset($no_follow) AND $no_follow}
    {assign var='no_follow_text' value='rel="nofollow"'}
{else}
    {assign var='no_follow_text' value=''}
{/if}


{if isset($p) AND $p}

    {assign var='requestPage' value=$link->getPaginationLink(false, false, false, false, true, false)}
    {assign var='requestNb' value=$link->getPaginationLink(false, false, true, false, false, true)}

    <!-- Pagination -->
    <div id="pagination_av" class="pagination_av">
    {if $start!=$stop}
        <ul class="btn_pagination_av">
        {if $p != 1}
            {assign var='p_previous' value=$p-1}
            <li id="pagination_previous_av"><a {$no_follow_text} rel="{$p_previous}" class="pagination_page_number_av" href="{$link->goPage($requestPage, $p_previous)}">&laquo;&nbsp;{l s='Précédent' mod='avisverifies'}</a></li>
        {else}
            <li id="pagination_previous_av" class="disabled_av"><span>&laquo;&nbsp;{l s='Précédent' mod='avisverifies'}</span></li>
        {/if}
        {if $start>3}
            <li><a {$no_follow_text} rel="1" class="pagination_page_number_av" href="{$link->goPage($requestPage, 1)}">1</a></li>
            <li class="truncate">...</li>
        {/if}
        {section name=pagination start=$start loop=$stop+1 step=1}
            {if $p == $smarty.section.pagination.index}
                <li class="current"><span>{$p|escape:'htmlall':'UTF-8'}</span></li>
            {else}
                <li><a {$no_follow_text} rel="{$smarty.section.pagination.index}" class="pagination_page_number_av" href="{$link->goPage($requestPage, $smarty.section.pagination.index)}">{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}</a></li>
            {/if}
        {/section}
        {if $pages_nb>$stop+2}
            <li class="truncate">...</li>
            <li><a rel="{$pages_nb}" class="pagination_page_number_av" href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
        {/if}
        {if $pages_nb > 1 AND $p != $pages_nb}
            {assign var='p_next' value=$p+1}
            <li id="pagination_next_av"><a class="pagination_page_number_av" {$no_follow_text} rel="{$p_next}" href="{$link->goPage($requestPage, $p_next)}">{l s='Suivant' mod='avisverifies'}&nbsp;&raquo;</a></li>
        {else}
            <li id="pagination_next_av" class="disabled_av"><span>{l s='Suivant' mod='avisverifies'}&nbsp;&raquo;</span></li>
        {/if}
            <div class="clear"></div>
        </ul>
    {/if}
    </div>

    <!-- /Pagination -->

{/if}