
{capture name=path}
	{l s='Bank-wire payment.' mod='bankwire'}
{/capture}

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}


<div class="container">

	{if $nbProducts <= 0}
		<p class="alert alert-warning">
			{l s='Your shopping cart is empty.' mod='bankwire'}
		</p>
	{else}
		<form action="{$link->getModuleLink('bankwire', 'validation', [], true)|escape:'html':'UTF-8'}" method="post">
			<div class="box cheque-box">
				<h3 class="page-subheading">
					{l s='Bank-wire payment.' mod='bankwire'}
				</h3>
				<p class="cheque-indent">
					<strong class="dark">
						{l s='You have chosen to pay by bank wire.' mod='bankwire'} {l s='Here is a short summary of your order:' mod='bankwire'}
					</strong>
				</p>
				<p>
					- {l s='The total amount of your order is' mod='bankwire'}
					<span id="amount" class="price">{displayPrice price=$total}</span>
					{if $use_taxes == 1}
						{l s='(tax incl.)' mod='bankwire'}
					{/if}
				</p>
				<p>
					-
					{if $currencies|@count > 1}
					{l s='We allow several currencies to be sent via bank wire.' mod='bankwire'}
				<div class="form-group">
					<label>{l s='Choose one of the following:' mod='bankwire'}</label>
					<select id="currency_payement" class="form-control" name="currency_payement">
						{foreach from=$currencies item=currency}
							<option value="{$currency.id_currency}" {if $currency.id_currency == $cust_currency}selected="selected"{/if}>
								{$currency.name}
							</option>
						{/foreach}
					</select>
				</div>
				{else}
				{l s='We allow the following currency to be sent via bank wire:' mod='bankwire'}&nbsp;<b>{$currencies.0.name}</b>
				<input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}" />
				{/if}
				</p>
				<p>
					- {l s='Bank wire account information will be displayed on the next page.' mod='bankwire'}
					<br />
					- {l s='Please confirm your order by clicking "I confirm my order."' mod='bankwire'}.
				</p>
			</div><!-- .cheque-box -->

			<p class="cart_navigation clearfix" id="cart_navigation">
				<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" class="button-exclusive btn btn-default">
					<i class="icon-chevron-left"></i>{l s='Other payment methods' mod='bankwire'}
				</a>
				<button type="submit" class="button btn btn-default button-medium">
					<span>{l s='I confirm my order' mod='bankwire'}<i class="icon-chevron-right"></i></span>
				</button>
			</p>
		</form>
	{/if}

</div>