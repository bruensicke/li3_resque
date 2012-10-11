<?php
namespace li3_resque\extensions\command;

use li3_resque\extensions\ResqueProxy;

use lithium\core\Environment;
use lithium\util\String;
use lithium\net\http\Service;
use lithium\analysis\Logger;

use Exception;

/**
 * The Resque Command interacts with php-resque
 */
class Resque extends \lithium\console\Command {

	protected $queues = null;

	protected $host = null;

	protected $port = null;


	public function __construct(array $config = array()) {
		$this->setUp();
		parent::__construct($config);
	}

	protected function setUp()
	{
		if( empty( $this->host ) ) {
		    $this->host = Environment::get('resque.host');
		}

		if( empty ( $this->host ) ) {
		    $this->host = 'localhost';    
		}

		if( empty( $this->port ) ) {
		    $this->port = Environment::get('resque.port');
		}

		if( empty ( $this->port ) ) {
		    $this->port = 6379;    
		}

		ResqueProxy::setBackend($this->host . ':' . $this->port);

		$this->queues = ResqueProxy::queues();
	}

	/**
	 * show the current and overall status of resque
	 */
	public function status() {
		$output = array(
			array('CURRENT', ''),
		    array('Queue', 'Size'),
		    array('----', '---'),
		);

		foreach($this->queues as $queue) {
		    $output[] = array( $queue, ResqueProxy::size( $queue ) );
		}

		$output[] = array( 'failed', ResqueProxy::redis()->llen('failed') );


		$output[] = array('----', '---');
		$output[] = array('TOTALS', '');
		$output[] = array( 'processed', ResqueProxy::redis()->get('resque:stat:processed') );
		$output[] = array( 'failed', ResqueProxy::redis()->get('resque:stat:failed') );

		$this->columns($output);
	}

	/**
	 * removes all jobs from a specific queue
	 * @param  string $queueName name of queue to reset
	 */
	public function reset( $queueName ) {
		$count = 0;
		if( $queueName == 'failed' ) {

			while( ResqueProxy::redis()->lpop( 'failed' ) ) {
				$count++;
			}
		} else {

			if( !in_array( $queueName, $this->queues ) ) {
				throw new Exception( "Queue $queueName not found" );
			}
			while( ResqueProxy::pop( $queueName ) ) {
				$count++;
			}
		}

		$this->out( "Removed $count jobs from queue $queueName" );
	}


}
