
		{if !$content_only}

			{$HOOK_RIGHT_COLUMN}

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
