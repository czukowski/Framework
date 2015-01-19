<?php
/**
 * Kohana Codebench bootstrap for Framework
 * 
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 */

require_once __DIR__.'/../classes/Cz/Framework/Autoloader.php';
spl_autoload_register(array(
	new Cz\Framework\Autoloader(array(__DIR__, __DIR__.'/../classes')),
	'load',
));

$runner = new Cz\Codebench\Runner(__DIR__);

new Codebench();
