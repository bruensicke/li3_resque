<?php

namespace li3_resque\extensions;

abstract class Job extends \lithium\core\StaticObject {

	public $args;


	public function setUp() {

	}

	public function tearDown() {
		
	}

	abstract public function perform();
}
