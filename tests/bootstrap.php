<?php
/**
 * PHPUnit bootstrap for Framework
 * 
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
require_once __DIR__.'/Cz/PHPUnit/Autoloader.php';
spl_autoload_register(array(
	new Cz\PHPUnit\Autoloader(array(__DIR__, __DIR__.'/../classes')),
	'load',
));