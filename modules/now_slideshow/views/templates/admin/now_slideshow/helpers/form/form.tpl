
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
