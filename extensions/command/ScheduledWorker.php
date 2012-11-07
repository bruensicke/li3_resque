<?php

namespace li3_resque\extensions\command;

use Exception;
use lithium\core\Environment;
use li3_resque\extensions\ResqueProxy;

include dirname(__FILE__) . '/../../libraries/ResqueScheduler/lib/ResqueScheduler/Worker.php';
use ResqueScheduler_Worker;

class ScheduledWorker extends \lithium\console\Command {

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

        if( empty ( $this->interval ) ) {
            $this->interval = 5;    
        }

        if( $this->loglevel === null ) {
            $this->loglevel = Environment::get('resque.loglevel');
        }

        if( $this->loglevel === null ) {
            $this->loglevel = Resque_Worker::LOG_NONE;
        }

        ResqueProxy::setBackend($this->host . ':' . $this->port);

        $instance = new ResqueScheduler_Worker();

        if (!$instance){
            throw new Exception('ResqueScheduler_Worker cant be instantiated!');
        }

        $instance->logLevel = $this->loglevel;

        $this->out("Scheduled Worker is up listening at {$this->host}:{$this->port} every {$this->interval} seconds \n");
        
        if (($pidfile = $this->pidfile) && $this->D) {
          $pid = file_get_contents($pidfile);
          if ($pid && !posix_kill($pid, SIGQUIT)) {
            $this->out("Error: Unable to shutdown previous worker");
            die;
          }
        }
        
        if ($this->D) {
          $pid = pcntl_fork();
          if (-1 == $pid) {
            $this->out("Error: Unable to fork");
            die;
          } else if ($pid) {
            // Shutdown the parent
            $this->out("Successfully backgrounded job");
            exit(0);
          }
          // We're in the child now
          posix_setsid();
          if ($pidfile = $this->pidfile) {
            file_put_contents($pidfile, posix_getpid());
          }
          fclose(STDOUT); fclose(STDERR); fclose(STDIN);
          ob_start(); // Capture all output
          $instance->work($this->interval);
          ob_end_flush();
        } else {
          $instance->work($this->interval);
        }
        
    }


}
