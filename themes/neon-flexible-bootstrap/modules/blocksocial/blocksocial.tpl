<div id="social_block">

	<hr>

	<p class="follow-us">{l s='Suivez-nous sur :'}</p>
	<ul>
		{if $facebook_url != ''}
			<li class="facebook">
				<a class="_blank" href="{$facebook_url|escape:html:'UTF-8'}">{l s='Facebook' mod='blocksocial'}</a>
			</li>
		{/if}
		{if $twitter_url != ''}
			<li class="twitter">
				<a class="_blank" href="{$twitter_url|escape:html:'UTF-8'}">{l s='Twitter' mod='blocksocial'}</a>
			</li>
		{/if}
		{if $google_plus_url != ''}
			<li class="google_plus">
				<a class="_blank" href="{$google_plus_url|escape:html:'UTF-8'}">{l s='Google+' mod='blocksocial'}</a>
			</li>
		{/if}
		{if $rss_url != ''}
			<li class="rss">
				<a class="_blank" href="{$rss_url|escape:html:'UTF-8'}">{l s='RSS' mod='blocksocial'}</a>
			</li>
		{/if}
		{if $youtube_url != ''}
			<li class="youtube">
				<a class="_blank" href="{$youtube_url|escape:html:'UTF-8'}">{l s='YouTube' mod='blocksocial'}</a>
			</li>
		{/if}
		{if $pinterest_url != ''}
			<li class="pinterest">
				<a class="_blank" href="{$pinterest_url|escape:html:'UTF-8'}">{l s='Pinterest' mod='blocksocial'}</a>
			</li>
		{/if}
		{if $vimeo_url != ''}
			<li class="vimeo">
				<a href="{$vimeo_url|escape:html:'UTF-8'}">{l s='Vimeo' mod='blocksocial'}</a>
			</li>
		{/if}
	</ul>
</div>