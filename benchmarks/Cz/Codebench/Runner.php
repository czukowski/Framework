<?php
namespace Cz\Codebench;

/**
 * Runner
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class Runner
{
	/**
	 * @var  string  Temporary dir path, will be deleted after benchmarks are finished.
	 */
	private $_tempDir;
	/**
	 * @var  boolean  Keep record about being initialized, cannot initialize twice!
	 */
	private static $_initialized;

	/**
	 * Runner object constructor.
	 * 
	 * @throws  \RuntimeException
	 */
	public function __construct($appDir)
	{
		if (self::$_initialized)
		{
			throw new \RuntimeException('Cannot initialize benchmark runner more than once!');
		}
		$this->_initializeTempDir();
		$this->_initializeKohanaPaths($appDir);
		$this->_initializeKohanaApplication();
	}

	/**
	 * Create temporary directory, will be deleted after benchmarks are finished.
	 */
	private function _initializeTempDir()
	{
		// Create directory.
		$tempPath = tempnam(sys_get_temp_dir(), 'cfb');
		if (file_exists($tempPath)) {
			unlink($tempPath);
		}
		mkdir($tempPath);

		// Schedule directory removal on script end.
		register_shutdown_function(function() use ($tempPath)
		{
			$files = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($tempPath, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::CHILD_FIRST
			);

			foreach ($files as $fileinfo)
			{
				$todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
				$todo($fileinfo->getRealPath());
			}

			rmdir($tempPath);
		});

		$this->_tempDir = $tempPath;
	}

	/**
	 * Initialize Kohana Framework paths and define its core constants if not defined already.
	 * 
	 * @param  string  $appDir  Kohana applicaion root dir.
	 */
	private function _initializeKohanaPaths($appDir)
	{
		// Define constants required by Kohana.
		$defineConstants = array(
			'DOCROOT' => $appDir.'/',
			'SYSPATH' => $appDir.'/../vendor/kohana/core/',
			'MODPATH' => $appDir.'/../modules/',
			'APPPATH' => $appDir.'/',
			'EXT' => '.php',
		);
		foreach ($defineConstants as $name => $value)
		{
			if ( ! defined($name))
			{
				define($name, $value);
			}
		}

		// Require Kohana Core classes.
		require_once SYSPATH.'classes/Kohana/Core'.EXT;
		require_once SYSPATH.'classes/Kohana'.EXT;
	}

	/**
	 * Initialize Kohana Framework application.
	 */
	private function _initializeKohanaApplication()
	{
		// Register Kohana autoloader.
		spl_autoload_register(array('Kohana', 'auto_load'));

		// Load `I18n` class in order to define `__` function that's used by Kohana for translations
		// and more importantly, exception messages.
		\I18n::lang('en');

		// Initialize Kohana.
		\Kohana::init(array(
			'errors' => FALSE,
			'cache_dir' => $this->_tempDir,
		));

		// Initialize CodeBench module.
		\Kohana::modules(array(
			'codebench' => MODPATH.'codebench',
		));

		// Initialize Kohana application config.
		\Kohana::$config->attach(new \Config_File);
	}
}
