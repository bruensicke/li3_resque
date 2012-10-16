<?php

namespace li3_resque\extensions\command;

use Exception;
use lithium\core\Environment;
use li3_resque\extensions\ResqueProxy;

include dirname(__FILE__) . '/../../libraries/Resque/lib/Resque/Worker.php';
use Resque_Worker;

class ResqueWorker extends \lithium\console\Command {

    public $queues = null;

    public $host = null;

    public $port = null;

    public $loglevel = null;


    public function run() {

        if( empty( $this->queues ) ) {
            $this->queues = Environment::get('resque.queues');
        }

        if( empty( $this->queues ) ) {
            throw new Exception('you need to define queues for the worked, either in the environment (as resque.queues) or using -queues');
        }

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

        if( $this->loglevel === null ) {
            $this->loglevel = Environment::get('resque.loglevel');
        }

        if( $this->loglevel === null ) {
            $this->loglevel = Resque_Worker::LOG_NONE;
        }

        ResqueProxy::setBackend($this->host . ':' . $this->port);
        
        $this->queues = is_string($this->queues) ? explode(',', $this->queues) : $this->queues;
        $instance = new Resque_Worker($this->queues);

        if (!$instance){
            throw new Exception('Resque_Worker cant be instantiated!');
        }

        $instance->logLevel = $this->loglevel;

        $this->out("Worker is up listening to queues '" . join(',', $this->queues) ."' at {$this->host}:{$this->port} \n");
        $instance->work();
    }


}
