<?php

namespace Avorg\Block;

use Avorg\Block;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

class RelatedSermons extends Block
{
	protected $template = 'block-relatedSermons.twig';

	/** @var Php */
	private $php;

	/** @var PresentationRepository */
	private $presentationRepository;

	public function __construct(
	    Php $php,
	    PresentationRepository $presentationRepository,
        Renderer $renderer,
        WordPress $wp
    )
	{
	    parent::__construct($renderer, $wp);

	    $this->php = $php;
	    $this->presentationRepository = $presentationRepository;

	}

    /**
     * @return array
     * @throws Exception
     */
    protected function getData() {
        $entityId = $this->getEntityId();
        $recordings = $this->presentationRepository->getRelatedPresentations($entityId);

        return [
            "recordings" => $this->php->arrayRand($recordings, 3)
        ];
    }
}