<div id="now-slideshow">

	<div class="container">

		<div class="carousel-slick">

			{foreach $aSlides as $aSlide key=k name=foo}
				<div class="item{if $smarty.foreach.foo.first} active{/if}">

					<div class="left">

						<p class="titre-vert">{$aSlide['name']|escape:'htmlall':'UTF-8'}</p>

						<p class="titre-blanc">{$aSlide['title']|escape:'htmlall':'UTF-8'}</p>

						<div class="desc"><p>{$aSlide['description']|truncate:550:'...'}</p></div>

						<a href="" class="button-white">{$aSlide['button_name']|escape:'htmlall':'UTF-8'}</a>

					</div>

					{if file_exists('_PS_ROOT_DIR_'|constant|cat:'/images/slides/'|cat:$aSlide['id_now_slideshow']|cat:'.jpg')}
						<div class="right">
							<img src="/images/slides/{$aSlide['id_now_slideshow']|intval}.jpg" alt="{$aSlide['name']|escape:'htmlall':'UTF-8'}" title="{$cms->meta_title|escape:'htmlall':'UTF-8'}" />
						</div>
					{elseif file_exists('_PS_ROOT_DIR_'|constant|cat:'/images/slides/default.jpg')}
						<div class="right">
							<img src="/images/slides/default.jpg" alt="{$aSlide['name']|escape:'htmlall':'UTF-8'}" title="{$aSlide['name']|escape:'htmlall':'UTF-8'}" />
						</div>
					{/if}

				</div>
			{/foreach}

		</div>

	</div>

</div>