<?php

namespace Avorg;

use natlib\Factory;
use ReflectionException;

abstract class TestCase extends \PHPUnit\Framework\TestCase {
	/* Mock Objects */
	
	/** @var AvorgApi|StubAvorgApi $mockAvorgApi */
	protected $mockAvorgApi;

	/** @var Filesystem|StubFilesystem */
	protected $mockFilesystem;
	
	/** @var Php|StubPhp $mockPhp */
	protected $mockPhp;
	
	/** @var Twig|StubTwig $mockTwig */
	protected $mockTwig;
	
	/** @var WordPress|StubWordPress $mockWordPress */
	protected $mockWordPress;
	
	/** @var Factory $factory */
	protected $factory;
	
	protected $textDomain = "wp-avorg-plugin";
	
	protected function setUp()
	{
		define( "ABSPATH", "/" );
		define( "AVORG_LOGO_URL", "https://s.audioverse.org/english/gallery/sponsors/_/600/600/default-logo.png" );

		$_SERVER["HTTP_HOST"] = "localhost:8080";

		$this->factory = new Factory(__NAMESPACE__);

		$this->factory->injectObjects(
			$this->mockAvorgApi = new StubAvorgApi($this),
			$this->mockFilesystem = new StubFilesystem($this),
			$this->mockPhp = new StubPhp($this),
			$this->mockTwig = new StubTwig($this),
			$this->mockWordPress = new StubWordPress($this, $this->factory)
		);
	}

	/**
	 * @param $presentationData
	 * @return Presentation
	 * @throws ReflectionException
	 */
	protected function makePresentation($presentationData)
	{
		$apiResponseObject = $this->convertArrayToObjectRecursively($presentationData);

		return $this->factory->make("Avorg\\Presentation")->setPresentation($apiResponseObject);
	}

    /**
     * @param $array
     * @return mixed
     */
    protected function convertArrayToObjectRecursively($array)
    {
    	if ($array == []) return new \stdClass();

        return json_decode(json_encode($array), FALSE);
    }
}
