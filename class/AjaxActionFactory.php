<?php

namespace Avorg;

use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class AjaxActionFactory
{
	/** @var Factory $factory */
	private $factory;

	private $actionNames = [
		"Recording"
	];

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

    public function registerCallbacks()
    {
        $actions = $this->getActions();
        array_walk($actions, function (AjaxAction $action) {
            $action->registerCallbacks();
        });
    }

	public function getActions()
	{
		return array_map(function($actionName) {
			return $this->factory->secure("Avorg\\AjaxAction\\$actionName");
		}, $this->actionNames);
	}
}