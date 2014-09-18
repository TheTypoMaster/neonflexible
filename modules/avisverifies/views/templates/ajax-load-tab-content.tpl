{foreach from=$reviews key=k_review item=review}	

	<div class="reviewAV">

		<ul class="reviewInfosAV">
			<li style="text-transform:capitalize">{$review['customer_name']}<!--{$review['id_product_av']}--></li>
			<li>&nbsp;{l s='le' mod='avisverifies'} {$review['horodate']}</li>
			<li class="rateAV"><img src="{$modules_dir}avisverifies/images/etoile{$review['rate']}.png" width="80" height="15" /> {$review['rate']}/5</li>
		</ul>	

		<div class="triangle-border top">{$review['avis']}</div>

	{if $review['discussion']}
		{foreach from=$review['discussion'] key=k_discussion item=discussion}

		<div class="triangle-border top answer" {if $k_discussion > 0} review_number={$review['id_product_av']} style= "display: none" {/if}>

			<span>&rsaquo; {l s='Commentaire de' mod='avisverifies'}  <b style="text-transform:capitalize; font-weight:normal">{$discussion['origine']}</b> {l s='le' mod='avisverifies'} {$discussion['horodate']}</span>
			<p class="answer-bodyAV">{$discussion['commentaire']}</p>


		</div>
		
			
		{/foreach}

		{if $k_discussion > 0}
			<a href="javascript:switchCommentsVisibility('{$review['id_product_av']}')" style="padding-left: 6px;margin-left: 30px; display: block; font-style:italic" id="display{$review['id_product_av']}" class="display-all-comments" review_number={$review['id_product_av']} >{l s='Afficher les échanges' mod='avisverifies'}</a>

			<a href="javascript:switchCommentsVisibility('{$review['id_product_av']}')" style="padding-left: 6px;margin-left: 30px; display: none; font-style:italic" id="hide{$review['id_product_av']}" class="display-all-comments" review_number={$review['id_product_av']} >{l s='Masquer les échanges' mod='avisverifies'}</a>
			</a>
	  	{/if}
	{/if}

	</div>
{/foreach}
		{include file="views/templates/pagination.tpl"}







