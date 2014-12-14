<?php


class Tools extends ToolsCore {


	/**
	 * Get the user's journey
	 *
	 * @param integer $id_category Category ID
	 * @param string $path Path end
	 * @param boolean $linkOntheLastItem Put or not a link on the current category
	 * @param string [optionnal] $categoryType defined what type of categories is used (products or cms)
	 */
	public static function getPath($id_category, $path = '', $link_on_the_item = false, $category_type = 'products', Context $context = null) {
		if (!$context) {
			$context = Context::getContext();
		}

		$id_category = (int)$id_category;
		if ($id_category == 1) {
			return '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span class="navigation_end">'.$path.'</span></div>';
		}

		$pipe = Configuration::get('PS_NAVIGATION_PIPE');
		if (empty($pipe)) {
			$pipe = '>';
		}

		$full_path = '';
		if ($category_type === 'products') {
			$interval = Category::getInterval($id_category);
			$id_root_category = $context->shop->getCategory();
			$interval_root = Category::getInterval($id_root_category);
			if ($interval)
			{
				$sql = 'SELECT c.id_category, cl.name, cl.link_rewrite
						FROM '._DB_PREFIX_.'category c
						LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = c.id_category'.Shop::addSqlRestrictionOnLang('cl').')
						'.Shop::addSqlAssociation('category', 'c').'
						WHERE c.nleft <= '.$interval['nleft'].'
							AND c.nright >= '.$interval['nright'].'
							AND c.nleft >= '.$interval_root['nleft'].'
							AND c.nright <= '.$interval_root['nright'].'
							AND cl.id_lang = '.(int)$context->language->id.'
							AND c.active = 1
							AND c.level_depth > '.(int)$interval_root['level_depth'].'
						ORDER BY c.level_depth ASC';
				$categories = Db::getInstance()->executeS($sql);

				$n = 1;
				$n_categories = count($categories);
				foreach ($categories as $category)
				{
					$full_path .= '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">' .
						(($n < $n_categories || $link_on_the_item) ? '<a href="'.Tools::safeOutput($context->link->getCategoryLink((int)$category['id_category'], $category['link_rewrite'])).'" title="'.htmlentities($category['name'], ENT_NOQUOTES, 'UTF-8').'" itemprop="url">' : '').
						'<span itemprop="title">' . htmlentities($category['name'], ENT_NOQUOTES, 'UTF-8') . '</span>' .
						(($n < $n_categories || $link_on_the_item) ? '</a>' : '').
						'</div>' .
						(($n++ != $n_categories || !empty($path)) ? '<span class="navigation-pipe">'.$pipe.'</span>' : '');
				}

				return $full_path.$path;
			}
		} else if ($category_type === 'CMS') {
			$category = new CMSCategory($id_category, $context->language->id);
			if (!Validate::isLoadedObject($category))
				die(Tools::displayError());
			$category_link = $context->link->getCMSCategoryLink($category);

			if ($path != $category->name)
				$full_path .= '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Tools::safeOutput($category_link).'" itemprop="url"><span itemprop="title">'.htmlentities($category->name, ENT_NOQUOTES, 'UTF-8').'</span></a><span class="navigation-pipe">'.$pipe.'</span>'.$path;
			else
				$full_path = ($link_on_the_item ? '<a href="'.Tools::safeOutput($category_link).'" itemprop="url">' : '') . '<span itemprop="title">' . htmlentities($path, ENT_NOQUOTES, 'UTF-8') . '</span>' . ($link_on_the_item ? '</a>' : '');

			return Tools::getPath($category->id_parent, $full_path, $link_on_the_item, $category_type);
		}
	}


}