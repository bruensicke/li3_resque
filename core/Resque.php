<?php

namespace li3_resque\core;

class Resque
{
	public static $job_status = array(
		0 => 'NOT_FOUND',
		1 => 'WAITING',
		2 => 'RUNNING',
		3 => 'FAILED',
		4 => 'COMPLETE',
	);

	public static function queue($queue = 'default', $name = 'Job', $args = array()) {
		$args = array(
			'time' => time(),
			'array' => array(
				'test' => 'test',
			),
		);
		return \Resque::enqueue('default', $name, $args, true);
	}

	public static function host($host) {
		return \Resque::setBackend($host);
	}

	public static function size($queue = 'default') {
		return \Resque::size($queue);
	}

	public static function status($token) {
		$status = new \Resque_Job_Status($token);
		$code = $status->get();
		if (empty($code)) {
			return self::$job_status[0];
		}
		return self::$job_status[$code];
	}
}
