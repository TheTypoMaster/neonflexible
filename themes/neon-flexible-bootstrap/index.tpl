{if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}

	{*if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
		<ul id="home-page-tabs" class="nav nav-tabs clearfix">
			{$HOOK_HOME_TAB}
		</ul>
	{/if*}

	{$HOOK_HOME_TAB_CONTENT}

{/if}

{if isset($HOOK_HOME) && $HOOK_HOME|trim}
	{$HOOK_HOME}
{/if}