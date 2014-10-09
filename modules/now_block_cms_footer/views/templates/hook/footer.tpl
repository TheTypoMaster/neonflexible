<div id="page-listing">

	<ul>

		{foreach $aColumns as $aColumn}
			{if $aColumn['active'] && array_key_exists($aColumn['id_now_block_cms_footer_column'], $aLinksByColumnId) && count($aLinksByColumnId[$aColumn['id_now_block_cms_footer_column']]) > 0}
				<li class="hidden-xs">
					<span>{$aColumn['name']}</span>

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
											<a href="{Context::getContext()->link->getCategoryLink($aLink['object'])}">
												{if !is_null($aLink['name'])}
													{$aLink['name']}
												{else}
													{$aLink['object']->name}
												{/if}
											</a>
										{elseif $aLink['type'] == NowBlockFooterCms::TYPE_MANUFACTURER}
											<a href="{Context::getContext()->link->getManufacturerLink($aLink['object'])}">
												{if !is_null($aLink['name'])}
													{$aLink['name']}
												{else}
													{$aLink['object']->name}
												{/if}
											</a>
										{elseif $aLink['type'] == NowBlockFooterCms::TYPE_CMS}
											<a href="{Context::getContext()->link->getCMSLink($aLink['object'])}">
												{if !is_null($aLink['name'])}
													{$aLink['name']}
												{else}
													{$aLink['object']->meta_title}
												{/if}
											</a>
										{/if}
									</li>
								{/if}
							{/foreach}
						</ul>
					{/foreach}
					<span class="clear"></span>
				</li>
			{/if}
		{/foreach}

	</ul>

	<hr>

	<img src="{$img_dir}/theme/cb-visa-mastercard-paypal.png" alt="{l s='CB / Visa / MasterCard / Paypal' mod='now_block_cms_footer'}" class="footer-payement" />

</div>