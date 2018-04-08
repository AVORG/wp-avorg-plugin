<?php

namespace Avorg;

require_once(dirname(__FILE__) . '/MockFactory.php');

abstract class TestCase extends \PHPUnit\Framework\TestCase {
	protected function setUp()
	{
		define( "ABSPATH", "/" );
	}
}