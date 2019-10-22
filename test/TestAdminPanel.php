<?php

final class TestAdminPanel extends Avorg\TestCase
{
	private $saveApiCredentialsPost = array(
		"api-user" => "user",
		"api-pass" => "pass",
		"api-key" => "key",
		"save-api-credentials" => "Save credentials"
	);
	
	/** @var \Avorg\AdminPanel $adminPanel */
	protected $adminPanel;
	
	public function setUp(): void
	{
		parent::setUp();
		
		$this->adminPanel = $this->factory->secure("Avorg\\AdminPanel");
	}
	
	public function testRendersPage()
	{
        $this->mockWordPress->setMappedReturnValues('get_option', [
            ['avorgApiUser', 'the_username'],
            ['avorgApiPass', 'the_password'],
            ['avorgApiKey', 'the_key']
        ]);
		
		$this->adminPanel->render();
		
		$this->mockTwig->assertTwigTemplateRenderedWithData(
			"admin.twig",
			[
			    "apiUser" => "the_username",
                "apiPass" => "the_password",
                "apiKey" => "the_key"
            ]
		);
	}
	
	public function testRegistersPage()
	{
		$this->adminPanel->register();
		
		$this->mockWordPress->assertMethodCalledWith(
			"add_menu_page",
			"AVORG", "AVORG", "manage_options", "avorg",
			array($this->adminPanel, "render")
		);
	}
	
	public function testSetsApiUser()
	{
		$_POST = $this->saveApiCredentialsPost;
		
		$this->adminPanel->render();
		
		$this->mockWordPress->assertMethodCalledWith(
			"update_option", "avorgApiUser", "user");
	}
	
	public function testSetsApiPass()
	{
		$_POST = $this->saveApiCredentialsPost;
		
		$this->adminPanel->render();
		
		$this->mockWordPress->assertMethodCalledWith(
			"update_option", "avorgApiPass", "pass");
	}

    public function testSetsApiKey()
    {
        $_POST = $this->saveApiCredentialsPost;

        $this->adminPanel->render();

        $this->mockWordPress->assertMethodCalledWith(
            "update_option", "avorgApiKey", "key");
    }
	
	public function testGetsApiUser()
	{
		$this->adminPanel->render();
		
		$this->mockWordPress->assertMethodCalledWith(
			"get_option", "avorgApiUser");
	}
	
	public function testGetsApiPass()
	{
		$this->adminPanel->render();
		
		$this->mockWordPress->assertMethodCalledWith(
			"get_option", "avorgApiPass");
	}

	public function testGetsApiKey()
    {
        $this->adminPanel->render();

        $this->mockWordPress->assertMethodCalledWith(
            "get_option", "avorgApiKey");
    }
}