<?php

final class TestAdminPanel extends Avorg\TestCase
{
	private $saveApiCredentialsPost = array(
		"api-user" => "user",
		"api-pass" => "pass",
		"save-api-credentials" => "Save credentials"
	);
	
	public function testRendersPage()
	{
		$this->mockWordPress->setReturnValues( "call", [ "username", "password" ] );
		
		$this->mockedAdminPanel->render();
		
		$this->assertCalledWith($this->mockTwig, "render", "admin.twig", ["apiUser" => "username", "apiPass" => "password"]);
	}
	
	public function testRegistersPage()
	{
		$this->mockedAdminPanel->register();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"add_menu_page",
			"AVORG", "AVORG", "manage_options", "avorg",
			array($this->mockedAdminPanel, "render")
		);
	}
	
	public function testRunsActivationCodeWhenAsked()
	{
		$_POST = array("reactivate" => "Reactivate");
		
		$this->mockedAdminPanel->render();
		
		$this->assertCalled($this->mockPlugin, "activate");
	}
	
	public function testSetsApiUser()
	{
		$_POST = $this->saveApiCredentialsPost;
		
		$this->mockedAdminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"update_option", "avorgApiUser", "user");
	}
	
	public function testSetsApiPass()
	{
		$_POST = $this->saveApiCredentialsPost;
		
		$this->mockedAdminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"update_option", "avorgApiPass", "pass");
	}
	
	public function testGetsApiUser()
	{
		$this->mockedAdminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"get_option", "avorgApiUser");
	}
	
	public function testGetsApiPass()
	{
		$this->mockedAdminPanel->render();
		
		$this->assertCalledWith($this->mockWordPress, "call",
			"get_option", "avorgApiPass");
	}
}