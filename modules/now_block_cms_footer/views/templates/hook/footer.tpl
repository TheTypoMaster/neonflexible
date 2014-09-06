<div id="nowBlockCmsFooter">
	<ul>

		{foreach $aColumns as $aColumn}
		{if $aColumn['active'] && array_key_exists($aColumn['id_now_block_cms_footer_column'], $aLinksByColumnId) && count($aLinksByColumnId[$aColumn['id_now_block_cms_footer_column']]) > 0}
			<li>
				{$aColumn['name']}

				{if count($aLinksByColumnId[$aColumn['id_now_block_cms_footer_column']]) > 1}<div>{/if}

				{foreach $aLinksByColumnId[$aColumn['id_now_block_cms_footer_column']] as $aColumns}
					<ul>
						{foreach $aColumns as $aLink}
							{if $aLink['active']}
								<li>
									{if $aLink['type'] == NowBlockFooterCms::TYPE_LINK}
										<a href="{$aLink['link']}">
											{$aLink['name']}
										</a>
									{elseif $aLink['type'] == NowBlockFooterCms::TYPE_CATEGORY}
										{assign var=aCategory value=Category::getCategoryInformations(array($aLink['id_type']))}
										{if array_key_exists($aLink['id_type'], $aCategory)}
											<a href="{Context::getContext()->link->getCategoryLink($aLink['id_type'])}">
												{$aCategory[$aLink['id_type']]['name']}
											</a>
										{/if}
									{elseif $aLink['type'] == NowBlockFooterCms::TYPE_MANUFACTURER}
										<a href="{Context::getContext()->link->getManufacturerLink($aLink['id_type'])}">
											{Manufacturer::getNameById($aLink['id_type'])}
										</a>
									{elseif $aLink['type'] == NowBlockFooterCms::TYPE_CMS}
										<a href="{Context::getContext()->link->getCMSLink($aLink['id_type'])}">
											{assign var=oCms value=CMS::getCMSById($aLink['id_type'])}
											{$oCms['meta_title']}
										</a>
									{/if}
								</li>
							{/if}
						{/foreach}
					</ul>
				{/foreach}
				<span class="clear"></span>

				{if count($aLinksByColumnId[$aColumn['id_now_block_cms_footer_column']]) > 1}</div>{/if}
			</li>
		{/if}
		{/foreach}

	</ul>
	<span class="clear"></span>
</div>

<span class="clear"></span>

<div id="footer-copyright-payment">
	<p class="copyright">{l s='MON TIROIR A CHAUSSETTES © %d' sprintf=$smarty.now|date_format:'%Y'}</p>
	<div class="payment">
		<p>{l s='paiement sécurisé'}</p>
		<a href="{Context::getContext()->link->getCMSLink(5)}" title="{l s='paiement sécurisé'}"><img src="{$img_dir}payment.png" alt="{l s='paiement sécurisé'}" /></a>
	</div>
</div>