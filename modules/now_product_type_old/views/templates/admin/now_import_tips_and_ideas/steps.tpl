{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

<script type="text/javascript">
	// defines text language for dataTable javscript tool
	var lang_sLengthMenu            = "{l s='Display %s records' sprintf='_MENU_' mod='now_product_type' js=1}";
	var lang_sEmptyTable            = "{l s='No data available in table' mod='now_product_type' js=1}";
	var lang_sLoadingRecords        = "{l s='Please wait - loading...' mod='now_product_type' js=1}";
	var lang_sInfo                  = "{l s='Got a total of %1$s entries to show (%2$s to %3$s)' sprintf=['_TOTAL_', '_START_', '_END_'] mod='now_product_type' js=1}";
	var lang_oPaginate_sNext        = "{l s='Next page' mod='now_product_type' js=1}";
	var lang_oPaginate_sPrevious    = "{l s='Previous page' mod='now_product_type' js=1}";
	var pagination                  = "{$pagination}";
	var admin_import_stock_link     = "{Context::getContext()->link->getAdminLink('AdminNowImportTipsAndIdeas', true)}";
</script>

<div id="steps">
	<div {if $selected == 1}class="selected"{/if}>
		{if $selected > 1}
			<a href="{Context::getContext()->link->getAdminLink('AdminNowImportTipsAndIdeas', true)}&step=1">
				<span class="step_number green">01</span>
				<h3>{l s='Select' mod='now_product_type'}</h3>
			</a>
		{elseif $selected == 1}
			<span class="step_number green">01</span>
			<h3>{l s='Select your file' mod='now_product_type'}</h3>
		{else}
			<span class="step_number green">01</span>
			<h3>{l s='Select' mod='now_product_type'}</h3>
		{/if}
	</div>

	<div {if $selected == 2}class="selected"{/if}>
		{if $selected > 2}
			<a href="{Context::getContext()->link->getAdminLink('AdminNowImportTipsAndIdeas', true)}&step=2&file_name={$file_name}">
				<span class="step_number blue">02</span>
				<h3>{l s='Choice' mod='now_product_type'}</h3>
			</a>
		{elseif $selected == 2}
			<span class="step_number blue">02</span>
			<h3>{l s='Choice data to import' mod='now_product_type'}</h3>
		{else}
			<span class="step_number blue">02</span>
			<h3>{l s='Choice' mod='now_product_type'}</h3>
		{/if}
	</div>

	<div {if $selected == 3}class="selected"{/if}>
		{if $selected > 3}
			<span class="step_number red">03</span>
			<h3>{l s='Analyse' mod='now_product_type'}</h3>
		{elseif $selected == 3}
			<span class="step_number red">03</span>
			<h3>{l s='Analyse of your data' mod='now_product_type'}</h3>
		{else}
			<span class="step_number red">03</span>
			<h3>{l s='Analyse' mod='now_product_type'}</h3>
		{/if}
	</div>

	<div {if $selected == 4}class="selected"{/if}>
		{if $selected > 4}
			<span class="step_number orange">04</span>
			<h3>{l s='Import' mod='now_product_type'}</h3>
		{elseif $selected == 4}
			<span class="step_number orange">04</span>
			<h3>{l s='Import of your data' mod='now_product_type'}</h3>
		{else}
			<span class="step_number orange">04</span>
			<h3>{l s='Import' mod='now_product_type'}</h3>
		{/if}
	</div>
</div>
<div class="clear"></div>