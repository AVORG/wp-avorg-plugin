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

class RecordingsList extends Block
{
    protected $template = 'block-list.twig';

    /** @var PresentationRepository */
    private $presentationRepository;

    public function __construct(
        Renderer $renderer,
        PresentationRepository $presentationRepository,
        WordPress $wp
    )
    {
        parent::__construct($renderer, $wp);

        $this->presentationRepository = $presentationRepository;
    }

    /**
     * @param $attributes
     * @param $content
     * @return array
     * @throws Exception
     */
    protected function getData($attributes, $content)
    {
        return [
            "recordings" => $this->presentationRepository->getPresentations($attributes['type'] ?? '')
        ];
    }
}