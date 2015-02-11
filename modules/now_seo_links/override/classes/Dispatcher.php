<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */
require_once (_PS_MODULE_DIR_.'now_seo_links/classes/NowLanguageLink.php');

class Dispatcher extends DispatcherCore
{
	/**
	 * Add "categories" in category rule keyword on route
	 * And "id" params now must not necessary but "rewrite" params yes in Link
	 *
	 * @module now_seo_links
	 *
	 * @see DispatcherCore::__construct()
	 */
	protected function __construct()
	{
		$this->default_routes['category_rule']['keywords'] = array_merge($this->default_routes['category_rule']['keywords'], array(
				'categories' => array(
					'regexp' => '[/_a-zA-Z0-9-\pL]*'
				),
				'id' => array(
					'regexp' => '[0-9]+'
				),
				'rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
					'param' => 'category_rewrite'
				),
			)
		);

		$this->default_routes['product_rule']['keywords'] = array_merge($this->default_routes['product_rule']['keywords'], array(
				'id' => array(
					'regexp' => '[0-9]+'
				),
				'rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
					'param' => 'product_rewrite'
				),
			)
		);

		$this->default_routes['supplier_rule']['keywords'] = array_merge($this->default_routes['supplier_rule']['keywords'], array(
				'id' => array(
					'regexp' => '[0-9]+'
				),
				'rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
					'param' => 'supplier_rewrite'
				),
			)
		);

		$this->default_routes['manufacturer_rule']['keywords'] = array_merge($this->default_routes['manufacturer_rule']['keywords'], array(
				'id' => array(
					'regexp' => '[0-9]+'
				),
				'rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
					'param' => 'manufacturer_rewrite'
				),
			)
		);

		$this->default_routes['cms_rule']['keywords'] = array_merge($this->default_routes['cms_rule']['keywords'], array(
				'id' => array(
					'regexp' => '[0-9]+'
				),
				'rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
					'param' => 'cms_rewrite'
				),
				'category_cms_rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
				),
			)
		);

		$this->default_routes['cms_category_rule']['keywords'] = array_merge($this->default_routes['cms_category_rule']['keywords'], array(
				'id' => array(
					'regexp' => '[0-9]+'
				),
				'rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
					'param' => 'cms_category_rewrite'
				),
			)
		);

		$this->default_routes['layered_rule']['keywords'] = array_merge($this->default_routes['layered_rule']['keywords'], array(
				'id' => array(
					'regexp' => '[0-9]+'
				),
				'rewrite' => array(
					'regexp' => '[_a-zA-Z0-9-\pL]*',
					'param' => 'category_rewrite'
				),
			)
		);

		/*$this->default_routes['attachment_rule'] = array(
			'controller' =>	'attachment',
			'rule' =>		'{categories:/}{product_name}/{file_name}',
			'keywords' => array(
				'id' =>				array('regexp' => '[0-9]+'),
				'file_name' =>		array('regexp' => '[\._a-zA-Z0-9-\pL]*', 'param' => 'attachment_file_name'),
				'categories' =>     array('regexp' => '[/_a-zA-Z0-9-\pL]*'),
				'product_name' =>   array('regexp' => '[_a-zA-Z0-9-\pL]*'),
			),
		);*/

		$this->setOldRoutes();
		parent::__construct();
	}

	/**
	 * Retrieve the controller from url or request uri if routes are activated
	 *
	 * @return string
	 */
	public function getController($id_shop = null) {
		$controller = parent::getController($id_shop);

		$this->controller = '';
		$_GET['controller'] = '';
		$oldController = $this->getOldController();

		$whiteListControllers = array('category', 'supplier', 'manufacturer', /*'cms',*/ 'product');
		if (in_array($oldController, $whiteListControllers) && (($controller == $this->controller_not_found && $oldController != $this->controller_not_found) || $oldController == $controller)) {

			// Redirection 301 : on laisse faire l'override du controller

		} else {
			$this->controller = $controller;
			$_GET['controller'] = $controller;
		}

		return $controller;
	}

	public function setOldRoutes() {

		// @todo : a rendre paramétrable à l'instalation...
		$this->default_routes['category_rule']['oldRule'] = "{id}-{rewrite}";
		$this->default_routes['supplier_rule']['oldRule'] = "{id}__{rewrite}";
		$this->default_routes['manufacturer_rule']['oldRule'] = "{id}_{rewrite}";
		$this->default_routes['cms_rule']['oldRule'] = "content/{id}-{rewrite}";
		$this->default_routes['cms_category_rule']['oldRule'] = "content/category/{id}-{rewrite}";
		$this->default_routes['module']['oldRule'] = "module/{module}{/:controller}";
		$this->default_routes['product_rule']['oldRule'] = "{category:/}{id}-{rewrite}{-:ean13}.html";
		$this->default_routes['layered_rule']['oldRule'] = "{id}-{rewrite}{/:selected_filters}";
	}

	/**
	 * Retrieve the controller from url or request uri if routes are activated
	 *
	 * @return string
	 */
	public function getOldController($id_shop = null) {
		if (defined('_PS_ADMIN_DIR_')) {
			$_GET['controllerUri'] = Tools::getvalue('controller');
		}
		if ($this->controller)
		{
			$_GET['controller'] = $this->controller;
			return $this->controller;
		}

		if ($id_shop === null) {
			$id_shop = (int)Context::getContext()->shop->id;
		}

		$controller = Tools::getValue('controller');

		if (isset($controller) && is_string($controller) && preg_match('/^([0-9a-z_-]+)\?(.*)=(.*)$/Ui', $controller, $m))
		{
			$controller = $m[1];
			if (isset($_GET['controller'])) {
				$_GET[$m[2]] = $m[3];
			} elseif (isset($_POST['controller'])) {
				$_POST[$m[2]] = $m[3];
			}
		}

		if (!Validate::isControllerName($controller)) {
			$controller = false;
		}

		// Use routes ? (for url rewriting)
		if ($this->use_routes && !$controller && !defined('_PS_ADMIN_DIR_'))
		{
			if (!$this->request_uri) {
				return strtolower($this->controller_not_found);
			}
			$controller = $this->controller_not_found;

			// If the request_uri matches a static file, then there is no need to check the routes, we keep "controller_not_found" (a static file should not go through the dispatcher)
			if (!preg_match('/\.(gif|jpe?g|png|css|js|ico)$/i', $this->request_uri))
			{
				// Add empty route as last route to prevent this greedy regexp to match request uri before right time
				if ($this->empty_route) {
					$this->addRoute($this->empty_route['routeID'], $this->empty_route['rule'], $this->empty_route['controller'], Context::getContext()->language->id, array(), array(), $id_shop);
				}

				if (isset($this->routes[$id_shop][Context::getContext()->language->id])) {
					foreach ($this->routes[$id_shop][Context::getContext()->language->id] as $route) {
						if (preg_match($route['oldRegexp'], $this->request_uri, $m))
						{
							// Route found ! Now fill $_GET with parameters of uri
							foreach ($m as $k => $v)
								if (!is_numeric($k))
									$_GET[$k] = $v;

							$controller = $route['controller'] ? $route['controller'] : $_GET['controller'];
							if (!empty($route['params']))
								foreach ($route['params'] as $k => $v)
									$_GET[$k] = $v;

							// A patch for module friendly urls
							if (preg_match('#module-([a-z0-9_-]+)-([a-z0-9]+)$#i', $controller, $m))
							{
								$_GET['module'] = $m[1];
								$_GET['fc'] = 'module';
								$controller = $m[2];
							}

							if (isset($_GET['fc']) && $_GET['fc'] == 'module')
								$this->front_controller = self::FC_MODULE;
							break;
						}
					}
				}
			}

			if ($controller == 'index' || $this->request_uri == '/index.php')
				$controller = $this->default_controller;
			$this->controller = $controller;
		}
		// Default mode, take controller from url
		else {
			$this->controller = $controller;
		}

		$this->controller = str_replace('-', '', $this->controller);
		$_GET['controller'] = $this->controller;
		return $this->controller;
	}

	/**
	 *
	 * @param string $route_id Name of the route (need to be uniq, a second route with same name will override the first)
	 * @param string $rule Url rule
	 * @param string $controller Controller to call if request uri match the rule
	 * @param int $id_lang
	 * @param int $id_shop
	 */
	public function addRoute($route_id, $rule, $controller, $id_lang = null, array $keywords = array(), array $params = array(), $id_shop = null)
	{
		parent::addRoute($route_id, $rule, $controller, $id_lang, $keywords, $params, $id_shop);

		if ($id_lang === null)
			$id_lang = (int)Context::getContext()->language->id;

		if ($id_shop === null)
			$id_shop = (int)Context::getContext()->shop->id;

		// On récupère l'ancienne règle :
		$oldRule = $this->getOldRule($route_id, $rule);
		$oldRegexp = preg_quote($oldRule, '#');

		if ($keywords)
		{
			$transform_keywords = array();
			preg_match_all('#\\\{(([^{}]*)\\\:)?('.implode('|', array_keys($keywords)).')(\\\:([^{}]*))?\\\}#', $oldRegexp, $m);
			for ($i = 0, $total = count($m[0]); $i < $total; $i++)
			{
				$prepend = $m[2][$i];
				$keyword = $m[3][$i];
				$append = $m[5][$i];
				$transform_keywords[$keyword] = array(
					'required' =>	isset($keywords[$keyword]['param']),
					'prepend' =>	stripslashes($prepend),
					'append' =>		stripslashes($append),
				);

				$prepend_regexp = $append_regexp = '';
				if ($prepend || $append)
				{
					$prepend_regexp = '('.preg_quote($prepend);
					$append_regexp = preg_quote($append).')?';
				}

				if (isset($keywords[$keyword]['param']))
					$oldRegexp = str_replace($m[0][$i], $prepend_regexp.'(?P<'.$keywords[$keyword]['param'].'>'.$keywords[$keyword]['regexp'].')'.$append_regexp, $oldRegexp);
				else
					$oldRegexp = str_replace($m[0][$i], $prepend_regexp.'('.$keywords[$keyword]['regexp'].')'.$append_regexp, $oldRegexp);

			}
		}

		$oldRegexp = '#^/'.$oldRegexp.'(\?.*)?$#u';

		$this->routes[$id_shop][$id_lang][$route_id]['oldRegexp'] = $oldRegexp;
	}

	public function getOldRule($route_id, $rule) {

		if (array_key_exists($route_id, $this->default_routes) && array_key_exists('oldRule', $this->default_routes[$route_id])) {

			return $this->default_routes[$route_id]['oldRule'];
		}

		return $rule;

	}

	/**
	 * Set request uri and iso lang
	 */
	protected function setRequestUri()
	{
		// Get request uri (HTTP_X_REWRITE_URL is used by IIS)
		if (isset($_SERVER['REQUEST_URI']))
			$this->request_uri = $_SERVER['REQUEST_URI'];
		else if (isset($_SERVER['HTTP_X_REWRITE_URL']))
			$this->request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
		$this->request_uri = rawurldecode($this->request_uri);

		if (isset(Context::getContext()->shop) && is_object(Context::getContext()->shop))
			$this->request_uri = preg_replace('#^'.preg_quote(Context::getContext()->shop->getBaseURI(), '#').'#i', '/', $this->request_uri);

		// If there are several languages, get language from uri
		if ($this->use_routes && Language::isMultiLanguageActivated()) {
			if (preg_match('#^/([a-z-]{2,20})(?:/.*)?$#', $this->request_uri, $m))
			{
				if ($m[1] != 'modules') {

					// Default Language
					$_GET['isolang'] = 'fr';

					$sIsoCode = NowLanguageLink::getIsoCodeByFolderName($m[1]);

					if ($sIsoCode) {
						$_GET['isolang'] = $sIsoCode;

						$this->request_uri = substr($this->request_uri, (strlen($m[1]) + 1));
					}
				} else {
					$_GET['isolang'] = Context::getContext()->language->iso_code;
				}

			} elseif ($this->request_uri == '/') {
				// Default Language
				$_GET['isolang'] = 'fr';
			}
		}

		if (!isset($_GET['isolang']) || $_GET['isolang'] === null) {
			// Default Language
			$_GET['isolang'] = 'fr';
		}
	}


}
