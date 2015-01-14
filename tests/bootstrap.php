<?php
/**
 * PHPUnit bootstrap for Framework
 * 
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
require_once __DIR__.'/../classes/Cz/Framework/Autoloader.php';
spl_autoload_register(array(
	new Cz\Framework\Autoloader(array(__DIR__, __DIR__.'/../classes')),
	'load',
));
