<?php
use lithium\core\Libraries;

define('LI3_RESQUE_PATH', dirname(__DIR__));
define('LI3_RESQUE_LIB_PATH', LI3_RESQUE_PATH . '/libraries/Resque');

// load up third party lib
// Libraries::add('resque', array(
// 	'path' => LI3_RESQUE_LIB_PATH,
// 	'prefix' => false,
// 	'transform' => function($class, $config) {
// 		$class = str_replace("_", "/", $class);
// 		return "{$config['path']}/{$class}{$config['suffix']}";
// 	}
// ));

if (Libraries::paths('job') === null) {
	Libraries::paths(
		array(
			'job' => '{:library}\extensions\job\{:name}'
			)
		);
}

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
	require __DIR__ . '/../vendor/autoload.php';
}

?>