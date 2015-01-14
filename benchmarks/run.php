<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define('DOCROOT', __DIR__.'/');
define('SYSPATH', __DIR__.'/../vendor/kohana/core/');
define('MODPATH', __DIR__.'/../modules/');
define('APPPATH', DOCROOT);
define('EXT', '.php');

require_once __DIR__.'/../vendor/autoload'.EXT;
require_once SYSPATH.'classes/Kohana/Core'.EXT;
require_once SYSPATH.'classes/Kohana'.EXT;

spl_autoload_register(array('Kohana', 'auto_load'));

$tempfile = tempnam(sys_get_temp_dir(), 'cfb');
if (file_exists($tempfile)) {
	unlink($tempfile);
}
mkdir($tempfile);

register_shutdown_function(function() use ($tempfile) {
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($tempfile, RecursiveDirectoryIterator::SKIP_DOTS),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ($files as $fileinfo) {
		$todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
		$todo($fileinfo->getRealPath());
	}

	rmdir($tempfile);
});

Kohana::init(array(
	'errors' => FALSE,
	'cache_dir' => $tempfile,
));
I18n::lang('en');
Kohana::modules(array(
	'codebench' => MODPATH.'codebench',
));
Kohana::$config->attach(new Config_File);

new Codebench();
