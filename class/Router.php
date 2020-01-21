<?php

namespace Avorg;

use Avorg\Route\PageRoute;
use function defined;
use Exception;
use natlib\Stub;

if (!defined('ABSPATH')) exit;

class Router
{
	/** @var EndpointFactory $endpointFactory */
	private $endpointFactory;

	/** @var LanguageFactory $languageFactory */
	private $languageFactory;

	/** @var PageFactory $pageFactory */
	private $pageFactory;

	/** @var RouteFactory $routeFactory */
	private $routeFactory;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		EndpointFactory $endpointFactory,
		LanguageFactory $languageFactory,
		PageFactory $pageFactory,
		RouteFactory $routeFactory,
		WordPress $WordPress
	)
	{
		$this->endpointFactory = $endpointFactory;
		$this->languageFactory = $languageFactory;
		$this->pageFactory = $pageFactory;
		$this->routeFactory = $routeFactory;
		$this->wp = $WordPress;
	}

    public function registerCallbacks()
    {
        $this->wp->add_filter("locale", array($this, "setLocale"));
        $this->wp->add_filter("redirect_canonical", array($this, "filterRedirect"));
        $this->wp->add_action('init', [$this, 'registerRoutes']);
        $this->wp->register_activation_hook(AVORG_PLUGIN_FILE, [$this, 'activate']);
    }

	public function activate()
	{
		$this->registerRoutes();
		$this->wp->flush_rewrite_rules();
	}

	public function registerRoutes()
	{
		$routes = $this->routeFactory->getRoutes();
		array_walk($routes, function ($route) {
			$this->addRewriteTags($route);
			$this->addRewriteRules($route);
		});
	}

	/**
	 * @param $route
	 * @throws \Exception
	 */
	private function addRewriteTags(Route $route)
	{
		$tags = $route->getRewriteTags();
		$keys = array_keys($tags);
		array_walk($keys, function ($key) use ($tags) {
			$this->wp->add_rewrite_tag("%$key%", $tags[$key]);
		});
	}

	/**
	 * @param Route $route
	 * @throws \Exception
	 */
	private function addRewriteRules(Route $route)
	{
		$rules = $route->getRewriteRules();

		array_walk($rules, function ($rule) {
			$this->wp->add_rewrite_rule($rule["regex"], $rule["redirect"], "top");
		});
	}

	public function setLocale($previous)
	{
		$language = $this->getRequestLanguage();

		return ($language) ? $language->getWpCode() : $previous;
	}

	public function filterRedirect($redirectUrl)
	{
		$language = $this->getRequestLanguage();

		Logger::log(
			"Filter redirect. " .
			"Request: " . var_export($redirectUrl, TRUE) . "; " .
			"Should cancel: " . var_export((bool)$language, TRUE)
		);

		return $language ? $this->getFullRequestUri() : $redirectUrl;
	}

	public function getRequestLanguage()
	{

		$baseRoute = $this->getRequestBaseRoute();

		return $this->languageFactory->getLanguageByBaseRoute($baseRoute);
	}

	/**
	 * @return string
	 */
	public function getFullRequestUri()
	{
		return $this->getBaseUrl() . $this->getRequestPath();
	}

	public function getRequestPath()
	{
        $url = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER["REQUEST_URI"] : null;

        return parse_url($url, PHP_URL_PATH);
	}

    /**
     * @param $routableClass
     * @param array $variables
     * @return string
     * @throws Exception
     */
    public function buildUrl($routableClass, $variables = [])
	{
		return $this->getBaseUrl() . $this->buildPath($routableClass, $variables);
	}

    /**
     * @param $routableClass
     * @param array $variables
     * @return string
     * @throws Exception
     */
    public function buildPath($routableClass, $variables = [])
    {
        if (!class_exists($routableClass)) {
            throw new Exception("Class $routableClass does not exist.");
        }

        /** @var Route $route */
        $route = $this->routeFactory->getDefaultRouteByClass($routableClass);

        if (!$route) return null;

        $locale = $this->wp->get_locale() ?: "en_US";
        $language = $this->languageFactory->getLanguageByWpLangCode($locale);
        $vars = array_merge([
            "language" => $language->getBaseRoute()
        ], $variables);
        $path = $route->getPath($vars);
        $translatedPath =  $language->translatePath($path);

        return "/$translatedPath";
    }

	public function formatStringForUrl($string)
	{
		$stringLowerCase = strtolower($string);
		$stringNoPunctuation = preg_replace("/[^\w ]/", "", $stringLowerCase);

        return str_replace(" ", "-", $stringNoPunctuation);
	}

	/**
	 * @return string
	 */
	private function getBaseUrl()
	{
		return "http://${_SERVER['HTTP_HOST']}";
	}

	/**
	 * @return mixed
	 */
	private function getRequestBaseRoute()
	{
		return explode("/", trim($this->getRequestPath(), "/"))[0];
	}
}
