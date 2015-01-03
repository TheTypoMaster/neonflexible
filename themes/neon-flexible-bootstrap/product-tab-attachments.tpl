{if isset($attachments) && $attachments}
	<div class="product-tab" id="download">
		<h2>{l s='Download'}</h2>
		<ul class="bullet">
			{foreach from=$attachments item=attachment}
				<li><a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html'}">{$attachment.name|escape:'htmlall':'UTF-8'}</a><br />{$attachment.description|escape:'htmlall':'UTF-8'}</li>
			{/foreach}
		</ul>
	</div>
{/if}