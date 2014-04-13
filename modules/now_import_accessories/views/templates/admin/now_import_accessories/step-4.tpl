{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

{if $show_toolbar}
	{include file="page_header_toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
	{include file="helpers/modules_list/modal.tpl" }
{/if}

<div class="leadin">{block name="leadin"}{/block}</div>

{include file="$module_path/steps.tpl" selected=$current_step pagination=$pagination}

<div class="now_import_accessories">
	<table cellpadding="0" cellspacing="0" border="0" class="bordered-table zebra-striped" id="table-bootsrap">
		<thead>
			<tr>
				{foreach $aDatas.0 as  $sKey => $sElement}
					<td>{$sElement}</td>
				{/foreach}
			</tr>
		</thead>

		<tbody>
			{foreach $aDatas as $iKey => $aRow}
				{if $iKey != 0}
					<tr>
						{foreach $aRow as $sKey => $sElement}
							<td class="{if $sKey == 'error' && !empty($sElement)}error{elseif $sKey != 'error'}{$sKey}{/if} center">
								{if $sKey == 'action_type'}
									<img src="../modules/now_import_accessories/img/{$sElement}.png" alt="{$sElement|escape:'htmlall':'UTF-8'}" />
								{else}
									{$sElement}
								{/if}
							</td>
						{/foreach}
					</tr>
				{/if}
			{/foreach}
		</tbody>
	</table>
</div>