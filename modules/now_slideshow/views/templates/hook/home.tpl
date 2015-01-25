<div id="now-slideshow">

	<div class="container">

		<div class="carousel-slick">

			{foreach $aSlides as $oSlide key=k name=foo}
				<div class="item{if $smarty.foreach.foo.first} active{/if}">

					<div class="right">
						<img src="{$oSlide->getImageLink()}" alt="{$oSlide->name}"  class="img-responsive" />
					</div>

					<div class="left">

						{if $oSlide->type == NowSlideshow::TYPE_LINK}
							{assign var=link value=$oSlide->link}
						{elseif $oSlide->type == NowSlideshow::TYPE_CATEGORY}
							{assign var=link value=Context::getContext()->link->getCategoryLink($oSlide->object)}
						{elseif $oSlide->type == NowSlideshow::TYPE_MANUFACTURER}
							{assign var=link value=Context::getContext()->link->getManufacturerLink($oSlide->object)}
						{elseif $oSlide->type == NowSlideshow::TYPE_CMS}
							{assign var=link value=Context::getContext()->link->getCMSLink($oSlide->object)}
						{/if}

						<p class="titre-vert">{$oSlide->name|escape:'htmlall':'UTF-8'}</p>

						<p class="titre-blanc">{$oSlide->title|escape:'htmlall':'UTF-8'}</p>

						<div class="desc"><p>{$oSlide->description|truncate:550:'...'}</p></div>

						<a href="{$link}" class="button-white">{$oSlide->button_name|escape:'htmlall':'UTF-8'}</a>

					</div>

				</div>
			{/foreach}

		</div>

	</div>

</div>