
		{if !$content_only}

			{$HOOK_RIGHT_COLUMN}

			<footer>

				<div class="container">{$HOOK_FOOTER}</div>

			</footer>

		{/if}

		{if isset($js_files)}
			{foreach from=$js_files item=js_uri}
				<script type="text/javascript" src="{$js_uri}"></script>
			{/foreach}
		{/if}

		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="{$js_dir}bootstrap/bootstrap.min.js"></script>

		{include file="$tpl_dir./global.tpl"}

	</body>
</html>