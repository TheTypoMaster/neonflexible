
		{if !$content_only}

			{$HOOK_RIGHT_COLUMN}

			{if !in_array(Context::getContext()->controller->php_self, array('authentication', 'order', 'order-confirmation'))}
				<footer>

					<div class="container">
                        <div class="row">
                        	{$HOOK_FOOTER}
                        </div>
                    </div>

				</footer>
			{/if}

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
