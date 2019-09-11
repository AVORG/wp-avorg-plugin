<?php

namespace Avorg\Block;

use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class RelatedSermons
{
	private $template = 'block-relatedSermons.twig';

	/** @var Renderer $renderer */
	private $renderer;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(Renderer $renderer, WordPress $wp)
	{
	    $this->renderer = $renderer;
		$this->wp = $wp;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("init", [$this, "init"]);
	}

	public function init()
	{
		$this->wp->register_block_type($this->getName(), [
			'editor_script' => "Avorg_Script_Editor",
            'render_callback' => [$this, 'render']
		]);
	}

	public function render()
    {
        return $this->renderer->render($this->template, [], true);
    }

	private function getName()
	{
		return 'avorg/' . strtolower($this->getBasename());
	}

	/**
	 * @return mixed
	 */
	private function getBasename()
	{
		return explode('.', $this->template)[0];
	}
}