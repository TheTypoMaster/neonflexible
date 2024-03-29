{* Two variable are necessaries to display the address with the new layout system *}
{* Will be deleted for 1.5 version and more *}
{if !isset($multipleAddresses)}
	{$ignoreList.0 = "id_address"}
	{$ignoreList.1 = "id_country"}
	{$ignoreList.2 = "id_state"}
	{$ignoreList.3 = "id_customer"}
	{$ignoreList.4 = "id_manufacturer"}
	{$ignoreList.5 = "id_supplier"}
	{$ignoreList.6 = "date_add"}
	{$ignoreList.7 = "date_upd"}
	{$ignoreList.8 = "active"}
	{$ignoreList.9 = "deleted"}

	{* PrestaShop < 1.4.2 compatibility *}
	{if isset($addresses)}
		{$address_number = 0}
		{foreach from=$addresses key=k item=address}
			{counter start=0 skip=1 assign=address_key_number}
			{foreach from=$address key=address_key item=address_content}
				{if !in_array($address_key, $ignoreList)}
					{$multipleAddresses.$address_number.ordered.$address_key_number = $address_key}
					{$multipleAddresses.$address_number.formated.$address_key = $address_content}
					{counter}
				{/if}
			{/foreach}
		{$multipleAddresses.$address_number.object = $address}
		{$address_number = $address_number  + 1}
		{/foreach}
	{/if}
{/if}

{* Define the style if it doesn't exist in the PrestaShop version*}
{* Will be deleted for 1.5 version and more *}
{if !isset($addresses_style)}
	{$addresses_style.company = 'address_company'}
	{$addresses_style.vat_number = 'address_company'}
	{$addresses_style.firstname = 'address_name'}
	{$addresses_style.lastname = 'address_name'}
	{$addresses_style.address1 = 'address_address1'}
	{$addresses_style.address2 = 'address_address2'}
	{$addresses_style.city = 'address_city'}
	{$addresses_style.country = 'address_country'}
	{$addresses_style.phone = 'address_phone'}
	{$addresses_style.phone_mobile = 'address_phone_mobile'}
	{$addresses_style.alias = 'address_title'}
{/if}

<script type="text/javascript">
//<![CDATA[
	{literal}
	$(document).ready(function()
	{
			resizeAddressesBox();
	});
	{/literal}
//]]>
</script>

{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My addresses'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<div class="container">
	<h1 class="titre-size-1">{l s='My addresses'}</h1>
	<p>{l s='Please configure your default billing and delivery addresses when placing an order. You may also add additional addresses, which can be useful for sending gifts or receiving an order at your office.'}</p>

	{if isset($multipleAddresses) && $multipleAddresses}
		<div class="addresses">
			<h3>{l s='Your addresses are listed below.'}</h3>
			<p>{l s='Be sure to update your personal information if it has changed.'}</p>
			{assign var="adrs_style" value=$addresses_style}
			<div class="bloc_adresses clearfix">
				{foreach from=$multipleAddresses item=address name=myLoop}
					<ul class="block {if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{else}item{/if}">
						<li class="title">{$address.object.alias}</li>
						{foreach from=$address.ordered name=adr_loop item=pattern}
							{assign var=addressKey value=" "|explode:$pattern}
							<li>
								{foreach from=$addressKey item=key name="word_loop"}
									<span{if isset($addresses_style[$key])} class="{$addresses_style[$key]}"{/if}>
						{$address.formated[$key|replace:',':'']|escape:'htmlall':'UTF-8'}
					</span>
								{/foreach}
							</li>
						{/foreach}
						<li class="address_update">

							<a href="{$link->getPageLink('address', true, null, "id_address={$address.object.id|intval}")|escape:'html'}" title="{l s='Update'}" class="button-exclusive btn btn-default">
								{l s='Update'}<i class="icon-chevron-right"></i>
							</a>

						</li>
						<li class="address_delete">

							<a href="{$link->getPageLink('address', true, null, "id_address={$address.object.id|intval}&delete")|escape:'html'}" onclick="return confirm('{l s='Are you sure?' js=1}');" title="{l s='Delete'}" class="button-exclusive btn btn-default">
								{l s='Delete'}<i class="icon-chevron-right"></i>
							</a>

						</li>
					</ul>
				{/foreach}
			</div>
			<p class="clear" />
		</div>
	{else}
		<p class="warning">{l s='No addresses are available.'}&nbsp;<a href="{$link->getPageLink('address', true)|escape:'html'}">{l s='Add a new address'}</a></p>
	{/if}

	<div class="clear address_add">

		<a href="{$link->getPageLink('address', true)|escape:'html'}" title="{l s='Add an address'}" class="button btn btn-default standard-checkout button-medium">
			<span>
				{l s='Add an address'}
				<i class="icon-chevron-right"></i>
			</span>
		</a>

	</div>
</div>