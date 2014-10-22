{if $aItems && count($aItems) > 0}
	<div id="references-clients">

		<div class="container">

			<div class="carousel-customer-references">

				{foreach $aItems as $key => $aItem}
					{if $aItem['active']}
						<div class="item{if $smarty.foreach.foo.first} active{/if}">
							<img src="{$module_dir}{$aItem['file_name']}" alt="{$aItem['name']}" />
							<p>{$aItem['description']}</p>
							{*if $aItem['link']}
								<a href="{$aItem['link']}" target="_blank">{l s='En savoir plus'}</a>
							{/if*}
						</div>
					{/if}
				{/foreach}

			</div>

			<span class="clearBoth"></span>

			<a href="{Context::getContext()->link->getCMSCategoryLink(Configuration::get('NOW_CUST_REFERENCE_CAT_CMS'))}" class="button-plus-references" title="{l s='Voir plus de références' mod='now_block_customer_references'}">
				{l s='Voir plus de références' mod='now_block_customer_references'}
			</a>

		</div>

	</div>
{/if}