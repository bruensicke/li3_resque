<?php

namespace li3_resque\extensions;

use lithium\core\Libraries;
use lithium\core\Environment;

use Exception;
use lithium\core\ClassNotFoundException;
use lithium\core\ConfigException;
use lithium\analysis\Logger;

if( !class_exists( 'Resque' ) ) {
    include dirname( __FILE__ ) . '/../libraries/Resque/lib/Resque.php';
}

if( !class_exists( 'ResqueScheduler' ) ) {
    include dirname( __FILE__ ) . '/../libraries/ResqueScheduler/lib/ResqueScheduler.php';
}

use Resque;
use ResqueScheduler;

/**
* actually a proxy for the php-resque lib
*/
class ResqueProxy extends \lithium\core\StaticObject {

    /**
     * Holds the configuration Options
     * @var array
    */
    protected static $_config = array(  );

    /**
     * These are the class `defaults`
     * @var array
     */
    protected static $_defaults = array( 
     );


    public function __construct( $config = array(  ) ) {
        if ( $config ){
            static::config( $config );
        }
    }

    /**
     *
     * @return void
     */
    public static function __init(  ) {
        $libraryConfig = Libraries::get( 'li3_resque' );
        static::config( $libraryConfig + static::$_defaults );
 
        if( Environment::get( 'resque.host' ) ) {
            static::$_config['host'] = Environment::get( 'resque.host' );
        }

        if( Environment::get( 'resque.port' ) ) {
            static::$_config['port'] = Environment::get( 'resque.port' );
        }

        if( !empty( static::$_config['host'] ) || !empty( static::$_config['port'] ) ) {
            try {
                Resque::setBackend( static::$_config['host'] . ':' . static::$_config['port']);
            } catch( Exception $e ) {
                throw new ConfigException('Could not connect to Resque server');
            }
        }
    }

    /**
     * Sets configurations for resque.
     * This Method is basically a copy and edit of the config in adaptable.
     *
     * @see lithium\core\adaptable
     *
     * @param array $config Configuratin.
     * @return array|void `Collection` of configurations or true if setting configurations.
     */
    public static function config( $config = null ) {
        //set if `config`is given
        if ( $config && is_array( $config ) ) {
            //filter only accepts configuration options
            foreach ( $config as $key => $value ){
                if ( \array_key_exists( $key, static::$_defaults ) ){
                    static::$_config[$key] = $value;
                }
            };
            return true;
        }

        //so we return the current config
        $result = static::$_config;
        return $result;
    }

    /**
     * Clears all configurations.
     *
     * @return void
     */
    public static function reset(  ) {
        static::$_config = array(  );
    }

    /**
     * Does proxying the method calls
     * @param string $method
     * @param mixed $arguments
     */
    public static function __callStatic( $method, $arguments ) {
        return static::run( $method,$arguments );
    }

    /**
     * Calls should be rerouted to the resqueInstance of the proxy
     * @todo insert a callable existance check
     *
     * @see lithium/core/StaticObject
     *
     * @throws resqueException
     *
     * @param string $method
     * @param mixed $params
     * @return mixed Return value of the called method
     * @filter this Method is filterable
     */
    public static function run( $method, $params = array(  ) ) {
        $params = compact( 'method', 'params' );
        return static::_filter( __FUNCTION__, $params, function( $self, $params ) {
            extract( $params );


            if ( \is_callable( "Resque::$method" ) ) {
                switch ( count( $params ) ) {
                    case 0:
                        return Resque::$method(  );
                    case 1:
                        return Resque::$method( $params[0] );
                    case 2:
                        return Resque::$method( $params[0], $params[1] );
                    case 3:
                        return Resque::$method( 
                            $params[0], $params[1], $params[2]
                         );
                    case 4:
                        return Resque::$method( 
                            $params[0], $params[1], $params[2], $params[3]
                         );
                    case 5:
                        return Resque::$method( 
                            $params[0], $params[1], $params[2], $params[3], $params[4]
                         );
                    default:
                        //i am not sure if this is a good idea
                        return call_user_func_array( array( get_called_class(  ), $method ), $params );
                }               
            } elseif( \is_callable( "ResqueScheduler::$method" ) ) {
                switch ( count( $params ) ) {
                    case 0:
                        return ResqueScheduler::$method(  );
                    case 1:
                        return ResqueScheduler::$method( $params[0] );
                    case 2:
                        return ResqueScheduler::$method( $params[0], $params[1] );
                    case 3:
                        return ResqueScheduler::$method( 
                            $params[0], $params[1], $params[2]
                         );
                    case 4:
                        return ResqueScheduler::$method( 
                            $params[0], $params[1], $params[2], $params[3]
                         );
                    case 5:
                        return ResqueScheduler::$method( 
                            $params[0], $params[1], $params[2], $params[3], $params[4]
                         );
                    default:
                        //i am not sure if this is a good idea
                        return call_user_func_array( array( get_called_class(  ), $method ), $params );
                } 
            } else {
                throw new Exception( __CLASS__ . " Method `$method` is not callable" );
            }

        } );
    }


    /**
     * checks the configuration against Problems ( and unsupported features )
     *
     * @todo finish this!
     *
     * @throws Exceptions if there are problems
     *
     * @param array $config
     * @return boolean
     * @filter This method may be filtered.
     */
    public static function checkConfiguration( $config = array(  ) ){
        $params = compact( 'config' );
        return static::_filter( __FUNCTION__, $params, function( $self, $params ) {
            extract( $params );

            if ( !$config ){
            $config = $self::invokeMethod( 'config' );
            }
            // if ( empty( $config['appId'] ) ){
            //  throw new ConfigException( 'Configuration: `appId` should be set' );
            // }
            // if ( empty( $config['secret'] ) ){
            //  throw new ConfigException( 'Configuration: `secret` should be set' );
            // }
            // if ( !empty( $config['cookie'] ) ){
            //  throw new ConfigException( 'Configuration: `cookie` not yet supported' );
            // }
            // if ( !empty( $config['domain'] ) ){
            //  throw new ConfigException( 'Configuration: `domain` not yet supported' );
            // }
            // if ( !empty( $config['fileUpload'] ) ){
            //  throw new ConfigException( 'Configuration: `fileUpload` not yet supported' );
            // }
            return true;
        } );
    }

}

?>