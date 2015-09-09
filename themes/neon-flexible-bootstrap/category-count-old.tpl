<div class="nb-products-results">
	{if ($n*$p) < $nb_products }
		{assign var='productShowing' value=$n*$p}
	{else}
		{assign var='productShowing' value=($n*$p-$nb_products-$n*$p)*-1}
	{/if}
	{if $p==1}
		{assign var='productShowingStart' value=1}
	{else}
		{assign var='productShowingStart' value=$n*$p-$n+1}
	{/if}

	<p>
		{if $nb_products > 1}
			{l s='Showing %1$d - %2$d of %3$d items' sprintf=[$productShowingStart, $productShowing, $nb_products]}
		{else}
			{l s='Showing %1$d - %2$d of 1 item' sprintf=[$productShowingStart, $productShowing]}
		{/if}
	</p>
</div>