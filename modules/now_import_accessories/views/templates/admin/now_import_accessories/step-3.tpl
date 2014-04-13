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

{include file="$module_path/steps.tpl" selected=$current_step pagination=$pagination file_name=$file_name}

<div class="now_import_accessories">
	<form id="formData" action="{Context::getContext()->link->getAdminLink('AdminNowImportAccessories', true)}" method="post">
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
									{$sElement}
								</td>
							{/foreach}
						</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
		<input type="hidden" name="file_path" value="{$file_path}" />
		<input type="hidden" name="action" value="_fourth_Step" />

		{foreach $aColumns as $sKey => $sValue}
			<input type="hidden" name="aColumns[{$sKey}]" value="{$sValue}" />
		{/foreach}

		{foreach $aLines as $sKey => $sValue}
			<input type="hidden" name="aLines[{$sKey}]" value="{$sValue}" />
		{/foreach}
	</form>
</div>