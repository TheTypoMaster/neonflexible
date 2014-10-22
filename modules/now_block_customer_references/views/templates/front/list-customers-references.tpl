

<div class="container">

	<p class="titre-size-1">{l s='Nos Références clients'}</p>

	<ul class="list-customer-reference">
		{foreach $nowBlockCustomerReferencesList as $nowBlockCustomerReferences}
			<li>
				<img src="{$module_dir}{$nowBlockCustomerReferences['file_name']}" alt="{$nowBlockCustomerReferences['name']}" />

				<div>
					<p>{$nowBlockCustomerReferences['name']}</p>
					<p>{$nowBlockCustomerReferences['description']}</p>
					{if $nowBlockCustomerReferences['link']}
						<a href="{$nowBlockCustomerReferences['link']}" target="_blank">{$nowBlockCustomerReferences['link']}</a>
					{/if}
				</div>

				<span class="clearBoth"></span>
			</li>
		{/foreach}
	</ul>
</div>