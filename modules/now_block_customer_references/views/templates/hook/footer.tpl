{if $aItems && count($aItems) > 0}
	<div id="references-clients">

		<div class="container">

			<div class="carousel-customer-references">

				{foreach $aItems as $key => $oNowBlockCustomerReferences}
					{if $oNowBlockCustomerReferences->active}
						<div class="item{if $smarty.foreach.foo.first} active{/if}">
							<img src="{$oNowBlockCustomerReferences->getImageLink()}" alt="{$oNowBlockCustomerReferences->name}" />
							<p>{$oNowBlockCustomerReferences->description}</p>
							{*if oNowBlockCustomerReferences->link}
								<a href="{$oNowBlockCustomerReferences->link}" target="_blank">{l s='En savoir plus'}</a>
							{/if*}
						</div>
					{/if}
				{/foreach}

			</div>

			<span class="clearBoth"></span>

			<a href="{Context::getContext()->link->getModuleLink('now_block_customer_references')}" class="button-plus-references" title="{l s='Voir plus de références' mod='now_block_customer_references'}">
				{l s='Voir plus de références' mod='now_block_customer_references'}
			</a>

		</div>

	</div>
{/if}