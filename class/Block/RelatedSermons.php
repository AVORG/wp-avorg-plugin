<?php

namespace Avorg\Block;

use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

class RelatedSermons
{
	private $template = 'block-relatedSermons.twig';

	/** @var Php */
	private $php;

	/** @var PresentationRepository */
	private $presentationRepository;

	/** @var Renderer $renderer */
	private $renderer;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
	    Php $php,
	    PresentationRepository $presentationRepository,
        Renderer $renderer,
        WordPress $wp
    )
	{
	    $this->php = $php;
	    $this->presentationRepository = $presentationRepository;
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

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        return $this->renderer->render($this->template, $this->getData(), true);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getData() {
        $entityId = $this->getEntityId();
        $recordings = $this->presentationRepository->getRelatedPresentations($entityId);

        return [
            "recordings" => $this->php->arrayRand($recordings, 3)
        ];
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

    private function getEntityId()
    {
        return $this->wp->get_query_var("entity_id");
    }
}