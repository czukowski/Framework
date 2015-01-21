<?php
/**
 * Kohana Codebench bootstrap for Framework
 * 
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 */

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../classes/Cz/Framework/Autoloader.php';
spl_autoload_register(array(
	new Cz\Framework\Autoloader(array(__DIR__, __DIR__.'/../classes', __DIR__.'/../tests')),
	'load',
));

$args = $_SERVER['argv'];
if ( ! isset($args[1]))
{
	die('Too few arguments.');
}

new Cz\Codebench\Environment(__DIR__);

$runner = new Cz\Codebench\Runner($args[1]);
$results = $runner->run();

$printer = new Cz\Codebench\Printers\ByMethod($results);
$printer->printResults();
