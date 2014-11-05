{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

{if $show_toolbar}
	{include file="page_header_toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
	{include file="helpers/modules_list/modal.tpl" }
{/if}

<div class="leadin">{block name="leadin"}{/block}</div>

{include file="$module_path/steps.tpl" selected=$current_step pagination=$pagination}

<div class="now_all_import">
	<p>{l s='The import module allows you to connect a product with one or more accessories, type of product, packs, and ideas or tips.' mod='now_all_import'}</p>
	<p>{l s='To begin, please import a. CSV file.' mod='now_all_import'}</p>

	<hr>

	<p>{l s='Explication of the exemple file:' mod='now_all_import'}</p>

	<hr>

	<table class="table">

		<thead>
			<tr>
				<th class="center">id_product</th>
				<th class="center">product_reference</th>
				<th class="center">active</th>
				<th class="center">accessories</th>
				<th class="center">product_type_id</th>
				<th class="center">tips_or_ideas</th>
				<th class="center">products_pack</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td class="center">{l s='It is the product ID. Only the "product_id" fields or "product_reference" is required.' mod='now_all_import'}</td>
				<td class="center">{l s='It\'s the product reference. Only the "product_id" fields or "product_reference" is required.' mod='now_all_import'}</td>
				<td class="center">{l s='You can associate a product with a type of product but do not activate this link. For this, in this column, if "1" is information, the link will be active. If "0" is information, the link will be inactive.' mod='now_all_import'}</td>
				<td class="center">{l s='These product references or products Id\'s that made ​​the accessories separated by "::".' mod='now_all_import'}</td>
				<td class="center">{l s='This is the id of the product type. You can pick up the equivalent of this ID in "Ninja Of Web" menu and "type of products Manage" tab.' mod='now_all_import'}</td>
				<td class="center">{l s='These tips or ideas that made ​products by "::".' mod='now_all_import'}</td>
				<td class="center">{l s='These product references or products Id\'s that made ​​the product pack separated by "::". Brackets will present different amounts of these products.' mod='now_all_import'}</td>
			</tr>
			<tr>
				<td class="center">1</td>
				<td class="center">NF00001</td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center">2</td>
				<td class="center"></td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">2</td>
				<td class="center">NF00002</td>
				<td class="center">1</td>
				<td class="center">2::3::4</td>
				<td class="center"></td>
				<td class="center">1::3::4</td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">3</td>
				<td class="center">NF00003</td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center">5</td>
				<td class="center"></td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">4</td>
				<td class="center">NF00004</td>
				<td class="center">1</td>
				<td class="center">1::2</td>
				<td class="center">6</td>
				<td class="center"></td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">5</td>
				<td class="center"></td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center">10::3</td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">7</td>
				<td class="center"></td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center">15</td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">10</td>
				<td class="center">NF000010</td>
				<td class="center">0</td>
				<td class="center"></td>
				<td class="center">4</td>
				<td class="center"></td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">15</td>
				<td class="center">NF000015</td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center">3</td>
				<td class="center"></td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center">338</td>
				<td class="center"></td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center">333::334</td>
			</tr>
			<tr>
				<td class="center"></td>
				<td class="center">NF00006</td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center">4::15::10::3::1</td>
				<td class="center"></td>
			</tr>
			<tr>
				<td class="center"></td>
				<td class="center">NF00332</td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center">NF00340(2)::NF00348</td>
			</tr>
			<tr>
				<td class="center"></td>
				<td class="center">NF00356</td>
				<td class="center">1</td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center"></td>
				<td class="center">NF00343(1)::NF00345(2)::NF00347(3)::NF00346(4)</td>
			</tr>
		</tbody>

	</table>

	<script type="text/javascript">function showBanNow() { $('#ninja-of-web').animate( { "margin-right":'-260px' } , 500).attr('class', 'hover'); };function hideBanNow() { $('#ninja-of-web').animate( { "margin-right":'-690px' } , 500).removeAttr('class'); };</script>
	<div id="ninja-of-web">
		<a class="open-now" onclick="javascript:showBanNow();"></a>
		<a class="close-now" onclick="javascript:hideBanNow();"></a>
		<p>Ninja Of Web</p>
		<p>Développeur web</p>
		<p><a href="mailto:contact@ninja-of-web.fr" title="NinjaOfweb">contact@ninja-of-web.fr</a></p>
		<p><a href="http://www.ninja-of-web.fr" title="NinjaOfweb">www.ninja-of-web.fr</a></p>
	</div>
</div>