<?php
/**
 * Autoloader class
 * 
 * @package    Framework
 * @category   PHPUnit
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * Based on the autoloader and file search methods from Kohana Framework
 * 
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
namespace Cz\PHPUnit;

class Autoloader
{
	/**
	 * @var  array
	 */
	private $directories = array();

	/**
	 * @param  array  $directories
	 */
	public function __construct($directories = array())
	{
		foreach ($directories as $path)
		{
			$this->addDirectory($path);
		}
	}

	/**
	 * @param   string  $path
	 * @return  Cz\PHPUnit\Autoloader
	 */
	public function addDirectory($path)
	{
		$this->directories[] = realpath($path).DIRECTORY_SEPARATOR;
		return $this;
	}

	/**
	 * @param   string   $class
	 * @return  boolean
	 */
	public function load($class, $suffix = '.php')
	{
		// Transform the class name according to PSR-0
		$class = ltrim($class, '\\');
		$file = '';
		$namespace = '';

		if (($lastNamespacePosition = strripos($class, '\\')))
		{
			$namespace = substr($class, 0, $lastNamespacePosition);
			$class = substr($class, $lastNamespacePosition + 1);
			$file = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
		}

		$file .= str_replace('_', DIRECTORY_SEPARATOR, $class);

		if (($path = $this->find($file.$suffix)))
		{
			// Load the class file
			require $path;

			// Class has been found
			return TRUE;
		}

		// Class is not in the filesystem
		return FALSE;
	}

	/**
	 * @param   string  $file
	 * @return  mixed
	 */
	public function find($file)
	{
		foreach ($this->directories as $dir)
		{
			if (is_file($dir.$file))
			{
				return $dir.$file;
			}
		}
	}
}