{assign var=cms value=CMS::getCmsObjectById(Configuration::get('NOW_CUST_REFERENCE_CMS_ID'))}
{capture name=path}
	{$cms->meta_title}
{/capture}
{include file="$tpl_dir./cms.tpl" cms=$cms noDescription=true noBorder=true}

<div class="container">

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