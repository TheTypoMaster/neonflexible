<div class="nb-products-results">
	{assign var=end value=($p*$n)}
	{assign var=start value=($end-$n)}

	{if $end > $nb_products}
		{assign var=end value=$nb_products}
	{/if}

	<p>{l s='Résultats %1$d - %2$d sur %3$d' sprintf=[$start, $end, $nb_products]}</p>
</div>