<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

abstract class AjaxAction
{
	/** @var Php $php */
	protected $php;

	/** @var WordPress $wp */
	protected $wp;

	public function __construct(Php $php, WordPress $wp)
	{
		$this->php = $php;
		$this->wp = $wp;
	}

	public function registerCallbacks()
	{
		$identifier = $this->getIdentifier();

		$this->wp->add_action("wp_ajax_$identifier", [$this, "run"]);
		$this->wp->add_action("wp_ajax_nopriv_$identifier", [$this, "run"]);
	}

	public function getNonce()
	{
		$id = $this->getIdentifier();
		return $this->wp->wp_create_nonce($id);
	}

	public function run()
	{
		$this->checkNonce();
		$this->php->doEcho(json_encode($this->getResponseData()));
		$this->php->doDie();
	}

	protected function checkNonce()
	{
		$nonceName = $this->getIdentifier();
		$this->wp->check_ajax_referer($nonceName);
	}

	/**
	 * @return mixed
	 */
	private function getIdentifier()
	{
		return str_replace("\\", "_", get_class($this));
	}

	abstract protected function getResponseData();

	public function getSimpleIdentifier()
	{
        $pieces = explode("\\", get_class($this));

        return strtolower(end($pieces));
	}
}