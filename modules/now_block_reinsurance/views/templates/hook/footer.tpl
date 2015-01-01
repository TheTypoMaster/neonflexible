<div id="bloc-reinsurrance">

	<div class="container">
		<ul>
			{foreach $aItems as $oNowBlockReinsurance}
				<li>
					<img src="{$oNowBlockReinsurance->getImageLink()}" alt="{$oNowBlockReinsurance->name}" />

					{if !is_null($oNowBlockReinsurance->id_cms) && $oNowBlockReinsurance->id_cms}
						{if is_null($oNowBlockReinsurance->name) && !is_null($oNowBlockReinsurance->cms_name)}
							{assign var=nameReinsurance value=$oNowBlockReinsurance->cms_name}
						{else}
							{assign var=nameReinsurance value=$oNowBlockReinsurance->name}
						{/if}
					{else}
						{assign var=nameReinsurance value=$oNowBlockReinsurance->name}
					{/if}

					<p class="title_h6">{$nameReinsurance}</p>
					<p>{$oNowBlockReinsurance->description}</p>

					<span class="clearBoth"></span>

					{if !is_null($oNowBlockReinsurance->id_cms) && $oNowBlockReinsurance->id_cms}
						<a href="{Context::getContext()->link->getCMSLink($oNowBlockReinsurance->id_cms)}" title="{$nameReinsurance}" class="link"></a>
					{elseif !is_null($oNowBlockReinsurance->link)}
						<a href="{$oNowBlockReinsurance->link}" title="{$nameReinsurance}" class="link"></a>
					{/if}
				</li>
			{/foreach}
		</ul>
	</div>

</div>