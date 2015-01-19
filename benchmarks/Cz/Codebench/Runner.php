<?php
namespace Cz\Codebench;

/**
 * Benchmark runner.
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
	 * @var  \Kohana_Codebench  Benchmark object instance.
	 */
	private $_codebench = array();

	/**
	 * Runner object constructor.
	 * 
	 * @param  string  $className  Benchmark class name
	 */
	public function __construct($className)
	{
		if ( ! class_exists($className))
		{
			throw new \InvalidArgumentException('Class `'.$className.'` not found!');
		}
		$this->_codebench = new $className;
		if ( ! $this->_codebench instanceof \Kohana_Codebench)
		{
			throw new \InvalidArgumentException('Class `'.$className.'` is not a subclass of `Kohana_Codebench`!');
		}
	}

	/**
	 * Executes benchmark and returns the results.
	 * 
	 * @return  array
	 */
	public function run()
	{
		return $this->_codebench->run();
	}
}
