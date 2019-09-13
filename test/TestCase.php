<?php

namespace Avorg;

use Avorg\DataObject\BibleBook;
use Avorg\DataObject\Book;
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
	
	protected function setUp(): void
	{
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

	protected function makeSeries($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Series", $data);
	}

	protected function makeSponsor($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Sponsor", $data);
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
	 * @param array $data
	 * @return BibleBook
	 * @throws ReflectionException
	 */
	protected function makeBibleBook($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\BibleBook", $data);
	}

	/**
	 * @param $data
	 * @return Recording
	 * @throws ReflectionException
	 */
	protected function makePresentation($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Recording\\Presentation", $data);
	}

	/**
	 * @param array $data
	 * @return Recording\BibleChapter
	 * @throws ReflectionException
	 */
	protected function makeBibleChapter($data = [])
	{
		return $this->makeDataObject("Avorg\\DataObject\\Recording\\BibleChapter", $data);
	}

	/**
	 * @param array $data
	 * @return Book
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
		$apiResponse = $this->arrayToObject($data);

		$object->setData($apiResponse);

		return $object;
	}

    /**
     * @param $array
     * @return mixed
     */
    public function arrayToObject($array)
    {
    	if ($array == []) return new stdClass();

        return json_decode(json_encode($array), FALSE);
    }

    protected function arrSafe($key, $array, $default = Null)
    {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }
}
