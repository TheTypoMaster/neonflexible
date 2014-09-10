{if $aItems && count($aItems) > 0}
	<div id="presentation-company">

		<div class="container">

			<ul>
				{foreach $aItems as $key => $aItem}
					{if $aItem['active'] && $key < 2}
						<li class="float-{$aItem['float']|default:'left'}">
							<img src="{$module_dir}{$aItem['file_name']}" alt="{$aItem['name']}" />
							<p class="titre">{$aItem['name']}</p>
							<p class="desc">{$aItem['description']}</p>
						</li>
					{/if}
				{/foreach}
			</ul>

			<span class="clearBoth"></span>

			<a href="{Context::getContext()->link->getCMSLink(Configuration::get('NOW_PRESENTATION_CMS_ID'))}" class="button-plus" title="{l s='EN SAVOIR PLUS sur neon flexible' mod='now_block_presentation'}">
				{l s='EN SAVOIR PLUS sur neon flexible' mod='now_block_presentation'}
			</a>

		</div>

	</div>
{/if}