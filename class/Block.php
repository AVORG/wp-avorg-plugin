<?php

namespace Avorg;

use Avorg\DataObjectRepository\PresentationRepository;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

abstract class Block
{
    protected $template;

    /** @var Renderer $renderer */
    private $renderer;

    /** @var WordPress $wp */
    protected $wp;

    public function __construct(
        Renderer $renderer,
        WordPress $wp
    )
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

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        return $this->renderer->render($this->template, $this->getData(), true);
    }

    protected abstract function getData();

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

    protected function getEntityId()
    {
        return $this->wp->get_query_var("entity_id");
    }
}