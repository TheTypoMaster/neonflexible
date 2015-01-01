{* Assign a value to 'current_step' to display current style *}
{capture name="url_back"}
	{if isset($back) && $back}back={$back}{/if}
{/capture}

{if !isset($multi_shipping)}
	{assign var='multi_shipping' value='0'}
{/if}


{if !$opc}
	<div id="order_step">
		<div class="container">
			<ul class="step">
				<li class="{if $current_step == 'summary'}step_current{else}{if $current_step == 'payment' || $current_step == 'shipping' || $current_step == 'address' || $current_step == 'login'}step_done{else}step_todo{/if}{/if}">
					{if $current_step == 'payment' || $current_step == 'shipping' || $current_step == 'address' || $current_step == 'login'}
						<a href="{$link->getPageLink('order', true)}">
							<span class="step-number">1</span>
							{l s='Summary'}
						</a>
					{else}
						<span class="step-number">1</span>
						<span>{l s='Summary'}</span>
					{/if}
				</li>
				<li class="{if $current_step == 'login'}step_current{else}{if $current_step == 'payment' || $current_step == 'shipping' || $current_step == 'address'}step_done{else}step_todo{/if}{/if}">
					{if $current_step == 'payment' || $current_step == 'shipping' || $current_step == 'address'}
						<a href="{$link->getPageLink('order', true, NULL, "{$smarty.capture.url_back}&step=1&multi-shipping={$multi_shipping}")|escape:'html'}">
							<span class="step-number">2</span>
							{l s='Login'}
						</a>
					{else}
						<span class="step-number">2</span>
						<span>{l s='Login'}</span>
					{/if}
				</li>
				<li class="{if $current_step == 'address'}step_current{else}{if $current_step == 'payment' || $current_step == 'shipping'}step_done{else}step_todo{/if}{/if}">
					{if $current_step == 'payment' || $current_step == 'shipping'}
						<a href="{$link->getPageLink('order', true, NULL, "{$smarty.capture.url_back}&step=1&multi-shipping={$multi_shipping}")|escape:'html'}">
							<span class="step-number">3</span>
							{l s='Address'}
						</a>
					{else}
						<span class="step-number">3</span>
						<span>{l s='Address'}</span>
					{/if}
				</li>
				<li class="{if $current_step == 'shipping'}step_current{else}{if $current_step == 'payment'}step_done{else}step_todo{/if}{/if}">
					{if $current_step == 'payment'}
						<a href="{$link->getPageLink('order', true, NULL, "{$smarty.capture.url_back}&step=2&multi-shipping={$multi_shipping}")|escape:'html'}">
							<span class="step-number">4</span>
							{l s='Shipping'}
						</a>
					{else}
						<span class="step-number">4</span>
						<span>{l s='Shipping'}</span>
					{/if}
				</li>
				<li id="step_end" class="{if $current_step == 'payment'}step_current{else}step_todo{/if}">
					<span class="step-number">5</span>
					<span>{l s='Payment'}</span>
				</li>
			</ul>
		</div>
	</div>
{/if}