{if $aItems && count($aItems) > 0}
	<div id="references-clients">

		<div class="container">

			<ul>
				{foreach $aItems as $key => $aItem}
					{if $aItem['active'] && $key < 2}
						<li>
							<img src="{$module_dir}{$aItem['file_name']}" alt="{$aItem['name']}" />
							<p>{$aItem['description']}</p>
						</li>
					{/if}
				{/foreach}
			</ul>

			<span class="clearBoth"></span>

			<a href="#" class="button-plus-references" alt="{l s='Voir plus de références'}">
				{l s='Voir plus de références'}
			</a>

		</div>

	</div>
{/if}