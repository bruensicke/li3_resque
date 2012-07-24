<?php

namespace li3_resque\tests\mocks;

use lithium\analysis\Logger;

class MockJob extends \li3_resque\extensions\Job {

	public function setUp() {
		Logger::debug('setUp called');
	}

	public function tearDown() {
		Logger::debug('tearDown called');
	}

	public function perform() {
		Logger::debug(json_encode($this->args));
		Logger::debug('performed');
	}
}
