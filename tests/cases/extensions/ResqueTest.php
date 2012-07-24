<?php

namespace li3_resque\tests\cases\extensions;

use li3_resque\extensions\ResqueProxy;
use app\extensions\job\MyJob;

class ResqueTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}


	public function testEnqueuing() 
	{
		$args = array(
		    'name' => 'Batman'
		);
		ResqueProxy::enqueue('default', 'li3_resque\tests\mocks\MockJob', $args);
	}
}

?>