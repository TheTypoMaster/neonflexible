{if isset($features) && $features}
	<div class="product-tab" id="features">
		<h2>{l s='Caractéristiques'}</h2>
		<table class="std">
			{foreach $features as $key => $feature}
				{if isset($feature.value)}
					<tr {if $key%2}class="row-surligne"{/if}>
						<td class="bold">{$feature.name|escape:'htmlall':'UTF-8'}</td>
						<td>{$feature.value|escape:'htmlall':'UTF-8'}</td>
					</tr>
				{/if}
			{/foreach}
		</table>
	</div>
{/if}