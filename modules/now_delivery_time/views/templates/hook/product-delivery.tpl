{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

{if is_array($aDeliveryTimeList) && count($aDeliveryTimeList)}

	<div id="nowDeliveryTime">

		<ul>

			{foreach $aDeliveryTimeList as $aDeliveryTime}

				<li>

					<div {if $aDeliveryTime['timeslot']}title="{l s='Timeslot: %s' sprintf=$aDeliveryTime['timeslot'] mod='now_delivery_time'}"{/if}>

						{if $aDeliveryTime['logo']}
							<img src="{$aDeliveryTime['logo']}" alt="{$aDeliveryTime['name']}" />
						{/if}
						<p>
							{$aDeliveryTime['description']}&nbsp;<strong>{$aDeliveryTime['shipping_date_min']|date_format:'d/m/Y'}</strong>
						</p>

					</div>

				</li>

			{/foreach}

		</ul>

		<span class="clearBoth"></span>

	</div>

{/if}

<script>
	$('#nowDeliveryTime ul li div').tooltip();
</script>