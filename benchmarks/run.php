<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__.'/../classes/Cz/Framework/Autoloader.php';
spl_autoload_register(array(
	new Cz\Framework\Autoloader(array(__DIR__, __DIR__.'/../classes')),
	'load',
));

$runner = new Cz\Codebench\Runner(__DIR__);

new Codebench();
