
{extends file="helpers/form/form.tpl"}

{block name="input"}
	{if $input.type == 'select_category'}
		<div class="col-lg-9">
			<div class="row">
				<select name="id_parent">
					{$input.options.html}
				</select>
			</div>
		</div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{* Override du btn "Cancel" *}
{block name="footer"}
	{if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
		<div class="panel-footer">
			{if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
				<button
						type="submit"
						value="1"
						id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}"
						name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}"
						class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-default pull-right{/if}"
						>
					<i class="{if isset($fieldset['form']['submit']['icon'])}{$fieldset['form']['submit']['icon']}{else}process-icon-save{/if}"></i> {$fieldset['form']['submit']['title']}
				</button>
			{/if}
			{if isset($show_cancel_button) && $show_cancel_button}
				<a href="{$back_url_override}" class="btn btn-default" onclick="window.history.back()">
					<i class="process-icon-cancel"></i> {l s='Cancel'}
				</a>
			{/if}
			{if isset($fieldset['form']['reset'])}
				<button
						type="reset"
						id="{if isset($fieldset['form']['reset']['id'])}{$fieldset['form']['reset']['id']}{else}{$table}_form_reset_btn{/if}"
						class="{if isset($fieldset['form']['reset']['class'])}{$fieldset['form']['reset']['class']}{else}btn btn-default{/if}"
						>
					{if isset($fieldset['form']['reset']['icon'])}<i class="{$fieldset['form']['reset']['icon']}"></i> {/if} {$fieldset['form']['reset']['title']}
				</button>
			{/if}
			{if isset($fieldset['form']['buttons'])}
				{foreach from=$fieldset['form']['buttons'] item=btn key=k}
					{if isset($btn.href) && trim($btn.href) != ''}
						<a href="{$btn.href}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</a>
					{else}
						<button type="{if isset($btn['type'])}{$btn['type']}{else}button{/if}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" name="{if isset($btn['name'])}{$btn['name']}{else}submitOptions{$table}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</button>
					{/if}
				{/foreach}
			{/if}
		</div>
	{/if}
{/block}

{block name=script}
	$('.idTypeDiv').hide();
	$('.cmsDiv').hide();
	$('.linkDiv').hide();
	$('.manufacturerDiv').hide();
	$('.categoryDiv').hide();

	if ($('select[name="type"]').val() == 'link') {
		$('.linkDiv').show();
	} else if ($('select[name="type"]').val() == 'category') {
		$('.categoryDiv').show();
	} else if ($('select[name="type"]').val() == 'manufacturer') {
		$('.manufacturerDiv').show();
	} else {
		$('.cmsDiv').show();
	}

	$('select[name="type"]').change(function() {
		$('.idTypeDiv').hide();
		$('.cmsDiv').hide();
		$('.linkDiv').hide();
		$('.manufacturerDiv').hide();
		$('.categoryDiv').hide();

		if ($(this).val() == 'link') {
			$('.linkDiv').show();
		} else if ($(this).val() == 'category') {
			$('.categoryDiv').show();
		} else if ($(this).val() == 'manufacturer') {
			$('.manufacturerDiv').show();
		} else {
			$('.cmsDiv').show();
		}
	});

	$('select[name="cms"], select[name="manufacturer"], input[name="category"]').change(function() {
		console.log('on change par la valeur : ', $(this).val());
		$('input[name="id_type"').val($(this).val());
	});

{/block}
