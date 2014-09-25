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

		<div id="product-left">
			{include file="$tpl_dir./product-top-left.tpl"}
			{include file="$tpl_dir./product-top-center.tpl"}

			{if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}

			<span class="clearBoth"></span>

			{include file="$tpl_dir./product-tab.tpl"}
			{include file="$tpl_dir./product-tab-description.tpl"}
			{include file="$tpl_dir./product-tab-caracteristiques.tpl"}
			{include file="$tpl_dir./product-tab-attachments.tpl"}
		</div>

		<div id="product-right">
			{include file="$tpl_dir./product-top-right.tpl"}
		</div>

		<span class="clearBoth"></span>

		{include file="$tpl_dir./product-tab-accessoires.tpl"}
		{if isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT}{$HOOK_PRODUCT_TAB_CONTENT}{/if}

	</div>
{/if}