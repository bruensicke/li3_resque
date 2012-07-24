<?php

namespace li3_resque\extensions\command;

use Exception;

include dirname(__FILE__) . '/../../libraries/Resque/lib/Resque.php';
include dirname(__FILE__) . '/../../libraries/Resque/lib/Resque/Worker.php';
use Resque;
use Resque_Worker;

class ResqueWorker extends \lithium\console\Command {

    public $queues = '*';

    public $host = 'localhost';

    public $port = 6379;


    public function run() {
        Resque::setBackend($this->host . ':' . $this->port);

        $instance = new Resque_Worker($this->queues);

        if (!$instance){
            throw new Exception('Resque_Worker cant be instantiated!');
        }
        
        $this->out("Worker is up\n");
        $instance->work();
    }

}
