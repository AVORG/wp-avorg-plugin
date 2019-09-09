<?php

use Avorg\ContentBits;

final class TestContentBits extends Avorg\TestCase
{
	/** @var ContentBits $contentBits */
	protected $contentBits;
	
	public function setUp()
	{
		parent::setUp();

		$this->contentBits = $this->factory->secure("Avorg\\ContentBits");
	}
	
	public function testInitMethodRegistersPostType()
	{
		$this->contentBits->init();
		
		$this->mockWordPress->assertMethodCalled("register_post_type");
	}
	
	public function testInitRegistersTaxonomy()
	{
		$this->contentBits->init();
		
		$this->mockWordPress->assertMethodCalled("register_taxonomy");
	}
	
	public function testAddMetaBoxMethod()
	{
		$this->contentBits->addMetaBoxes();
		
		$this->mockWordPress->assertMethodCalled("add_meta_box");
	}
	
	public function testGetsSavedIdentifier()
	{
		$this->mockWordPress->setReturnValue("get_the_ID", 7);
		
		$this->contentBits->renderIdentifierMetaBox();
		
		$this->mockWordPress->assertMethodCalledWith( "get_post_meta", 7, "avorgBitIdentifier", true);
	}
	
	public function testPassesSavedValueToTwig()
	{
		$this->mockWordPress->setReturnValue("get_post_meta", "saved_value");
		
		$this->contentBits->renderIdentifierMetaBox();
		
		$this->mockTwig->assertTwigTemplateRenderedWithData("molecule-identifierMetaBox.twig", ["savedIdentifier" => "saved_value"]);
		
	}
	
	public function testSavesIdentifier()
	{
		$_POST["avorgBitIdentifier"] = "new_identifier";
		
		$this->mockWordPress->setReturnValue("get_the_ID", 7);
		
		$this->contentBits->saveIdentifierMetaBox();
		
		$this->mockWordPress->assertMethodCalledWith(
			"update_post_meta",
			7,
			"avorgBitIdentifier",
			"new_identifier"
		);
	}
	
	public function testDoesntSaveIfValueNotPassed()
	{
		$this->contentBits->saveIdentifierMetaBox();

		$this->mockWordPress->assertMethodNotCalled("update_post_meta");
	}

	public function testAddsTwoMetaBoxes()
	{
		$this->contentBits->addMetaBoxes();

		$this->mockWordPress->assertCallCount("add_meta_box", 2);
	}

	public function testDocumentationMetaBoxRenderMethod()
	{
		$this->contentBits->renderDocumentationMetaBox();

		$this->mockTwig->assertTwigTemplateRendered("molecule-contentBitsDocs.html");
	}

	public function testGetsIdentifiers()
	{
		$this->contentBits->renderIdentifierMetaBox();

		$this->mockWordPress->assertMethodCalledWith("get_all_meta_values", "avorgBitIdentifier");
	}

	public function testPassesIdentifiersToView()
	{
		$this->mockWordPress->setReturnValue("get_all_meta_values", [
			"identifier_1",
			"identifier_2"
		]);

		$this->contentBits->renderIdentifierMetaBox();

		$this->mockTwig->assertTwigTemplateRenderedWithData(
			"molecule-identifierMetaBox.twig",
			["allIdentifiers" => ["identifier_1", "identifier_2"]]
		);
	}

	public function testExposesContentBitsToRestApi()
    {
        $this->contentBits->init();

        $this->mockWordPress->assertAnyCallMatches('register_post_type', function($call) {
            return $call[1]['show_in_rest'] === True;
        });
    }

    public function testRegistersMethodOnApiInit()
    {
        $this->contentBits->registerCallbacks();

        $this->mockWordPress->assertActionAdded(
            'rest_api_init', [$this->contentBits, 'exposeIdentifierInApi']);
    }

    public function testRegistersInitCallback()
    {
        $this->contentBits->registerCallbacks();

        $this->mockWordPress->assertActionAdded('init', [$this->contentBits, 'init']);
    }
}