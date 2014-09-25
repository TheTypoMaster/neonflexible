{capture name=path}{l s='My account'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<div class="my-account">
	<div class="container">
		<h1>{l s='My account'}</h1>

		{if isset($account_created)}
			<p class="success">
				{l s='Your account has been created.'}
			</p>
		{/if}

		<ul class="myaccount_lnk_list">
			<li>
				<a href="{$link->getPageLink('identity', true)|escape:'html'}" title="{l s='Information'}">
					<img src="{$img_dir}theme/my-account/info-perso.png" alt="{l s='Information'}" class="icon" />
					{l s='My personal information'}
				</a>
			</li>

			{if $has_customer_an_address}
				<li>
					<a href="{$link->getPageLink('address', true)|escape:'html'}" title="{l s='Add my first address'}">
						<img src="{$img_dir}theme/my-account/mes-addresses.png" alt="{l s='Add my first address'}" class="icon" />
						{l s='Add my first address'}
					</a>
				</li>
			{else}
				<li>
					<a href="{$link->getPageLink('addresses', true)|escape:'html'}" title="{l s='Addresses'}">
						<img src="{$img_dir}theme/my-account/mes-addresses.png" alt="{l s='Addresses'}" class="icon" />
						{l s='My addresses'}
					</a>
				</li>
			{/if}

			<li>
				<a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='Orders'}">
					<img src="{$img_dir}theme/my-account/historique-commande.png" alt="{l s='Orders'}" class="icon" />
					{l s='Order history and details '}
				</a>
			</li>

			{if $voucherAllowed}
				<li>
					<a href="{$link->getPageLink('discount', true)|escape:'html'}" title="{l s='Vouchers'}">
						<img src="{$img_dir}theme/my-account/bon-reduction.png" alt="{l s='Vouchers'}" class="icon" />
						{l s='My vouchers'}
					</a>
				</li>
			{/if}

			{if $returnAllowed}
				<li>
					<a href="{$link->getPageLink('order-follow', true)|escape:'html'}" title="{l s='Merchandise returns'}">
						<img src="{$img_dir}theme/my-account/retours.png" alt="{l s='Merchandise returns'}" class="icon" />
						{l s='My merchandise returns'}
					</a>
				</li>
			{/if}

			<li>
				<a href="{$link->getPageLink('order-slip', true)|escape:'html'}" title="{l s='Credit slips'}">
					<img src="{$img_dir}theme/my-account/avoir.png" alt="{l s='Credit slips'}" class="icon" />
					{l s='My credit slips'}
				</a>
			</li>


			{$HOOK_CUSTOMER_ACCOUNT}

		</ul>
	</div>
</div>