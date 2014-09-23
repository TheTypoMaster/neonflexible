{if $errors|@count == 0}

	{include file="$tpl_dir./product-script.tpl"}

	{include file="$tpl_dir./breadcrumb.tpl"}

	<div class="container">

		{include file="$tpl_dir./product-admin.tpl"}

		{if isset($confirmation) && $confirmation}
			<p class="confirmation">
				{$confirmation}
			</p>
		{/if}

		{include file="$tpl_dir./errors.tpl"}

		{include file="$tpl_dir./product-top-left.tpl"}
		{include file="$tpl_dir./product-top-center.tpl"}
		{include file="$tpl_dir./product-top-right.tpl"}

	</div>
{/if}