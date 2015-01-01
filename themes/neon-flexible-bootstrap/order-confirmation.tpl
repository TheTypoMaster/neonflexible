
{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<div class="container">

	<h1 class="titre-size-1">{l s='Order confirmation'}</h1>

	{include file="$tpl_dir./errors.tpl"}

	{$HOOK_ORDER_CONFIRMATION}
	{$HOOK_PAYMENT_RETURN}
	{if $is_guest}
		<p>{l s='Your order ID is:'} <span class="bold">{$id_order_formatted}</span> . {l s='Your order ID has been sent via email.'}</p>
		<p class="cart_navigation exclusive">
			<a class="button-exclusive btn btn-default" href="{$link->getPageLink('guest-tracking', true, NULL, "id_order={$reference_order}&email={$email}")|escape:'html':'UTF-8'}" title="{l s='Follow my order'}"><i class="icon-chevron-left"></i>{l s='Follow my order'}</a>
		</p>
	{else}
		<p class="cart_navigation exclusive">
			<a class="button-exclusive btn btn-default" href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Back to orders'}"><i class="icon-chevron-left"></i>{l s='Back to orders'}</a>
		</p>
	{/if}
</div>