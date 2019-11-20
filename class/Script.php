<?php

namespace Avorg;

use Exception;

if (!\defined('ABSPATH')) exit;

class Script
{
	/** @var WordPress $wp */
	private $wp;

	private $actions = [];
	private $handle;
	private $path;
	private $deps = [];
	private $inFooter = false;
	private $data = [];

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

	public function setData($data)
	{
		$this->data = array_merge($this->data, $data);
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

    /**
     * @throws Exception
     */
    public function registerCallbacks()
	{
        if (! $this->actions) {
            throw new Exception("No actions set for script $this->path");
        }

        array_walk($this->actions, function($action) {
           $this->wp->add_action($action, [$this, "enqueue"]);
        });
	}

    /**
     * @throws Exception
     */
    public function enqueue()
	{
		if (!$this->path) {
            throw new Exception("Failed to enqueue script. Path not set.");
        }

		$handle = $this->getHandle();

		$this->wp->wp_enqueue_script(
		    $handle,
            $this->path,
            $this->deps,
            null,
            $this->inFooter
        );

		$this->wp->wp_localize_script(
		    $handle,
            "avorg",
            $this->getLocalizeData()
        );
	}

	/**
	 * @return string
	 */
	private function getHandle()
	{
	    if ($this->handle) return $this->handle;

		$class = get_class($this);

		return str_replace("\\", "_", $class) . "_" . sha1($this->path);
	}

	/**
	 * @return array
	 */
	private function getLocalizeData()
	{
		return array_merge([
			"nonces" => $this->getNonces(),
			"ajax_url" => $this->wp->admin_url("admin-ajax.php"),
            "query" => $this->wp->get_all_query_vars(),
            "post_id" => (int) $this->wp->get_the_ID()
		], $this->data);
	}

	/**
	 * @return mixed
	 */
	private function getNonces()
	{
		return [];
	}

    public function setDeps(...$deps)
    {
        $this->deps = $deps;
        return $this;
    }

    /**
     * @param mixed $handle
     * @return Script
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @param bool $inFooter
     * @return Script
     */
    public function setInFooter(bool $inFooter): Script
    {
        $this->inFooter = $inFooter;
        return $this;
    }
}