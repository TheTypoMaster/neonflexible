<div id="now_category_slide">

	<div class="carousel-slick-category">

		{foreach $aSlides as $oCategory key=k name=foo}
			<div class="item {if $smarty.foreach.foo.first} active{/if} couleur_metier_{$k}">

				<a href="{Context::getContext()->link->getCategoryLink($oCategory)}" title="{$oCategory->name}">
					{$oCategory->name}
				</a>

			</div>
		{/foreach}

	</div>

</div>