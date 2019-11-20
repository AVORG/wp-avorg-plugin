<?php

use Avorg\PlaceholderContent;

final class TestPlaceholderContent extends Avorg\TestCase
{
    /** @var PlaceholderContent $placeholderContent */
    protected $placeholderContent;

    public function setUp(): void
    {
        parent::setUp();

        $this->placeholderContent = $this->factory->secure("Avorg\\PlaceholderContent");
    }

    public function testInitMethodRegistersPostType()
    {
        $this->placeholderContent->init();

        $this->mockWordPress->assertMethodCalled("register_post_type");
    }

    public function testAddMetaBoxMethod()
    {
        $this->placeholderContent->addMetaBoxes();

        $this->mockWordPress->assertMethodCalled("add_meta_box");
    }

    public function testGetsSavedIdentifier()
    {
        $this->mockWordPress->setReturnValue("get_the_ID", 7);

        $this->placeholderContent->renderIdentifierMetaBox();

        $this->mockWordPress->assertMethodCalledWith("get_post_meta", 7, "avorgBitIdentifier", true);
    }

    public function testPassesSavedValueToTwig()
    {
        $this->mockWordPress->setReturnValue("get_post_meta", "saved_value");

        $this->placeholderContent->renderIdentifierMetaBox();

        $this->mockTwig->assertTwigTemplateRenderedWithData("molecule-identifierMetaBox.twig", ["savedIdentifier" => "saved_value"]);

    }

    public function testSavesIdentifier()
    {
        $_POST["avorgBitIdentifier"] = "new_identifier";

        $this->mockWordPress->setReturnValue("get_the_ID", 7);

        $this->placeholderContent->savePost();

        $this->mockWordPress->assertMethodCalledWith(
            "update_post_meta",
            7,
            "avorgBitIdentifier",
            "new_identifier"
        );
    }

    public function testDoesntSaveIfValueNotPassed()
    {
        $this->placeholderContent->savePost();

        $this->mockWordPress->assertMethodNotCalled("update_post_meta");
    }

    public function testAddsTwoMetaBoxes()
    {
        $this->placeholderContent->addMetaBoxes();

        $this->mockWordPress->assertCallCount("add_meta_box", 3);
    }

    public function testDocumentationMetaBoxRenderMethod()
    {
        $this->placeholderContent->renderDocumentationMetaBox();

        $this->mockTwig->assertTwigTemplateRendered("molecule-contentBitsDocs.html");
    }

    public function testGetsIdentifiers()
    {
        $this->placeholderContent->renderIdentifierMetaBox();

        $this->mockWordPress->assertMethodCalledWith("get_all_meta_values", "avorgBitIdentifier");
    }

    public function testPassesIdentifiersToView()
    {
        $this->mockWordPress->setReturnValue("get_all_meta_values", [
            "identifier_1",
            "identifier_2"
        ]);

        $this->placeholderContent->renderIdentifierMetaBox();

        $this->mockTwig->assertTwigTemplateRenderedWithData(
            "molecule-identifierMetaBox.twig",
            ["allIdentifiers" => ["identifier_1", "identifier_2"]]
        );
    }

    public function testExposesContentBitsToRestApi()
    {
        $this->placeholderContent->init();

        $this->mockWordPress->assertAnyCallMatches('register_post_type', function ($call) {
            return $call[1]['show_in_rest'] === True;
        });
    }

    public function testRegistersMethodOnApiInit()
    {
        $this->placeholderContent->registerCallbacks();

        $this->mockWordPress->assertActionAdded(
            'rest_api_init', [$this->placeholderContent, 'exposeIdentifierInApi']);
    }

    public function testRegistersInitCallback()
    {
        $this->placeholderContent->registerCallbacks();

        $this->mockWordPress->assertActionAdded('init', [$this->placeholderContent, 'init']);
    }

    public function testMediaIdMetaBoxCallback()
    {
        $this->placeholderContent->renderMediaIdMetaBox();

        $this->mockTwig->assertTwigTemplateRendered("molecule-mediaIdMetaBox.twig");
    }

    public function testAddsMediaIdMetaBox()
    {
        $this->placeholderContent->addMetaBoxes();

        $this->mockWordPress->assertMethodCalledWith(
            'add_meta_box',
            'avorg_placeholderContent_mediaIds',
            'Media IDs',
            [$this->placeholderContent, 'renderMediaIdMetaBox'],
            'avorg-content-bits',
            'side'
        );
    }

    public function testSavesMediaIds()
    {
        $_POST["avorgMediaIds"] = "[3]";

        $this->mockWordPress->setReturnValue("get_the_ID", 7);

        $this->placeholderContent->savePost();

        $this->mockWordPress->assertMethodCalledWith(
            "update_post_meta",
            7,
            "avorgMediaIds",
            [3]
        );
    }
}