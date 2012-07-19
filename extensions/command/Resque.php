<?php
namespace li3_resque\extensions\command;

use li3_resque\core\Resque as Core;

use lithium\core\Environment;
use lithium\util\String;
use lithium\net\http\Service;
use lithium\analysis\Logger;

/**
 * The Resque Command interacts with php-resque
 */
class Resque extends \lithium\console\Command {

	/**
	 * name of redis host to connect to
	 *
	 * @var string
	 */
	public $host = 'localhost:6379';

	/**
	 *
	 * @return void
	 */
	public function run() {
		$this->out(sprintf('connecting to %s', $this->host));
		Core::host($this->host);
	}

	public function size($queue = 'default') {
		$size = Core::size($queue);
		$this->out(sprintf('size of queue [%s] is [%s]', $queue, $size));
	}

	public function queue() {
		$res = Core::queue();
		$this->out($res);
	}

	public function status($token) {
		$status = Core::status($token);
		$this->out(sprintf('status for token [%s] is [%s]', $token, $status));
	}


}
