<?php

final class TestAdminPanel extends Avorg\TestCase
{
	public function testRendersPage() {
		$this->mockedAdminPanel->render();

		$this->assertCalledWith( $this->mockTwig, "render", "admin.twig" );
	}
	
	public function testRegistersPage() {
		$this->mockedAdminPanel->register();
		
		$this->assertCalledWith( $this->mockWordPress, "call",
			"add_menu_page",
			"AVORG", "AVORG", "manage_options", "avorg",
			array( $this->mockedAdminPanel, "render" )
		);
	}
	
	public function testRunsActivationCodeWhenAsked() {
		$_POST = array( "reactivate" => "Reactivate" );
		
		$this->mockedAdminPanel->render();
		
		$this->assertCalled( $this->mockPlugin, "activate" );
	}
}