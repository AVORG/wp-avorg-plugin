<?php

namespace Avorg;

use Avorg\DataObject\Recording;
use natlib\Factory;
use ReflectionException;
use stdClass;

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

	protected function assertTwigGlobalMatchesCallback(Page $page, callable $callback)
	{
		$this->mockWordPress->passCurrentPageCheck();

		$page->addUi("");

		$this->mockTwig->assertAnyCallMatches( "render", function($call) use($callback) {
			$avorg = $call[1]["avorg"];

			return call_user_func($callback, $avorg);
		});
	}

	protected function makeConference($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Conference", $data);
	}

	protected function makeStory($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Story", $data);
	}

	protected function makePlaylist($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Playlist", $data);
	}

	/**
	 * @param $data
	 * @return mixed
	 * @throws ReflectionException
	 */
	protected function makeBible($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Bible", $data);
	}

	/**
	 * @param $data
	 * @return Recording
	 * @throws ReflectionException
	 */
	protected function makeRecording($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Recording", $data);
	}

	/**
	 * @param array $data
	 * @return mixed
	 * @throws ReflectionException
	 */
	protected function makeBook($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Book", $data);
	}

	/**
	 * @param $class
	 * @param array $data
	 * @return mixed
	 * @throws ReflectionException
	 */
	private function makeDataObject($class, $data = [])
	{
		$object = $this->factory->make($class);
		$apiResponse = $this->convertArrayToObjectRecursively($data);

		$object->setData($apiResponse);

		return $object;
	}

    /**
     * @param $array
     * @return mixed
     */
    public function convertArrayToObjectRecursively($array)
    {
    	if ($array == []) return new stdClass();

        return json_decode(json_encode($array), FALSE);
    }
}
