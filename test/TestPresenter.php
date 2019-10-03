<?php

use Avorg\DataObject;
use Avorg\DataObject\Presenter;

final class TestPresenter extends Avorg\TestCase
{
	/** @var Presenter $presenter */
	private $presenter;

	private $apiPresenter;

	protected function setUp(): void
	{
		parent::setUp();

		$this->apiPresenter = (object) [
			"description" => "hello world",
			"id" => "131",
			"givenName" => "first",
			"surname" => "last",
			"suffix" => "suffix",
			"lang" => "en"
		];

		$this->presenter = $this->factory->make("Avorg\\DataObject\\Presenter");
		$this->presenter->setData($this->apiPresenter);
	}

	public function testGetRecordings()
	{
		$this->presenter->getRecordings();

		$this->mockAvorgApi->assertMethodCalledWith("getPresenterRecordings", 131);
	}

	public function testGetDescription()
	{
		$this->assertEquals("hello world", $this->presenter->description);
	}

	public function testIssetDescription()
	{
		$this->assertTrue($this->presenter->__isset("description"));
	}

	public function testGetName()
	{
		$this->assertEquals("first last suffix", $this->presenter->getName());
	}

	public function testGetNameWithNoGivenName()
	{
		unset($this->apiPresenter->givenName);

		$this->assertEquals("last suffix", $this->presenter->getName());
	}

	public function testGetUrl()
	{
		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/sermons/presenters/131/first-last-suffix.html",
			$this->presenter->getUrl()
		);
	}

	public function testGetNameReversed()
	{
		$this->assertEquals("last suffix, first", $this->presenter->getNameReversed());
	}

	public function testGetNameReversedWithNoSuffix()
	{
		unset($this->apiPresenter->suffix);

		$this->assertEquals("last, first", $this->presenter->getNameReversed());
	}

	public function testIncludesUrlInArray()
    {
        $this->assertToArrayKeyValue(
            $this->presenter,
            'url',
            "http://${_SERVER['HTTP_HOST']}/english/sermons/presenters/131/first-last-suffix.html"
        );
    }

    public function testIncludesTitle()
    {
        $this->assertToArrayKeyValue($this->presenter, 'title', "last suffix, first");
    }
}