{assign var=cms value=CMS::getCmsObjectById(Configuration::get('NOW_CUST_REFERENCE_CMS_ID'))}
{capture name=path}
	{$cms->meta_title}
{/capture}
{include file="$tpl_dir./cms.tpl" cms=$cms noDescription=true noBorder=true}

<div class="container">

	<ul class="list-customer-reference">
		{foreach $nowBlockCustomerReferencesList as $oNowBlockCustomerReferences}
			<li>
				<img src="{$oNowBlockCustomerReferences->getImageLink()}" alt="{$oNowBlockCustomerReferences->name}" />

				<div>
					<h2>{$oNowBlockCustomerReferences->name}</h2>
					<p>{$oNowBlockCustomerReferences->description}</p>
					{if $oNowBlockCustomerReferences->link}
						<a href="{$oNowBlockCustomerReferences->link}" target="_blank">{$oNowBlockCustomerReferences->link}</a>
					{/if}
				</div>

				<span class="clearBoth"></span>
			</li>
		{/foreach}
	</ul>
</div>