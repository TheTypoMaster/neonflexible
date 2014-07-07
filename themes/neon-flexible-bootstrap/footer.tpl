




		{if !$content_only}

			<div id="bloc-reinsurrance">

				<div class="container">
					<ul>
						<li>
							<img src="{$img_dir}/theme/satisfait-ou-rembourser.png" alt="{l s='Satisfait ou remboursé'}" />
							<h6>{l s='Satisfait ou remboursé'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
						<li>
							<img src="{$img_dir}/theme/une-question.png" alt="{l s='Une question ?'}" />
							<h6>{l s='Une question ?'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
						<li>
							<img src="{$img_dir}/theme/paiement-securise.png" alt="{l s='Paiement sécurisé'}" />
							<h6>{l s='Paiement sécurisé'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
						<li>
							<img src="{$img_dir}/theme/suivi-de-livraison.png" alt="{l s='Suivi de livraison'}" />
							<h6>{l s='Suivi de livraison'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
					</ul>
				</div>

			</div>

			<footer>

				<div class="container">

					<div id="page-listing">

						<ul>
							<li class="hidden-xs">
								<span>{l s='Produits'}</span>
								<ul>
									<li>Fil lumineux</li>
									<li>Bande lumineuse</li>
									<li>Ruban led</li>
									<li>Néon led</li>
									<li>Fibre optique lumineuse</li>
								</ul>
							</li>
							<li class="hidden-xs">
								<span>{l s='Métiers'}</span>
								<ul>
									<li>Décoration</li>
									<li>Tuning</li>
									<li>Spectacle</li>
									<li>Signalétique</li>
									<li>Magasin</li>
								</ul>
								<ul>
									<li>Vêtement</li>
									<li>Lightpainting</li>
									<li>Enseigne</li>
									<li>Sécurité</li>
									<li>Sport</li>
								</ul>
							</li>
							<li>
								<span>{l s='à propos'}</span>
								<ul>
									<li>Qui sommes-nous ?</li>
									<li>Plan du site</li>
									<li>Ruban led</li>
									<li>Mentions légales</li>
									<li>Contactez-nous</li>
								</ul>
							</li>
						</ul>

					</div>

					<span class="barre hidden-xs hidden-sm hidden-md"></span>

					<div id="contact">

						<div itemscope itemtype="http://schema.org/LocalBusiness">
							<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="url">
								<img class="logo" src="{$logo_url}" alt="{$shop_name|escape:'htmlall':'UTF-8'}" {if $logo_image_width}width="{$logo_image_width}"{/if} {if $logo_image_height}height="{$logo_image_height}" {/if}  itemprop="photo" />
							</a>

							<div class="adresse" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
								<span itemprop="street-address">95 avenue Denis Papin</span><br>
								<span itemprop="postal-code">45800</span> -
								<span itemprop="locality">Saint-Jean-de-Braye</span>,
								<span itemprop="country-name">France</span>
					        </div>

							<a href="tel:{Configuration::get('PS_SHOP_PHONE')}" class="telephone" itemprop="telephone">{Configuration::get('PS_SHOP_PHONE')}</a>
						</div>

						<hr />

						<p class="follow-us">{l s='Suivez-nous sur :'}</p>
						<ul>
							<li class="facebook"><a href="" title="{l s='Facebook'}">{l s='Facebook'}</a></li>
							<li class="twitter"><a href="" title="{l s='Twitter'}">{l s='Twitter'}</a></li>
							<li class="google"><a href="" title="{l s='Google +'}">{l s='Google +'}</a></li>
						</ul>

					</div>

				</div>

			</footer>

		{/if}




		<script type="text/javascript">
			var baseDir = '{$content_dir|addslashes}';
			var baseUri = '{$base_uri|addslashes}';
			var static_token = '{$static_token|addslashes}';
			var token = '{$token|addslashes}';
			var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
			var priceDisplayMethod = {$priceDisplay};
			var roundMode = {$roundMode};
		</script>

		{if isset($js_files)}
			{foreach from=$js_files item=js_uri}
				<script type="text/javascript" src="{$js_uri}"></script>
			{/foreach}
		{/if}

		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="{$js_dir}bootstrap/bootstrap.min.js"></script>

	</body>
</html>
