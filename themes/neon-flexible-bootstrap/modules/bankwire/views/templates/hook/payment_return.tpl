<div class="container">
	{if $status == 'ok'}
		<p class="alert alert-success">{l s='Your order is complete.' sprintf=$shop_name mod='bankwire'}</p>
		<div class="box order-confirmation">
			<h3 class="page-subheading">{l s='Please send us a bank wire with' mod='bankwire'}</h3>
			- {l s='Amount:' mod='bankwire'} <span class="price"><strong>{$total_to_pay}</strong></span>
			<br />- {l s='name of account owner:' mod='bankwire'} <strong>{if $bankwireOwner}{$bankwireOwner}{else}___________{/if}</strong>
			<br />- {l s='include these details:' mod='bankwire'} <br /><strong>{if $bankwireDetails}{$bankwireDetails}{else}___________{/if}</strong>
			<br />- {l s='bank name:' mod='bankwire'} <br /><strong>{if $bankwireAddress}{$bankwireAddress}{else}___________{/if}</strong>
			{if !isset($reference)}
				<br />- {l s='Do not forget to insert your order number #%d in the subject of your bank wire' sprintf=$id_order mod='bankwire'}
			{else}
				<br />- {l s='Do not forget to insert your order reference %s in the subject of your bank wire.' sprintf=$reference mod='bankwire'}
			{/if}		<br />{l s='An email has been sent with this information.' mod='bankwire'}
			<br /> <strong>{l s='Your order will be sent as soon as we receive payment.' mod='bankwire'}</strong>
			<br />{l s='If you have questions, comments or concerns, please contact our' mod='bankwire'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team. ' mod='bankwire'}</a>.
		</div>
	{else}
		<p class="alert alert-warning">
			{l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='bankwire'}
			<a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team. ' mod='bankwire'}</a>.
		</p>
	{/if}

</div>
