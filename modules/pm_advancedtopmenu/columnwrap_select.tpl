<select name="id_wrap">
	{if isset($columnsWrap) && is_array($columnsWrap) && sizeof($columnsWrap) > 1}<option>{l s='Choose' mod='pm_advancedtopmenu'}</option>{/if}
	{foreach from=$columnsWrap item=columnWrap name=loop}
		<option value="{$columnWrap.id_wrap|intval}" {if $columnWrap_selected eq $columnWrap.id_wrap}selected=selected{/if}>{$columnWrap.internal_name}</option>
	{foreachelse}
		<option value="">{l s='No column' mod='pm_advancedtopmenu'}</option>
	{/foreach}
</select>