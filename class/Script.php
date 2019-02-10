<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Script
{
	/** @var WordPress $wp */
	private $wp;

	private $actions = [];
	private $path;

	public function __construct(WordPress $wp)
	{
		$this->wp = $wp;
	}

	/**
	 * @param mixed $actions
	 * @return Script
	 */
	public function setActions(...$actions)
	{
		$this->actions = $actions;
		return $this;
	}

	/**
	 * @param mixed $path
	 * @return Script
	 */
	public function setPath($path)
	{
		$isRelative = preg_match("/^(?!(http(s)?:)?\/\/).+/", $path) === 1;
		$this->path = $isRelative ? AVORG_BASE_URL . "/$path" : $path;
		return $this;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("wp_enqueue_scripts", [$this, "enqueue"]);
	}

	public function enqueue()
	{
		if (!$this->path) throw new \Exception("Failed to enqueue script. Path not set.");

		$id = $this->getScriptId();

		$this->wp->wp_enqueue_script($id, $this->path);
		$this->wp->wp_localize_script($id, "avorg", $this->getLocalizeData());
	}

	/**
	 * @return string
	 */
	private function getScriptId()
	{
		$class = get_class($this);

		return str_replace("\\", "_", $class) . "_" . sha1($this->path);
	}

	/**
	 * @return array
	 */
	private function getLocalizeData()
	{
		return [
			"nonces" => $this->getNonces(),
			"ajax_url" => $this->wp->admin_url("admin-ajax.php")
		];
	}

	/**
	 * @return mixed
	 */
	private function getNonces()
	{
		return array_reduce($this->actions, function ($carry, AjaxAction $action) {
			return array_merge($carry, [
				$action->getSimpleIdentifier() => $action->getNonce()
			]);
		}, []);
	}
}