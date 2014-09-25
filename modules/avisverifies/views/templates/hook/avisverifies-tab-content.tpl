
<div id="av_more_info_tabs"></div>
<div class="clear"></div>
<div id="idTabavisverifies">

	<div id="headerAV">{l s='Evaluations produits' mod='avisverifies'}</div>
	<div id="under-headerAV"  style="background: url({l s='url_image_sceau_tabcontent' mod='avisverifies'}) no-repeat #f1f1f1">
		<ul id="aggregateRatingAV">
			<li><b>
				{if $count_reviews > 1}
					{l s='Nombre d\'aviss' mod='avisverifies'}
				{else}
					{l s='Nombre d\'avis' mod='avisverifies'}
				{/if}
			</b> : {$count_reviews}</li>
			<li><b>{l s='Note moyenne' mod='avisverifies'}</b> : {$average_rate} /5 <div class="ratingWrapper" style="display:inline-block;">
    	<div class="ratingInner" style="width:{$average_rate_percent}%"></div>
    </div></li>

		</ul>
		<ul id="certificatAV">			
			<li><a href="{$url_certificat}" target="_blank" class="display_certificat_review" >{l s='Afficher le certificat de confiance' mod='avisverifies'}</a></li>
		</ul>	

		<div class="clear"></div>

	</div>		

	<div id="ajax_comment_content">

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
		
		{include file="modules/avisverifies/views/templates/pagination.tpl"}
	</div>

</div>
<div class="clear"></div>


{literal}
<script>
	//<![CDATA[
    $('#pagination_next_av a, #pagination_previous_av a, a.pagination_page_number_av').live("click", function(){
    	
        $.ajax({
            url: "{/literal}{$base_dir}{literal}modules/avisverifies/ajax-load.php",
            type: "POST",
            data: {p : $(this).attr('rel'), id_product : $('input[name="id_product"]').val(), count_reviews : {/literal}{$count_reviews}{literal}},
            beforeSend: function() {
                backup_content = $("#ajax_comment_content").html();
                
                $("#ajax_comment_content").slideUp().empty();
                $('#ajax_comment_content').append('<img src="{/literal}{$base_dir}{literal}modules/avisverifies/images/pagination-loader.gif" />').slideDown('2000');
            },
            success: function( html ){
                $("#ajax_comment_content").empty();
                $("#ajax_comment_content").append(html);
                $('html,body').animate({scrollTop: $("#ajax_comment_content").offset().top}, 'slow');
            },
            error: function ( jqXHR, textStatus, errorThrown ){
                alert('something went wrong...');
                $("#ajax_comment_content").html( backup_content );
            }
        });
        return false;
    })
	//]]>
</script>
{/literal}


