<?php
/**
 * Kohana Codebench bootstrap for Framework
 * 
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 * 
 * Uses CommandLine.php script to parse command line arguments.
 * 
 * @link       https://github.com/pwfisher/CommandLine.php
 * @copyright  (c) 2009 Patrick Fisher
 * @license    Creative Commons Attribution License
 */

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../classes/Cz/Framework/Autoloader.php';
spl_autoload_register(array(
	new Cz\Framework\Autoloader(array(__DIR__, __DIR__.'/../classes', __DIR__.'/../tests')),
	'load',
));

$args = CommandLine::parseArgs($_SERVER['argv']);
if (empty($args[0]))
{
	die('Too few arguments.');
}
elseif ( ! isset($args[0]))
{
	die('Benchmark classname not specified');
}

new Cz\Codebench\Environment(__DIR__);

$runner = new Cz\Codebench\Runner($args[0]);
$benchmarks = $runner->run();

$results = new Cz\Codebench\Printers\Factory;
$results->render($benchmarks, isset($args['printer']) ? $args['printer'] : NULL);
