<div id="av_product_award">

<div id="top">
	<div class="ratingWrapper">
    	<div class="ratingInner" style="width:{$av_rate * 20}%;"></div>
    </div>
	<b>{$av_nb_reviews} &nbsp;

	{if $av_nb_reviews > 1}
		{l s='aviss produit' mod='avisverifies'}
	{else}
		{l s='avis produit' mod='avisverifies'}
	{/if}

	</b>
</div>
<div id="bottom"><a href="javascript:()" id="AV_button">{l s='Voir les avis' mod='avisverifies'}</a></div>
	<img id="sceau" src="{l s='url_image_sceau_extraright' mod='avisverifies'}" />
</div>




	