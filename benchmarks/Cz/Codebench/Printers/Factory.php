<?php
namespace Cz\Codebench\Printers;

/**
 * Benchmark results printer object factory.
 * 
 * @package    Framework
 * @category   Codebench
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 */
class Factory
{
	/**
	 * @var  string
	 */
	public $defaultPrinter = 'byMethod';

	/**
	 * Create results printer of the specified type.
	 * 
	 * @param   string  $printer  Output printer id.
	 * @return  Printer
	 */
	public function createPrinter($printer)
	{
		if ( ! $printer)
		{
			$printer = $this->defaultPrinter;
		}
		$className = __NAMESPACE__.'\\'.ucfirst($printer);
		if ( ! class_exists($className))
		{
			throw new \InvalidArgumentException("Benchmark result printer `$printer` not exists!");
		}
		return new $className;
	}

	/**
	 * Create output printer and print results.
	 * 
	 * @param  array   $results  Benchmark results
	 * @param  string  $printer  Output printer id
	 */
	public function render($results, $printer = NULL)
	{
		$this->createPrinter($printer)
			->setResults($results)
			->render($results);
	}
}
