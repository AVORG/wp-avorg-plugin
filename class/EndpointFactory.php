<?php


namespace Avorg;

use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class EndpointFactory
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
        $entity = $this->scanningFactory->getEntities("class/Endpoint");
        array_walk($entity, function (Endpoint $entity) {
            $entity->registerCallbacks();
        });
    }

    /**
     * @param $class
     * @return mixed
     * @throws ReflectionException
     */
    public function getEndpointByClass($class)
    {
        return $this->factory->secure($class);
    }

    public function getEndpointById($id)
	{
		if (strpos($id, "Avorg_Endpoint_") !== 0) return null;

		$class = str_replace("_", "\\", $id);

		if (!class_exists($class)) return null;

		return $this->factory->secure($class);
	}
}