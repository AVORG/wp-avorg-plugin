<?php

final class TestAdminPanel extends Avorg\TestCase
{
	private $saveApiCredentialsPost = array(
		"api-user" => "user",
		"api-pass" => "pass",
		"save-api-credentials" => "Save credentials"
	);
	
	/** @var \Avorg\AdminPanel $adminPanel */
	protected $adminPanel;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->adminPanel = $this->factory->getAdminPanel();
	}
	
	public function testRendersPage()
	{
		$this->mockWordPress->setReturnValues( "call", "username", "password" );
		
		$this->adminPanel->render();
		
		$this->assertTwigTemplateRenderedWithData("admin.twig", ["apiUser" => "username", "apiPass" => "password"]);
	}
	
	public function testRegistersPage()
	{
		$this->adminPanel->register();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"add_menu_page",
			"AVORG", "AVORG", "manage_options", "avorg",
			array($this->adminPanel, "render")
		);
	}
	
	public function testRunsActivationCodeWhenAsked()
	{
		$_POST = array("reactivate" => "Reactivate");
		
		$this->adminPanel->render();
		
		$this->assertWordPressFunctionCalled("flush_rewrite_rules");
	}
	
	public function testSetsApiUser()
	{
		$_POST = $this->saveApiCredentialsPost;
		
		$this->adminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"update_option", "avorgApiUser", "user");
	}
	
	public function testSetsApiPass()
	{
		$_POST = $this->saveApiCredentialsPost;
		
		$this->adminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"update_option", "avorgApiPass", "pass");
	}
	
	public function testGetsApiUser()
	{
		$this->adminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"get_option", "avorgApiUser");
	}
	
	public function testGetsApiPass()
	{
		$this->adminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"get_option", "avorgApiPass");
	}
}