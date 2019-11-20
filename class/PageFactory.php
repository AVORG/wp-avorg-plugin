<?php

namespace Avorg;

use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class PageFactory
{
	/** @var Factory $factory */
	private $factory;

	/** @var ScanningFactory $scanningFactory */
	private $scanningFactory;

	public function __construct(Factory $factory, ScanningFactory $scanningFactory)
	{
		$this->factory = $factory;
		$this->scanningFactory = $scanningFactory;
	}

	public function registerCallbacks()
    {
        $this->scanningFactory->registerCallbacks("class/Page");
    }

	/**
	 * @param $class
	 * @return mixed
	 * @throws ReflectionException
	 */
	public function getPage($class)
	{
		return $this->factory->secure($class);
	}
}