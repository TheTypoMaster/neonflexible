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

<div class="now_product_type">
	<form id="formData" action="{Context::getContext()->link->getAdminLink('AdminNowImportProductType', true)}" method="post">
		<table cellpadding="0" cellspacing="0" border="0" class="bordered-table zebra-striped" id="table-bootsrap">
			<thead>
				<tr>
					<th>{l s='Ignore lines' mod='now_product_type'}</th>
					{foreach $aDatas.0 as $iKey => $sElement}
						<th>
							<select name="columns[{$iKey}]">
								{foreach $aColumns as $sColumnKey => $sColumnName}
									<option value="{$sColumnKey}">{$sColumnName}</option>
								{/foreach}
							</select>
						</th>
					{/foreach}
				</tr>
			</thead>

			<tbody>
				{foreach $aDatas as $iKey => $aRow}
					<tr>
						<td><input type="checkbox" name="lines[{$iKey}]" value="1"/> </td>
						{foreach $aRow as $sElement}
							<td>{$sElement}</td>
						{/foreach}
					</tr>
				{/foreach}
			</tbody>
		</table>
		<input type="hidden" name="file_path" value="{$file_path}" />
		<input type="hidden" name="action" value="_third_Step" />
	</form>
</div>