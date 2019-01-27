<?php

namespace Avorg;


class Pwa
{
	/** WordPress $wp */
	private $wp;

	public function __construct(WordPress $wp)
	{
		$this->wp = $wp;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action(
			"wp_front_service_worker",
			[$this, "registerServiceWorker"]);
	}

	public function registerServiceWorker()
	{
		var_dump('hello world');die;

		$this->wp->wp_register_service_worker_script(
			"avorgServiceWorker",
			[
				"src" => AVORG_BASE_PATH . "/serviceWorker.js"
			]);

		wp_register_service_worker_caching_route(
			'/wp-content/.*\.(?:png|gif|jpg|jpeg|svg|webp)(\?.*)?$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_CACHE_FIRST,
				'cacheName' => 'images',
				'plugins'   => array(
					'expiration'        => array(
						'maxEntries'    => 60,
						'maxAgeSeconds' => 60 * 60 * 24,
					),
				),
			)
		);
	}
}
