
		{if !$content_only}

			<div id="bloc-reinsurrance">now_block_reinsurance

				<div class="container">
					<ul>
						<li>
							<img src="{$img_dir}/theme/satisfait-ou-rembourser.png" alt="{l s='Satisfait ou remboursé'}" />
							<h6>{l s='Satisfait ou remboursé'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
						<li>
							<img src="{$img_dir}/theme/paiement-securise.png" alt="{l s='Paiement sécurisé'}" />
							<h6>{l s='Paiement sécurisé'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
						<li>
							<img src="{$img_dir}/theme/expedition-sous-24.png" alt="{l s='Expedition sous 24h'}" />
							<h6>{l s='Expedition sous 24h'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
						<li>
							<img src="{$img_dir}/theme/une-question.png" alt="{l s='Une question ?'}" />
							<h6>{l s='Une question ?'}</h6>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
						</li>
					</ul>
				</div>

			</div>

			<footer>

				<div class="container">{$HOOK_FOOTER}</div>

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
