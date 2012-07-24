<?php

namespace li3_resque\extensions;

abstract class Job {

	public function setUp() {

	}

	public function tearDown() {
		
	}

	abstract public function perform();
}
