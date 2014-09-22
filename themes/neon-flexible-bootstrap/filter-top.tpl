<div class="filter-top">

	{include file="./product-sort.tpl"}
	{include file="./nbr-product-page.tpl"}

	<div id="displayed-list">
		<p>{l s='Affichage :'}</p>
		<ul>
			<li>
				<img src="{$img_dir}theme/mode-liste.png" alt="{l s='Mode liste de produits'}" data-type-displayed="list" data-image-hover="{$img_dir}theme/mode-liste-on.png" class="jqDisplayed" />
			</li>
			<li>
				<img src="{$img_dir}theme/mode-block.png" alt="{l s='Mode liste de produits'}" data-type-displayed="block" data-image-hover="{$img_dir}theme/mode-block-on.png" class="jqDisplayed" />
			</li>
		</ul>
		<span class="clearBoth"></span>
	</div>
	<span class="clearBoth"></span>

</div>
<span class="clearBoth"></span>