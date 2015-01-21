<?php
namespace Cz\Codebench\Printers;

/**
 * Benchmark results printer base class.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 * 
 * Using the default codebench template logic from Kohana/Codebench module.
 * 
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class Printer
{
	/**
	 * @var  array
	 */
	protected $_results;

	/**
	 * @param  array  $results  Codebench results
	 */
	public function __construct(array $results)
	{
		$this->_results = $results;
	}

	/**
	 * Prints Codebench results.
	 */
	abstract public function printResults();
}
