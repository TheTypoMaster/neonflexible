<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class Link extends LinkCore
{
    /**
     * Override method getCategoryLink for use "categories" in category rule keyword on route
     *
     * @module now_seo_links
     *
     * @param mixed $category
     * @param null $alias
     * @param null $id_lang
     * @param null $selected_filters
     * @param null $id_shop
     * @return string
     * @see LinkCore::getCategoryLink()
     */
    public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null)
    {
        if (!$id_lang)
            $id_lang = Context::getContext()->language->id;

        $url = $this->getBaseLink($id_shop).$this->getLangLink($id_lang, null, $id_shop);

        if (!is_object($category))
            $category = new Category($category, $id_lang);

        // Set available keywords
        $params = array();
        $params['id'] = $category->id;
        $params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
        $params['meta_keywords'] =	Tools::str2url($category->meta_keywords);
        $params['meta_title'] = Tools::str2url($category->meta_title);

        // Selected filters is used by the module blocklayered
        $selected_filters = is_null($selected_filters) ? '' : $selected_filters;

        if (empty($selected_filters))
            $rule = 'category_rule';
        else
        {
            $rule = 'layered_rule';
            $params['selected_filters'] = $selected_filters;
        }

        $dispatcher = Dispatcher::getInstance();
        if ($dispatcher->hasKeyword('category_rule', $id_lang, 'categories', $id_shop))
        {
            $cats = array();
            foreach ($category->getParentsCategories($id_lang) as $cat)
                if (!in_array($cat['id_category'], array_merge(Link::$category_disable_rewrite, array($category->id))))//remove root and home category from the URL
                    $cats[] = $cat['link_rewrite'];

            krsort($cats);
            $params['categories'] = implode('/', $cats);
        }

        return $url.$dispatcher->createUrl($rule, $id_lang, $params, $this->allow, '', $id_shop);
    }


    /**
     * Override method getPageLink for redirect "attachment" link to method : getAttachmentLink
     *
     * @module now_seo_links
     *
     * @param string $controller
     * @param null $ssl
     * @param null $id_lang
     * @param null $request
     * @param bool $request_url_encode
     * @param null $id_shop
     * @return string
     * @see LinkCore::getPageLink()
     */
    public function getPageLink($controller, $ssl = null, $id_lang = null, $request = null, $request_url_encode = false, $id_shop = null)
    {
        if ($controller == 'attachment') {
            return $this->getAttachmentLink($id_lang, $request, $id_shop);
        } else {
            return parent::getPageLink($controller, $ssl, $id_lang, $request, $request_url_encode, $id_shop);
        }
    }

    /**
     * Get the attachment link
     *
     * @param null $id_lang
     * @param null $request
     * @param null $id_shop
     * @return string
     */
    public function getAttachmentLink($id_lang = null, $request = null, $id_shop = null) {
        $iIdAttachment = (int)str_replace('id_attachment=', '', $request);
        $oAttachment = new Attachment($iIdAttachment, $id_lang);
        $oProduct = new Product(Attachment::getProductIdByIdAttachment($oAttachment->id), $id_lang);

        // Set available keywords
        $params = array();
        $params['id'] = $oAttachment->id;
        $params['file_name'] = $oAttachment->file_name;


        $oDispatcher = Dispatcher::getInstance();
        $sRule = 'attachment_rule';

        if (Validate::isLoadedObject($oProduct)) {
            $params['product_name'] = $oProduct->getFieldByLang('link_rewrite');
            $cats = array();
            foreach ($oProduct->getParentCategories() as $cat)
                if (!in_array($cat['id_category'], Link::$category_disable_rewrite))
                    $cats[] = $cat['link_rewrite'];
            $params['categories'] = implode('/', $cats);
        }


        $sURL = $this->getBaseLink($id_shop).$this->getLangLink($id_lang, null, $id_shop);
        return $sURL.$oDispatcher->createUrl($sRule, $id_lang, $params, $this->allow, '', $id_shop);
    }

    /**
     * Create a link to a CMS page
     *
     * @param mixed $cms CMS object (can be an ID CMS, but deprecated)
     * @param string $alias
     * @param bool $ssl
     * @param int $id_lang
     * @return string
     */
    public function getCMSLink($cms, $alias = null, $ssl = null, $id_lang = null, $id_shop = null)
    {
        if (!$id_lang)
            $id_lang = Context::getContext()->language->id;

        $url = $this->getBaseLink($id_shop, $ssl).$this->getLangLink($id_lang, null, $id_shop);

        $dispatcher = Dispatcher::getInstance();
        if (!is_object($cms))
        {
            if ($alias !== null && !$dispatcher->hasKeyword('cms_rule', $id_lang, 'meta_keywords', $id_shop) && !$dispatcher->hasKeyword('cms_rule', $id_lang, 'meta_title', $id_shop))
                return $url.$dispatcher->createUrl('cms_rule', $id_lang, array('id' => (int)$cms, 'rewrite' => (string)$alias), $this->allow, '', $id_shop);
            $cms = new CMS($cms, $id_lang);
        }

        // Set available keywords
        $params = array();
        $params['id'] = $cms->id;
        $params['rewrite'] = (!$alias) ? (is_array($cms->link_rewrite) ? $cms->link_rewrite[(int)$id_lang] : $cms->link_rewrite) : $alias;

        $params['meta_keywords'] = '';
        if (isset($cms->meta_keywords) && !empty($cms->meta_keywords))
            $params['meta_keywords'] = is_array($cms->meta_keywords) ?  Tools::str2url($cms->meta_keywords[(int)$id_lang]) :  Tools::str2url($cms->meta_keywords);

        $params['meta_title'] = '';
        if (isset($cms->meta_title) && !empty($cms->meta_title))
            $params['meta_title'] = is_array($cms->meta_title) ? Tools::str2url($cms->meta_title[(int)$id_lang]) : Tools::str2url($cms->meta_title);

        $params['category_cms_rewrite'] = '';
        if (isset($cms->id_cms_category) && !empty($cms->id_cms_category))
            $params['category_cms_rewrite'] = Tools::str2url(CMSCategory::getLinkRewrite($cms->id_cms_category, $id_lang));

        return $url.$dispatcher->createUrl('cms_rule', $id_lang, $params, $this->allow, '', $id_shop);
    }
}

