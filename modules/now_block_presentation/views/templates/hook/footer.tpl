{if $aItems && count($aItems) > 0}
	<div id="presentation-company">

		<div class="container">

			<h2>{l s='Neon flexible, présentation de notre entreprise'}</h2>

			<ul>
				{foreach $aItems as $position => $oNowBlockPresentation}
					{if $oNowBlockPresentation->active}
						<li class="float-{$oNowBlockPresentation->float|default:'left'}">
							<img src="{$oNowBlockPresentation->getImageLink()}" alt="{$oNowBlockPresentation->name}" />
							<h3 class="titre">{$oNowBlockPresentation->name}</h3>
							<p class="desc">{$oNowBlockPresentation->description}</p>
						</li>
					{/if}
				{/foreach}
			</ul>

			<span class="clearBoth"></span>

			<a href="{Context::getContext()->link->getCMSLink(Configuration::get('NOW_PRESENTATION_CMS_ID'))}" class="button-grey-and-green" title="{l s='EN SAVOIR PLUS sur neon flexible' mod='now_block_presentation'}">
				{l s='EN SAVOIR PLUS sur neon flexible' mod='now_block_presentation'}
			</a>

		</div>

	</div>
{/if}